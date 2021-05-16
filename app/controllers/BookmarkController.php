<?php

namespace App\Controllers;

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

    public function update()
    {
        if(array_key_exists("edit-modal", $_POST)){
            try{

                $request_values = [
                    'title' => $_POST["bookmarks-modal"],
                    'link' => $_POST["link-modal"],
                    'thumbnail' => $_POST["thumbnail-modal"],
                    'difficulty' => $_POST["difficulty-modal"],
                ];

                $this->Bookmarks->edit($_POST["id_bookmarks_modal"], $request_values);
                FlashService::success("Vous avez modifiez avec sucess cette bookmarks");
            }catch (\Exception $e){
                FlashService::error($e->getMessage());
                http_response_code($e->getCode());
            }
            $data = $this->Bookmarks->getAllForMe();
            $this->render(View::new('dashboard'), 'Accueil', ['data' => $data ?? []]);
        }elseif(array_key_exists("delete", $_POST)){
            try{
                $this->Bookmarks->delete($_POST["id_bookmarks"]);
                FlashService::success("Vous avez bien supprime cette bookmarks");
            }catch(\Exception $e){
                FlashService::error($e->getMessage());
                http_response_code($e->getCode());
            }
            $data = $this->Bookmarks->getAllForMe();
            $this->render(View::new('dashboard'), 'Accueil', ['data' => $data ?? []]);
        }elseif(array_key_exists("pin", $_POST)){
            try{
                $response = $this->Bookmarks->pin($_POST["id_bookmarks"]);
                FlashService::success("Vous avez bien ajouté cette bookmarks en favoris");
            }catch(\Exception $e){
                FlashService::error($e->getMessage());
                http_response_code($e->getCode());
            }
            $data = $this->Bookmarks->getAllForMe();
            $this->render(View::new('dashboard'), 'Accueil', ['data' => $data ?? []]);
        }elseif(array_key_exists("add-modal", $_POST)){
            try{
                $response = $this->Bookmarks->add($_POST["bookmarks-modal"], $_POST["link-modal"], $_POST["thumbnail-modal"], $_POST["difficulty-modal"]);
                FlashService::success("Vous avez bien ajouté une bookmarks");
            }catch(\Exception $e){
                FlashService::error($e->getMessage());
                http_response_code($e->getCode());
            }
            $data = $this->Bookmarks->getAllForMe();
            $this->render(View::new('dashboard'), 'Accueil', ['data' => $data ?? []]);
        }
    }
}
