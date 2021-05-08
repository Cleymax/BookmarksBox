<?php

namespace App\Security;

use App\Database\Query;
use App\Router\Router;
use App\Services\FlashService;
use App\Services\MailService;
use App\Tools\Str;
use PHPMailer\PHPMailer\Exception;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Auth
{
    private const SESSION_NAME = 'BB';

    /**
     * @throws \App\Security\AuthException
     */
    public static function register(string $mail, string $username, string $password, string $confirm): bool
    {
        if ($_POST['password'] != $_POST['confirm']) {
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
            $link = Router::get_url('verify_account', [
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

    public static function login(string $email, string $password, bool $remember = false): bool
    {
        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }

        if ($_POST['login'] == 'iutannecy') {

        } else {
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
            if ($remember) {
                setcookie(self::SESSION_NAME . '_RM', $response->id . '-' . sha1($response->email . $response->password), time() + 3600 * 24 * 30, '/', false, true);
            }

            $hasTotp = $response->totp != null;
            $_SESSION['user'] = array(
                'id' => $response->id,
                'email' => $email,
                'username' => $response->username,
                'logged' => !$hasTotp
            );
            return !$hasTotp;
        }
    }

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

            $response = $query->first();
            $mail = $response->email;
            $password = $response->password;

            if (sha1($mail . $password) == $parts[1]) {
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
            throw new AuthException('Utilisateur non trouvé !');
        }

        $response = $query->first();

        $g = new GoogleAuthenticator();
        return $g->checkCode($response->totp, $code);
    }

    public static function logout()
    {
        setcookie(self::SESSION_NAME . '_RM', '', time() - 1000, '/', false, true);
        $_SESSION['user'] = [];
        FlashService::success("Déconnexion réusis !", 5);
        header('Location: '.$_ENV['BASE_URL'].'/auth/login');
        die();
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['logged'];
    }

    public static function user(): object
    {
        return (object)$_SESSION['user'];
    }

    /**
     * @throws \Exception
     */
    public static function verify($id, string $key): bool
    {
        if (!is_numeric($id)) {
            throw new \Exception('Compte non trouvé !');
        }

        $query = (new Query())
            ->select('verify', 'verify_key')
            ->from('users')
            ->where('id = ?')
            ->params([$id]);

        $response = $query->first();

        if ($response->verify) {
            throw new \Exception('Compte déjà verifié !');
        }

        if ($response->verify_key != $key) {
            throw new \Exception('Lien invalide !');
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
     * @throws \App\Security\AuthException
     */
    public static function reset(string $mail, bool $force = false): bool
    {
        $query = (new Query())
            ->select('id', 'username', 'password_reset_key')
            ->from('users')
            ->where('email = ?')
            ->params([$mail]);

        if ($query->rowCount() == 0) {
            throw new AuthException('Utilisateur non trouvé !');
        }

        $response = $query->first();

        if ($response->password_reset_key != null && !$force) {
            throw new AuthException('Un liens a déjà été envoyé par email !');
        }

        $username = $response->username;
        $id = $response->id;
        $key = Str::random(32);

        $link = Router::get_url('reset-password', [
            'id' => $response->id,
            'key' => $key
        ]);

        try {
            MailService::send_template('reset-password', $mail, 'Demande de réinitialisation de mot de passe', [
                'link' => $link,
                'username' => $username
            ]);
        } catch (Exception $e) {
            throw new AuthException('Erreur lors de l\'envoie de l\'email !');
        }

        (new Query())->update()->from('users')->set(['password_reset_key' => '?'])->where('id = ?')->params([$key, $id])->first();
        return true;
    }

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
            throw new AuthException('Liens invalide !');
        }
    }

    /**
     * @throws \App\Security\AuthException
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
}
