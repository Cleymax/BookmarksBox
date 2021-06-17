<?php

namespace App\Security;

use App\Database\Query;
use App\Exceptions\NotFoundException;
use App\Exceptions\TokenNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Services\FlashService;
use App\Services\JwtService;
use App\Services\MailService;
use App\Tools\Str;
use PHPMailer\PHPMailer\Exception;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

/**
 * Class Auth
 * @package App\Security
 */
class Auth
{
    private const SESSION_NAME = 'BB';

    /**
     * @throws AuthException
     */
    public static function register(string $mail, string $username, string $password, string $confirm): bool
    {
        if ($password != $confirm) {
            throw new AuthException('Votre mot de passe n\'est pas identique !');
        }

        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }

        $query = (new Query())
            ->select("id", "email", "username")
            ->from("users")
            ->where("email = ? or username = ?")
            ->limit(1)
            ->params([$mail, $username]);

        $count = $query->rowCount();

        if ($count == 1) {
            throw new AuthException('Votre mail ou votre nom d\' utilisateur est déjà utilisé');
        }

        $password = password_hash($_ENV['SALT'] . $password, PASSWORD_BCRYPT);

        $key = Str::random(32);

        $query = (new Query())
            ->insert("email", "username", "password", "verify_key")
            ->into("users")
            ->values(["?", "?", "?", "?"])
            ->params([$mail, $username, $password, $key])
            ->returning('id');

        $response = $query->first();

        try {
            $link = get_query_url('verify_account', [
                'id' => $response->id,
                'key' => $key
            ]);

            MailService::send_template('verify_account', $mail, 'Confirmation d\'adresse mail', [
                'link' => $link,
                'username' => $username
            ]);
        } catch (Exception $e) {
            throw new AuthException('Erreur lors de l\'envoie de l\'email !');
        }
        return true;
    }

    /**
     * @throws AuthException
     */
    public static function login(string $email, string $password, bool $remember = false): bool
    {
        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }

        $response = self::verifyLogin($email, $password);

        if ($remember) {
            setcookie(self::SESSION_NAME . '_RM', $response->id . '-' . hash_hmac('sha256', $response->email . $response->password, $_ENV['SALT']), time() + 3600 * 24 * 30, '/', '', true, true);
        }

        $hasTotp = $response->totp != null;
        $_SESSION['user'] = array(
            'id' => $response->id,
            'email' => $response->email,
            'username' => $response->username,
            'logged' => !$hasTotp
        );
        return !$hasTotp;
    }

    /**
     * @throws UserNotFoundException
     */
    public static function remember_me(): bool
    {
        if (!empty($_COOKIE) && isset($_COOKIE[self::SESSION_NAME . '_RM'])) {
            $parts = explode("-", $_COOKIE[self::SESSION_NAME . '_RM']);

            if (sizeof($parts) != 2 || !is_numeric($parts[0])) {
                return false;
            }

            $id = intval($parts[0]);

            $query = (new Query())
                ->select("email", "password", "username")
                ->from("users")
                ->where("id = ?")
                ->limit(1)
                ->params([$id]);

            $count = $query->rowCount();
            if ($count == 0) {
                throw new UserNotFoundException();
            }

            $response = $query->first();
            $mail = $response->email;
            $password = $response->password;

            if (hash_hmac('sha256', $mail . $password, $_ENV['SALT']) == $parts[1]) {
                $_SESSION['user'] = array(
                    'id' => $id,
                    'email' => $mail,
                    'username' => $response->username,
                    'logged' => true
                );
                return true;
            }
        }
        return false;
    }

    /**
     * @throws AuthException
     * @throws UserNotFoundException
     */
    public static function totp($code): bool
    {
        preg_match('/[0-9]{6}/', $code, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Merci de rentrer un code corect !');
        }

        $query = (new Query())
            ->select("id", "totp")
            ->from("users")
            ->where("id = ?")
            ->params([self::user()->id]);

        if ($query->rowCount() == 0) {
            throw new UserNotFoundException();
        }

        $response = $query->first();

        $g = new GoogleAuthenticator();
        return $g->checkCode($response->totp, $code);
    }

    public static function logout()
    {
        setcookie(self::SESSION_NAME . '_RM', '', time() - 1000, '/', false, true);
        $_SESSION['user'] = null;
        FlashService::success("Déconnexion réusis !", 5);
        header('Location: ' . $_ENV['BASE_URL'] . '/auth/login');
        die();
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['logged'];
    }

    public static function user(): object
    {
        if (Auth::check()) {
            return (object)$_SESSION['user'];
        }
    }

    public static function checkApi(): bool
    {
        try {
            self::userApi();
            return true;
        } catch (TokenNotFoundException $e) {
            return false;
        }
    }

    /**
     * @throws TokenNotFoundException
     */
    public static function userApi(): object
    {
        if(Auth::check()){
            return Auth::user();
        }

        if (!isset($_SERVER['HTTP_AUTHORIZATION']) || !preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            throw new TokenNotFoundException(' Please authenticate you !');
        }

        $token = $matches[1];

        if (!$token) {
            throw new TokenNotFoundException();
        }

        return JwtService::verify($token);
    }

    /**
     * @throws UserNotFoundException
     * @throws AuthException
     */
    public static function verify($id, string $key): bool
    {
        if (!is_numeric($id)) {
            throw new UserNotFoundException();
        }

        $query = (new Query())
            ->select('verify', 'verify_key')
            ->from('users')
            ->where('id = ?')
            ->params([$id]);

        $response = $query->first();

        if ($response->verify) {
            throw new AuthException('Compte déjà verifié !');
        }

        if ($response->verify_key != $key) {
            throw new AuthException('Lien invalide !');
        }

        $query = (new Query())
            ->update()
            ->from('users')
            ->set([
                'verify_key' => 'NULL',
                'verify' => 'True'
            ])
            ->where('id = ?')
            ->params([$id]);
        $query->execute();
        return true;
    }

    /**
     * @throws AuthException
     * @throws UserNotFoundException
     */
    public static function reset(string $mail, bool $force = false): bool
    {
        $query = (new Query())
            ->select('id', 'username', 'password_reset_key')
            ->from('users')
            ->where('email = ?')
            ->params([$mail]);

        if ($query->rowCount() == 0) {
            throw new UserNotFoundException();
        }

        $response = $query->first();

        if ($response->password_reset_key != null && !$force) {
            throw new AuthException('Un liens a déjà été envoyé par email !');
        }

        $username = $response->username;
        $id = $response->id;
        $key = Str::random(32);

        $link = get_query_url('reset-password', [
            'id' => $response->id,
            'key' => $key
        ]);

        try {
            MailService::send_template('reset-password', $mail, 'Demande de réinitialisation de mot de passe', [
                'link' => $link,
                'username' => $username
            ]);
        } catch (Exception $e) {
            throw new AuthException("Erreur lors de l'envoie de l'email !");
        }

        (new Query())->update()->from('users')->set(['password_reset_key' => '?'])->where('id = ?')->params([$key, $id])->first();
        return true;
    }

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public static function check_reset_password($id, $key)
    {
        $query = (new Query())
            ->select('id')
            ->from('users')
            ->where('id = ?', 'password_reset_key = ?')
            ->params([
                $id,
                $key
            ]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Liens invalide !');
        }
    }

    /**
     * @throws AuthException|\App\Exceptions\NotFoundException
     */
    public static function reset_password(string $password, string $confirm, $id, $key): bool
    {
        if ($password != $confirm) {
            throw new AuthException("Votre mot de passe n'est pas identique");
        }
        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }
        self::check_reset_password($id, $key);

        $password = password_hash($_ENV['SALT'] . $password, PASSWORD_BCRYPT);

        (new Query())
            ->update()
            ->from('users')
            ->set(['password_reset_key' => 'NULL', 'password' => '?'])
            ->where('id =?')
            ->params([$password, $id])->execute();
        return true;
    }

    /**
     * @throws AuthException
     */
    public static function verifyLogin(string $email, string $password): object
    {
        $query = (new Query())
            ->select("id", "verify", "password", "email", "last_name", "first_name", "username", "totp")
            ->from("users")
            ->where("LOWER(email) LIKE LOWER(?) or LOWER(username) LIKE LOWER(?)")
            ->params([$email, $email]);
        $count = $query->rowCount();
        $response = $query->first();

        if (!$count || !password_verify($_ENV['SALT'] . $password, $response->password)) {
            throw new AuthException('Identifiant ou mot de passe incorrect !');
        }
        if (!$response->verify) {
            throw new AuthException('Compte non verifié ! Regardez vos emails !');
        }

        return $response;
    }

    /**
     * @throws AuthException
     * @throws TokenNotFoundException
     */
    public static function verifyLoginToken(string $token)
    {
        $query = (new Query())
            ->select("users.id", "verify", "password", "email", "last_name", "first_name", "username", "totp")
            ->from('users_tokens')
            ->inner('users', 'user_id', 'id')
            ->where('token = ?')
            ->params([$token]);

        $count = $query->rowCount();
        $response = $query->first();

        if ($count == 0) {
            throw new TokenNotFoundException();
        }

        if (!$response->verify) {
            throw new AuthException('Compte non verifié ! Regardez vos emails !');
        }

        return $response;
    }


    public static function checkCas($u): bool
    {
        $query = (new Query())
            ->select("email", "id", "username")
            ->from("users")
            ->where("LOWER(username) = LOWER(?)")
            ->params([$u]);

        $count = $query->rowCount();
        if ($count == 0) {
            $email = "$u@iut-acy.univ-smb.fr";
            $query = (new Query())
                ->insert("email", "username", "password", "verify")
                ->into("users")
                ->values(["?", "?", "?", true])
                ->params([$email, $u, 'CAS'])
                ->returning('id');

            $response = $query->first();
            $id = $response->id;
            $username = $u;
        } else {
            $response = $query->first();
            $id = $response->id;
            $email = $response->email;
            $username = $response->username;
        }
        $_SESSION['user'] = array(
            'id' => $id,
            'email' => $email,
            'username' => $username,
            'logged' => true
        );
        return true;
    }
}
