<?php

namespace App\Controllers;

use App\Views\View;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $this->render(View::new('dashboard'), 'Accueil');
    }
}
