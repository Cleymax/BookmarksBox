<?php

namespace App\Controllers;

use App\Security\Auth;
use App\Security\AuthException;
use App\Services\FlashService;
use App\Views\View;
use function App\debug;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->loadModel('User', 'Users');
    }

    public function accountView(){
        $this->render(View::new('settings.infos', 'settings'), "Infos");
    }

    public function securityView(){
        $this->render(View::new('settings.security', 'security'), "Infos");
    }

    public function infosView(){

        $data = $this->User->getById(Auth::user()->id);

        $this->render(View::new('settings.infos', 'settings'), "Infos", ["data" => $data]);
    }

    public function emailView(){
        $this->render(View::new('settings.email', 'settings'), "Email");
    }

    public function email(){
        try{
            $this->checkCsrf();
            $this->checkPost("current", "Merci de préciser votre mot de passe !");

            $response = $this->User->getById(Auth::user()->id);

            if(!password_verify($_ENV['SALT'] . $_POST["current"], $response->password)){
                throw new AuthException('Le mot de passe ne correspond pas');
            }

            $request_values =  $this->getRequestValue([], [
                'email' => '',
                'current' => '',
            ]);

            unset($request_values["current"]);

            $response = $this->User->editSettings($request_values);

            FlashService::success('Votre email à était modifier avec sucèss', 2);
            $this->render(View::new('settings.email', 'settings'), "Email",  ["data" => $response]);
        }catch (\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode() == 0 ? 400 : $e->getCode());
            $response = $this->User->getById(Auth::user()->id);
            $this->render(View::new('settings.email', 'settings'), "Email",  ["data" => $response]);
        }
    }

    public function profilpicView(){
        $this->render(View::new('settings.profil_picture', 'settings'), "Image de Profile");
    }

    public function biographyView(){
        $this->render(View::new('settings.biography', 'settings'), "Biographie");
    }

    public function biography(){
        try{
            $this->checkCsrf();

            $request_values =  $this->getRequestValue([], [
                'bio' => '',
            ]);

            $response = $this->User->editSettings($request_values);

            FlashService::success('Votre biographie à était modifier avec sucèss', 2);
            $this->render(View::new('settings.biography', 'settings'), "Biographie",  ["data" => $response]);
        }catch (\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode() == 0 ? 400 : $e->getCode());
            $response = $this->User->getById(Auth::user()->id);
            $this->render(View::new('settings.biography', 'settings'), "Biographie",  ["data" => $response]);
        }
    }

    public function deleteView(){
        $this->render(View::new('settings.delete', 'settings'), "Supprimer");
    }

    public function passwordView(){
        $this->render(View::new('settings.password', 'settings'), "Mot de passe");
    }

    public function password(){
        try{
            $this->checkCsrf();
            $this->checkPost("current", "Merci de préciser votre mot de passe !");

            $response = $this->User->getById(Auth::user()->id);

            if(!password_verify($_ENV['SALT'] . $_POST["current"], $response->password)){
                throw new AuthException('Le mot de passe ne correspond pas');
            }

            if(isset($_POST["password"]) && isset($_POST["confirm"]) || !isset($_POST["confirm"])){
                if($_POST["password"] != $_POST["confirm"]){
                    throw new AuthException('Les mot de passe ne sont pas identique');
                }
            }

            $request_values =  $this->getRequestValue([], [
                'password' => '',
                'confirm' => '',
                'current' => '',
            ]);

            unset($request_values["confirm"]);
            unset($request_values["current"]);

            $response = $this->User->editSettings($request_values);

            FlashService::success('Votre mot de passe à était modifier avec sucèss', 2);
            $this->render(View::new('settings.password', 'settings'), "Mot de passe",  ["data" => $response]);
        }catch (\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode() == 0 ? 400 : $e->getCode());
            $response = $this->User->getById(Auth::user()->id);
            $this->render(View::new('settings.password', 'settings'), "Mot de passe",  ["data" => $response]);
        }
    }

    public function identityView(){
        $this->render(View::new('settings.identity', 'settings'), "Identité");
    }

    public function identity(){
        try{
            $this->checkCsrf();
            $this->checkPost("current", "Merci de préciser votre mot de passe !");

            $response = $this->User->getById(Auth::user()->id);

            if(!password_verify($_ENV['SALT'] . $_POST["current"], $response->password)){
                throw new AuthException('Le mot de passe ne correspond pas');
            }

            // Check si username n'existe pas déjà

            $request_values =  $this->getRequestValue([], [
                'last_name' => '',
                'first_name' => '',
                'username' => '',
                'current' => '',
            ]);

            unset($request_values["current"]);

            $response = $this->User->editSettings($request_values);

            FlashService::success('Vos données ont était mise à jour avec succès', 2);
            $this->render(View::new('settings.identity', 'settings'), "Identité",  ["data" => $response]);
        }catch (\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode() == 0 ? 400 : $e->getCode());
            $response = $this->User->getById(Auth::user()->id);
            $this->render(View::new('settings.identity', 'settings'), "Identité",  ["data" => $response]);
        }
    }
}
