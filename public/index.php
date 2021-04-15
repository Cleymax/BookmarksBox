<?php
use App\Router\Route;
define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/../vendor/autoload.php';
require_once ROOT_PATH . '/../app/Bootsrap.php';

Route::get('/user/{id}/profiles', function (int $id) {
    return "Salut $id !";
});
Route::get('/user/{id}/settings', [UserController::class, 'settings']);
Route::init();
