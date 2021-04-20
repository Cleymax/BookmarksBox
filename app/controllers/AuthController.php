<?php

namespace App\Controllers;

use App\Database\Query;
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
        FlashService::error("Tu n'as pas entré ton mot passe !", 25);
        FlashService::error("Tu n'as pas entré ton email !", 25);
        FlashService::info("En cours de dév", 25);
        FlashService::success("Connexion réussi", 25);
        $this->render(View::new('auth.login'), 'Connexion');
    }

    public function registerView()
    {
        $this->render(View::new('auth.register'), 'Inscription');
    }
}
