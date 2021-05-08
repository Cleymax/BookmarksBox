<?php

namespace App\Controllers;

use App\Security\Auth;
use App\Views\View;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->loadModel('User','Users');
    }

    public function profileView()
    {
        $data = $this->User->getById(Auth::user()->id);
        $this->render(View::new('profil'), 'Profile', ["data" => $data]);
    }
}
