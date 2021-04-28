<?php

namespace App\Security;

use App\Database\Query;
use App\Services\FlashService;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Auth
{
    private const SESSION_NAME = 'RT';

    public static function register(string $mail, string $username, string $password, string $confirm): bool
    {
        if ($_POST['password'] != $_POST['confirm']) {
            FlashService::error("Votre mot de passe n'est pas identique");
        }

        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        } else {
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

            $query = (new Query())
                ->insert("email", "username", "password")
                ->into("users")
                ->values([$mail, $username, $password])
                ->returning('id');

            $response = $query->first();

            $_SESSION['user'] = array(
                'id' => $response->id,
                'email' => $mail,
                'logged' => true
            );

            return true;
        }
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
                ->select("id", "verify", "password", "email", "last_name", "first_name", "totp")
                ->from("users")
                ->where("email = ?")
                ->params([$email]);
            $count = $query->rowCount();
            $response = $query->first();

            if (!$count || !password_verify($_ENV['SALT'] . $password, $response->password)) {
                throw new AuthException('Identifiant ou mot de passe incorrect !');
            }
            if (!$response->verify) {
                throw new AuthException('Please see your email ! Verify your account !');
            }
            if ($remember) {
                setcookie(self::SESSION_NAME . '_RM', $response->id . '-' . sha1($response->email . $response->password), time() + 3600 * 24 * 30, '/', false, true);
            }
            $hasTotp = $response->totp != null;
            $_SESSION['user'] = array(
                'id' => $response->id,
                'email' => $email,
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
                ->select("email", "password")
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
        $_SESSION['user'] = [];
        FlashService::success("Déconnexion réusis !");
        header('Location: /auth/login');
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['logged'];
    }

    private static function user(): object
    {
        return (object)$_SESSION['user'];
    }
}
