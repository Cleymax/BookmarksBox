<?php

namespace App\Security;

use App\Database\Query;
use App\Services\DebugBarService;
use App\Services\FlashService;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Auth
{
    private const SESSION_NAME = 'RT';
    private const PASSWORD_BCRYPT_COST = 12;

    public static function register()
    {

    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return bool
     * @throws \App\Security\AuthException
     */
    public static function login(string $email, string $password, bool $remember = false): bool
    {
        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }

        if($_POST['login'] == 'iutannecy'){

        }else {
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

    public static function remember_me()
    {

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
