<?php

namespace App\Controllers;

use App\Database\Query;
use App\Database\QueryApi;
use App\Exceptions\UserNotFoundException;
use App\Security\Auth;
use App\Security\AuthException;
use App\Services\FlashService;
use App\Views\View;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->loadModel('User', 'Users');
    }

    public function profileView()
    {
        $data = $this->User->getById(Auth::user()->id);
        $this->render(View::new('profil'), 'Profile', ["data" => $data]);
    }

    public function settings2fa()
    {
        $data = $this->User->getById(Auth::user()->id);

        if (is_null($data->totp)) {
            $g = new GoogleAuthenticator();
            $secret = $g->generateSecret();
        }
        $this->render(View::new('user.2fa', 'security'), '2FA', ["data" => $data, "secret" => $secret ?? '']);
    }

    public function settingsView()
    {
        $response = $this->User->getById(Auth::user()->id);

        $this->render(View::new('settings', 'security'), "Paramètres", ["data" => $response]);
    }

    public function settings2faActivate()
    {
        $g = new GoogleAuthenticator();
        try {
            $this->checkCsrf();
            $this->checkPost('action', 'Erreur lors de la requête !');

            $action = $_POST['action'];

            if ($action == 'reset') {
                $this->User->resetTotp();
            } else {
                $this->checkPost('code', 'Veuilliez entrer votre code !', '\d{6}');
                $this->checkPost('secret', 'Erreur interne !', '[A-Z0-9]{16}');

                $data = $this->User->getById(Auth::user()->id);

                if (!is_null($data->totp)) {
                    throw new AuthException('2FA déjà activé !');
                }
                $secret = $_POST['secret'];

                if ($g->checkCode($secret, $_POST['code'])) {
                    $this->User->changeTotp($secret);
                    FlashService::success('2FA activé !');
                } else {
                    throw new AuthException('Mauvais code !');
                }
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode() == 0 ? 400 : $e->getCode());
        }
        $data = $this->User->getById(Auth::user()->id);
        if (isset($_POST['secret'])) {
            $secret = $_POST['secret'];
        } elseif (is_null($data->totp)) {
            $secret = $g->generateSecret();
        }
        $this->render(View::new('user.2fa', 'security'), '2FA', ["data" => $data, 'secret' => $secret ?? '']);
    }

    public function settings()
    {
        try{
            $this->checkCsrf();
            $this->checkPost("current", "Merci de préciser votre mot de passe !");

            $response = $this->User->getById(Auth::user()->id);

            if(!password_verify($_ENV['SALT'] . $_POST["current"], $response->password)){
                throw new AuthException('Le mot de passe ne correspond pas');
            }

            if(isset($_POST["password"]) && isset($_POST["confirm"]) || isset($_POST["confirm"])){
                if($_POST["password"] != $_POST["confirm"]){
                    throw new AuthException('Les mot de passe ne sont pas identique');
                }
            }

            $request_values =  $this->getRequestValue([], [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm' => '',
                'current' => '',
                'avatar' => '',
                'bio' => '',
            ]);

            unset($request_values["confirm"]);
            unset($request_values["current"]);

            $response = $this->User->editSettings($request_values);

            $this->render(View::new('settings', 'settings'), "Paramètres",  ["data" => $response]);
        }catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode() == 0 ? 400 : $e->getCode());
            $response = $this->User->getById(Auth::user()->id);
            $this->render(View::new('settings', 'settings'), "Paramètres",  ["data" => $response]);
        }

    }
}
