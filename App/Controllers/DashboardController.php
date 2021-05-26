<?php

namespace App\Controllers;

use App\Services\FlashService;
use App\Views\View;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->loadModel('Bookmarks');
        $this->loadModel('Teams');
    }

    public function dashboard()
    {
        try {
            $data = $this->Bookmarks->getAllForMe();
            $equipes = $this->Teams->getAllForMe();
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('dashboard', 'dashboard'), 'Accueil', ['data' => $data ?? [], 'equipes' => $equipes ?? []]);
    }

    public function favorite()
    {
        try {
            $equipes = $this->Teams->getAllForMe();
            $data = $this->Bookmarks->getFavorite();
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('favorite', 'dashboard'), 'Favoris', ['equipes' => $equipes, 'data' => $data]);
    }
}
