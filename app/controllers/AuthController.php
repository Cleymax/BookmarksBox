<?php

namespace App\Controllers;

use App\Security\Auth;
use App\Security\AuthException;
use App\Services\FlashService;
use App\Views\View;

class AuthController extends Controller
{
    public function loginView()
    {
        $this->render(View::new('auth.login'), 'Connexion');
    }


    public function login()
    {
        if (Auth::check()) {
            $this->redirect("dashboard");
            return;
        }
        try {
            $this->check($_POST['username'], 'Merci de rentrer un nom d\'utilisateur correct !');
            $this->check($_POST['password'], 'Merci de rentrer votre mot de passe !');
            $remember_me = isset($_POST['remember']) && $_POST['remember'] == 'on';
            if (!Auth::login($_POST['username'], $_POST['password'], $remember_me)) {
                $this->redirect("2fa");
            } else {
                FlashService::success("Connexion réussi.", 10);
                $this->redirect("dashboard");
            }
        } catch (AuthException $e) {
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
            $this->check($_POST['code'], 'Merci de rentrer un code corect !');
            $result = Auth::totp($_POST['code']);
            if (!$result) {
                FlashService::error("Code éronné !");
                $this->render(View::new('auth.2fa'), 'Double authentification');
            } else {
                $_SESSION['user']['logged'] = true;
                FlashService::success("Connexion réussi.", 10);
                $this->redirect("dashboard");
            }
        } catch (AuthException $e) {
            FlashService::error($e->getMessage());
            http_response_code(400);
            $this->render(View::new('auth.2fa'), 'Double authentification');
        }
    }
}
