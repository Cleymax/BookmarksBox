<?php

namespace App\Controllers;

use App\Services\FlashService;
use App\Views\View;

class FolderController extends Controller
{

    public function __construct()
    {
        $this->loadModel('Bookmarks');
        $this->loadModel('Teams');
        $this->loadModel('Folders');
    }

    public function folderView(string $id)
    {
        try {
            $equipes = $this->Teams->getAllForMe();
            $data = $this->Bookmarks->getAllForMeInDir($id);
            $folders = $this->Folders->getAllForMeInDir($id);
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('dashboard', 'dashboard'), 'Accueil', ['data' => $data ?? [], 'equipes' => $equipes ?? [], 'folders' => $folders ?? [], 'folder_id' => $id]);
    }

}
