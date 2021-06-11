<?php

namespace App\Controllers;

use App\Exceptions\UnknownFieldException;
use App\Services\FlashService;
use App\Views\View;

class BookmarkController extends Controller
{

    /**
     * BookmarkController constructor.
     */
    public function __construct()
    {
        $this->loadModel('Bookmarks');
        $this->loadModel('Teams');
        $this->loadModel('Folders');
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        $this->checkPost('action', 'Erreur lors de la requête !');

        $action = htmlspecialchars($_POST['action']);

        try{
            switch ($action){
                case "edit":
                    if(filter_var($_POST["link"], FILTER_FLAG_HOST_REQUIRED)){
                        throw new InvalidArgumentException("Veuillez saisir un liens");
                    }

                    if(filter_var($_POST["thumbnail"], FILTER_FLAG_PATH_REQUIRED)){
                        throw new InvalidArgumentException("Veuillez saisir un liens comme image");
                    }

                    $request_values = [
                        'title' => $_POST["title"],
                        'link' => $_POST["link"],
                        'thumbnail' => $_POST["thumbnail"],
                        'difficulty' => $_POST["difficulty"],
                    ];

                    $this->Bookmarks->edit($_POST["id_bookmarks"], $request_values);
                    FlashService::success("Vous avez modifiez avec sucess cette bookmarks", 2);
                    break;
            }
        }catch (\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $data = $this->Bookmarks->getAllForMe();
        $equipes = $this->Teams->getAllForMe();
        $folders = $this->Folders->getAllForMe();
        $this->render(View::new('dashboard', 'dashboard'), 'Accueil', ['data' => $data ?? [], 'equipes' => $equipes ?? [], 'folders' => $folders ?? []]);
    }
}
