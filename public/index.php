<?php

namespace App;

use App\Controllers\AuthController;
use App\Controllers\BookmarkController;
use App\Controllers\DashboardController;
use App\Controllers\FolderController;
use App\Controllers\TeamsController;
use App\Controllers\UserController;
use App\Router\Route;
use App\Router\Router;
use App\Security\Auth;

define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/../vendor/autoload.php';
require_once ROOT_PATH . '/../app/Bootstrap.php';

Route::get('/auth/login', [AuthController::class, 'loginView'], false, 'login');
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/auth/register', [AuthController::class, 'registerView'],false, 'register');
Route::post('/auth/register', [AuthController::class, 'register']);

Route::get('/auth/2fa', [AuthController::class, 'twofaView'], false, '2fa');
Route::post('/auth/2fa', [AuthController::class, 'twofa'], false);

Route::get('/auth/logout', function () {
    Auth::logout();
}, true, 'logout');

Route::get('/profile', [UserController::class, 'profileView'], true);
Route::post('/profile', [UserController::class, 'profile'], true);

Route::get('/settings', [UserController::class, 'settingsView'], true);
Route::post('/settings', [UserController::class, 'settings'], true);

Route::get('/dashboard', [DashboardController::class, 'dashboard'], true, 'dashboard');
Route::get('/favorite', [DashboardController::class, 'favorite'], true);

Route::get('/folder/{id}', [FolderController::class, 'folderView'], true);
Route::post('/folder/{id}', [FolderController::class, 'createFolder'], true);
Route::put('/folder/{id}', [FolderController::class, 'editFolder'], true);
Route::delete('/folder/{id}', [FolderController::class, 'deleteFolder'], true);
Route::get('/folder/{id}/bookmarks', [FolderController::class, 'getFolderBookmark'], false);
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

