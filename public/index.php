<?php

namespace App;

use App\Controller\AuthController;
use App\Controller\BookmarkController;
use App\Controller\DashboardController;
use App\Controller\FolderController;
use App\Controller\TeamsController;
use App\Controller\UserController;
use App\Router\Route;
use App\Router\Router;
use App\Security\Auth;

define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/../vendor/autoload.php';
require_once ROOT_PATH . '/../app/Bootsrap.php';

Route::get('/auth/login', [AuthController::class, 'loginView']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/auth/2fa', [AuthController::class, 'twofaView']);
Route::post('/auth/2fa', [AuthController::class, 'twofa']);

Route::get('/auth/logout', [AuthController::class, 'logoutView'], true);
Route::get('/auth/logout', function () {
    Auth::logout();
}, true);

Route::get('/profile', [UserController::class, 'profileView'], true);
Route::post('/profile', [UserController::class, 'profile'], true);

Route::get('/settings', [UserController::class, 'settingsView'], true);
Route::post('/settings', [UserController::class, 'settings'], true);

Route::get('/dashboard', [DashboardController::class, 'dashboard'], true);
Route::get('/favorite', [DashboardController::class, 'favorite'], true);

Route::get('/folder/{id}', [FolderController::class, 'folderView'], true);
Route::post('/folder/{id}', [FolderController::class, 'createFolder'], true);
Route::put('/folder/{id}', [FolderController::class, 'editFolder'], true);
Route::delete('/folder/{id}', [FolderController::class, 'deleteFolder'], true);
Route::get('/folder/{id}/bookmarks', [FolderController::class, 'getFolderBookmark'], true);
Route::get('/folder/{id}/info', [FolderController::class, 'getFolderInfo'], true);

Route::get('/bookmarks/', [BookmarkController::class, 'getAllBookmark'], true);
Route::get('/bookmark/{id}', [BookmarkController::class, 'getBookmark'], true);
Route::post('/bookmark/{id}', [BookmarkController::class, 'createBookmark'], true);
Route::put('/bookmark/{id}', [BookmarkController::class, 'editBookmark'], true);
Route::delete('/bookmark/{id}', [BookmarkController::class, 'deleteBookmark'], true);

Route::get('/teams/{id}', [TeamsController::class, 'folderView'], true);
Route::get('/teams/{id}/settings', [TeamsController::class, 'teamSettings'], true);
Route::get('/teams/', [FolderController::class, 'folderView'], true);

Route::redirect('/', '/dashboard', true);
Router::init();

