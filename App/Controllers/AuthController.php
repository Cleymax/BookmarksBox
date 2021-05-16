<?php

namespace App\Controllers;

use App\Database\Query;
use App\Security\Auth;
use App\Services\FlashService;
use App\Views\View;

class AuthController extends Controller
{
    public function loginView()
    {
        $this->render(View::new('auth.login'), 'Connexion', ['username' => $_GET['username'] ?? '']);
    }

    public function login()
    {
        if (Auth::check()) {
            $this->redirect("dashboard");
            return;
        }
        try {
            $this->checkCsrf();
            $this->checkPost('username', 'Merci de rentrer un nom d\'utilisateur correct !', '\w{2,12}');
            $this->checkPost('password', 'Merci de rentrer votre mot de passe !');
            $remember_me = isset($_POST['remember']) && $_POST['remember'] == 'on';

            if (!Auth::login($_POST['username'], $_POST['password'], $remember_me)) {
                $this->redirect("2fa");
            } else {
                FlashService::success("Connexion réussi.", 10);
                if (!isset($_POST['redirect_to'])) {
                    $this->redirect('dashboard');
                } else {
                    $this->redirect($_GET['redirect_to']);
                }
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code(400);
            $this->render(View::new('auth.login'), 'Connexion');
        }
    }

    public function registerView()
    {
        $this->render(View::new('auth.register'), 'Inscription');
    }

    public function twofaView()
    {
        $this->render(View::new('auth.2fa'), 'Double authentification');
    }

    public function twofa()
    {
        if (Auth::check()) {
            $this->redirect("dashboard");
            return;
        }
        try {
            $this->checkCsrf();
            $this->checkPost('code', 'Merci de rentrer un code corect !', '\d{6}');

            if (!Auth::totp($_POST['code'])) {
                FlashService::error("Code éronné !");
                $this->render(View::new('auth.2fa'), 'Double authentification');
            } else {
                $_SESSION['user']['logged'] = true;
                FlashService::success("Connexion réussi.", 10);
                $this->redirect("dashboard");
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code(400);
            $this->render(View::new('auth.2fa'), 'Double authentification');
        }
    }

    public function register()
    {
        if (Auth::check()) {
            $this->redirect("dashboard");
            return;
        }

        try {
            $this->checkCsrf();
            $this->checkPost('email', 'Merci de rentrer un email correct !');
            $this->checkPost('username', 'Merci de rentrer un nom d\'utilisateur correct !', '\w{2,12}');
            $this->checkPost('password', 'Merci de rentrer votre mot de passe !');
            $this->checkPost('confirm', 'Merci de bien vouloir confirmer votre mot de passe !');

            if (Auth::register($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm'])) {
                FlashService::success("Inscription réusis !", 10);
                FlashService::success("Verifier votre compte en cliquant sur le liens envoyé dans votre boîte mail !", 15);
                $this->redirect("login");
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code(400);
            $this->render(View::new('auth.register'), 'Inscription');
        }
    }

    public function verify()
    {
        if (Auth::check()) {
            $this->redirect("dashboard");
            return;
        }

        try {
            $this->checkGet('id', 'Lien de verification éronné !', '\d+');
            $this->checkGet('key', 'Lien de verification éronné !');

            if (Auth::verify($_GET['id'], $_GET['key'])) {
                $username = (new Query())->select('username')->from('users')->where('id =?')->params([intval($_GET['id'])])->first()->username;
                FlashService::success("Compte vérifié avec succès !");
                $this->redirect('auth/login?username=' . $username);
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            $this->redirect('login');
        }
    }

    public function password_resetView()
    {
        $this->render(View::new('auth.password-forgot'), 'Réinitialisation de mot de passe');
    }

    public function password_reset()
    {
        try {
            $this->checkCsrf();
            $this->checkPost('mail', 'Merci de préciser une adresse mail valide !');
            $this->checkEmail($_POST['mail'], 'Format de l\'adresse mail non valide !');
            $force = isset($_POST['force']) && $_POST['force'] == 'on';
            if (Auth::reset($_POST['mail'], $force)) {
                FlashService::success("Liens envoyé par email !");
                $this->render(View::new('auth.password-forgot'), 'Réinitialisation de mot de passe');
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            $this->render(View::new('auth.password-forgot'), 'Réinitialisation de mot de passe');
        }
    }

    public function reset_password_view()
    {
        try {
            $this->checkGet('id', 'Liens invalide !', '\d+');
            $this->checkGet('key', 'Liens invalide !', '\w{32}');

            Auth::check_reset_password($_GET['id'], $_GET['key']);

            $this->render(View::new('auth.reset-password'), 'Réinitialisation de mot de passe');
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            $this->redirect('login');
        }
    }

    public function reset_password()
    {
        try {
            $this->checkCsrf();
            $this->checkGet('id', 'Liens de réinitialisation plus valide !', '\d+');
            $this->checkGet('key', 'Liens de réinitialisation plus valide !', '\w{32}');
            $this->checkPost('password', 'Merci de rentrer votre nouveau mot de passe !');
            $this->checkPost('confirm', 'Merci de bien vouloir confirmer votre nouveau mot de passe !');

            if (Auth::reset_password($_POST['password'], $_POST['confirm'], $_GET['id'], $_GET['key'])) {
                FlashService::success('Mot de passe réinitialisé !');
                $this->redirect('login');
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            $this->render(View::new('auth.reset-password'), 'Réinitialisation de mot de passe', [
                'id' => $_GET['id'],
                'key' => $_GET['key']
            ]);
        }
    }
}
