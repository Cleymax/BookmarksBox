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
    }

    public function dashboard()
    {
        try{
            $data = $this->Bookmarks->getAllForMe();
        }catch(\Exception $e){
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('dashboard'), 'Accueil', ['data' => $data ?? []]);
    }
}
