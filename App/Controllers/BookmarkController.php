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

                    if(filter_var($_POST["link-modal"], FILTER_FLAG_HOST_REQUIRED)){
                        throw new InvalidArgumentException("Veuillez saisir un liens");
                    }

                    if(filter_var($_POST["thumbnail-modal"], FILTER_FLAG_PATH_REQUIRED)){
                        throw new InvalidArgumentException("Veuillez saisir un liens comme image");
                    }



                    $request_values = [
                        'title' => $_POST["bookmarks-modal"],
                        'link' => $_POST["link-modal"],
                        'thumbnail' => $_POST["thumbnail-modal"],
                        'difficulty' => $_POST["difficulty-modal"],
                    ];

                    $this->Bookmarks->edit($_POST["id_bookmarks_modal"], $request_values);
                    FlashService::success("Vous avez modifiez avec sucess cette bookmarks", 2);
                    break;
                case "delete":
                    if($this->Bookmarks->isPin($_POST["id_bookmarks"]) != null){
                        FlashService::error("Vous ne pouvez pas supprime un bookmarks mis en favoris", 2);
                    }else{
                        $this->Bookmarks->delete($_POST["id_bookmarks"]);
                        FlashService::success("Vous avez bien supprime cette bookmarks", 2);
                    }
                    break;
                case "pin":
                    if($this->Bookmarks->isPin($_POST["id_bookmarks"]) != null){
                        $this->Bookmarks->removePin($_POST["id_bookmarks"]);
                        FlashService::success("Vous avez bien enleve cette bookmarks de vos favoris", 2);
                    }else{
                        $response = $this->Bookmarks->pin($_POST["id_bookmarks"]);
                        FlashService::success("Vous avez bien ajouté cette bookmarks en favoris", 2);
                    }
                    break;
                case "add":

                    if(filter_var($_POST["link-modal"], FILTER_FLAG_HOST_REQUIRED)){
                        throw new  UnknownFieldException("Veuillez saisir un liens");
                    }

                    if(filter_var($_POST["thumbnail-modal"], FILTER_FLAG_PATH_REQUIRED)){
                        throw new  UnknownFieldException("Veuillez saisir un liens comme image");
                    }

                    $value = ["EASY", "MEDIUM", "DIFFICILE", "PRO"];

                    if(!in_array($_POST["difficulty-modal"], $value)){
                        throw new  UnknownFieldException("Veuillez saisir une difficultés valide");
                    }

                    $response = $this->Bookmarks->add($_POST["bookmarks-modal"], $_POST["link-modal"], $_POST["thumbnail-modal"], $_POST["difficulty-modal"]);
                    FlashService::success("Vous avez bien ajouté une bookmarks",2);
                    break;
            }
        }catch (\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $data = $this->Bookmarks->getAllForMe();
        $this->render(View::new('dashboard'), 'Accueil', ['data' => $data ?? []]);
    }
}
