<?php

namespace App\Controllers;

use App\Views\View;

class AuthController extends Controller
{
    public function loginView()
    {
        $this->render(View::new('auth.login'), 'Connexion');
    }

    public function registerView()
    {
        $this->render(View::new('auth.register'), 'Inscription');
    }
}
