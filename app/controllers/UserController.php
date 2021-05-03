<?php

namespace App\Controllers;

use App\Database\Query;
use App\Security\Auth;
use App\Security\AuthException;
use App\Security\Profil;
use App\Services\FlashService;
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

    public function settingsView()
    {
        $query = (new Query())
            ->select("first_name", "last_name", "email", "username", "avatar", "bio", "created_at", "verify")
            ->from("users")
            ->limit(1)
            ->where("id = ?")
            ->params([Auth::user()->id]);

        $response = $query->first();

        $this->render(View::new('settings'), "Paramètres", ["data" => $response]);
    }

    public function settings()
    {
        try {
            $fields = ["username", "email", "avatar", "bio", "password"];
            $values = [];

            $query = (new Query())
                ->select("id", "email", "username", "bio", "password")
                ->from("users")
                ->where("email = ? or username = ?")
                ->limit(1)
                ->params([$_POST["email"], $_POST["username"]]);

            $response = $query->first();

            if(password_verify($_ENV['SALT'] . $_POST["password"], $response->password)){
                throw new AuthException('Le mot de passe ne correspond pas');
            }

            if(isset($_POST["password"]) && isset($_POST["confirm"]) || !isset($_POST["confirm"])){
                if($_POST["password"] != $_POST["confirm"]){
                    throw new AuthException('Les mot de passe ne sont pas identique');
                }
            }

            foreach ($_POST as $key => $value) {
                if(in_array($key, $fields)){
                    array_push($values[$key], $value);
                }
            }

            $query = (new Query())
                ->update()
                ->into("users")
                ->set($values)
                ->where("id = ?")
                ->params([Auth::user()->id]);

            $query->execute();

        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code(400);
        }
        $this->render(View::new('settings'), "Paramètres");
    }
}
