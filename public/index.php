<?php

namespace App;

use App\Api\AuthApiController;
use App\Api\TeamsApiController;
use App\Api\UserApiController;
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

Route::get('/auth/register', [AuthController::class, 'registerView'], false, 'register');
Route::post('/auth/register', [AuthController::class, 'register']);

Route::get('/auth/verify', [AuthController::class, 'verify'], false, 'verify_account');

Route::get('/auth/password-forgot/reset', [AuthController::class, 'reset_password_view'], false, 'reset-password');
Route::post('/auth/password-forgot/reset', [AuthController::class, 'reset_password'], false);

Route::get('/auth/password-forgot', [AuthController::class, 'password_resetView'], false, 'password-forgot');
Route::post('/auth/password-forgot', [AuthController::class, 'password_reset'], false);

Route::get('/auth/2fa', [AuthController::class, 'twofaView'], false, '2fa');
Route::post('/auth/2fa', [AuthController::class, 'twofa'], false);

Route::get('/auth/logout', function () {
    Auth::logout();
}, true, 'logout');

Route::get('/profile', [UserController::class, 'profileView'], true, 'profile');

Route::get('/settings', [UserController::class, 'settingsView'], true);
Route::post('/settings', [UserController::class, 'settings'], true);

Route::get('/settings/2fa', [UserController::class, 'settings2fa'], true, 'settings2fa');
Route::post('/settings/2fa', [UserController::class, 'settings2faActivate'], true);

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
Route::post('/bookmark/', [BookmarkController::class, 'createBookmark'], true);
Route::put('/bookmark/{id}', [BookmarkController::class, 'editBookmark'], true);
Route::delete('/bookmark/{id}', [BookmarkController::class, 'deleteBookmark'], true);

Route::get('/teams/{id}', [TeamsController::class, 'folderView'], true)->where('id', '\w{10}');
Route::get('/teams/{id}/settings', [TeamsController::class, 'teamSettings'], true)->where('id', '\w{10}');
Route::get('/teams/', [TeamsController::class, 'getTeams'], true);
Route::post('/teams/', [TeamsController::class, 'createTeams'], true);

Route::post('/auth/login', [AuthApiController::class, 'login'])->api();
Route::get('/user/teams', [UserApiController::class, 'getTeams'])->api();
Route::get('/user/', [UserApiController::class, 'getMe'])->api();
Route::get('/teams/{id}', [TeamsApiController::class, 'getTeam'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/members', [TeamsApiController::class, 'getTeamMembers'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/settings', [TeamsApiController::class, 'getTeamSettings'])->where('id', '\w{10}')->api();

Route::redirect('/', '/dashboard', true);
Router::init();

