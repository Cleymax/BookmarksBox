<?php

namespace App\Controllers;

use App\Database\Query;
use App\Security\Auth;
use App\Views\View;

class UserController extends Controller
{

    public function profileView()
    {

        $query = (new Query())
            ->select("first_name", "last_name", "email", "username", "avatar", "bio", "created_at", "verify")
            ->from("users")
            ->limit(1)
            ->where("id = ?")
            ->params([Auth::user()->id]);

        $response = $query->first();

        $this->render(View::new('profil'), 'Profile', ["data" => $response]);
    }

}
