<?php

namespace App\Controllers;

use App\Database\Query;
use App\Security\Auth;
use App\Services\FlashService;
use App\Views\View;

class SettingsController extends Controller
{

    public function infosView(){
        $this->render(View::new('settings.infos', 'settings'), "Infos");
    }

    public function emailView(){
        $this->render(View::new('settings.email', 'settings'), "Email");
    }

    public function profilpicView(){
        $this->render(View::new('settings.profil_picture', 'settings'), "Image de Profile");
    }

    public function biographyView(){
        $this->render(View::new('settings.biography', 'settings'), "Biographie");
    }

    public function deleteView(){
        $this->render(View::new('settings.delete', 'settings'), "Supprimer");
    }

    public function passwordView(){
        $this->render(View::new('settings.password', 'settings'), "Mot de passe");
    }

    public function identityView(){
        $this->render(View::new('settings.identity', 'settings'), "Identité");
    }
}
