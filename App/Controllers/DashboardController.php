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
        $this->loadModel('Folders');
    }

    public function dashboard()
    {
        try {
            $data = $this->Bookmarks->getAllForMe();
            $equipes = $this->Teams->getAllForMe();
            $folders = $this->Folders->getAllForMe();
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('dashboard', 'dashboard'), 'Accueil', ['data' => $data ?? [], 'equipes' => $equipes ?? [], 'folders' => $folders ?? []]);
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

    public function recherche()
    {
        try {
            $equipes = $this->Teams->getAllForMe();
            $folders = $this->Folders->getAllForMe();
            $result = $this->Bookmarks->search(htmlspecialchars($_GET['q'] ?? ''));
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('search', 'dashboard'), 'Accueil', ['equipes' => $equipes ?? [], 'result' => $result ?? [], 'folders' => $folders ?? []]);
    }
}
