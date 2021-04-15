<?php

use App\Router\Route;

define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/../vendor/autoload.php';
require_once ROOT_PATH . '/../app/Bootsrap.php';

Route::get('/user/', function () {
    return "Salut !";
});
Route::get('/user/{id}/settings/{key}/{value}', function ($id, $key, $value) {
    return "Salut $id ! $key ==> $value";
})->whereArray([
    "id" => "[0-9]+",
    "key" => "\d+"
]);
Route::get('/user/{id}/settings', [UserController::class, 'settings']);

Route::debug();
Route::init();

