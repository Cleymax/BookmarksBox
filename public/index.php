<?php

namespace App;

use App\Api\AuthApiController;
use App\Api\BookmarkApiController;
use App\Api\TeamsApiController;
use App\Api\UserApiController;
use App\Controllers\AuthController;
use App\Controllers\BookmarkController;
use App\Controllers\DashboardController;
use App\Controllers\FolderController;
use App\Controllers\SettingsController;
use App\Controllers\TeamsController;
use App\Controllers\UserController;
use App\Router\Route;
use App\Router\Router;
use App\Security\Auth;
use App\Views\View;

define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/../vendor/autoload.php';
require_once ROOT_PATH . '/../App/Bootstrap.php';

Route::get('/auth/login', [AuthController::class, 'loginView'], false, 'login');
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/auth/cas/', [AuthController::class, 'cas'], false, 'cas');

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

Route::get('/settings/account', [SettingsController::class, 'accountView'], true, 'account');
Route::get('/settings/security', [SettingsController::class, 'securityView'], true, 'security');

Route::get('/settings/security/2fa', [UserController::class, 'settings2fa'], true, 'settings2fa');
Route::post('/settings/security/2fa', [UserController::class, 'settings2faActivate'], true);

Route::get('/settings/account/infos', [SettingsController::class, 'infosView'], true, 'infos');
Route::post('/settings/account/infos', [SettingsController::class, 'infos'], true);

Route::get('/settings/account/password', [SettingsController::class, 'passwordView'], true, 'password');
Route::post('/settings/account/password', [SettingsController::class, 'password'], true);

Route::get('/settings/account/email', [SettingsController::class, 'emailView'], true, 'email');
Route::post('/settings/account/email', [SettingsController::class, 'email'], true);

Route::get('/settings/account/identity', [SettingsController::class, 'identityView'], true, 'identity');
Route::post('/settings/account/identity', [SettingsController::class, 'identity'], true);

Route::get('/settings/account/profilpic', [SettingsController::class, 'profilpicView'], true, 'profil_picture');
Route::post('/settings/account/profilpic', [SettingsController::class, 'profilpic'], true);

Route::get('/settings/account/biography', [SettingsController::class, 'biographyView'], true, 'biography');
Route::post('/settings/account/biography', [SettingsController::class, 'biography'], true);

Route::get('/settings/account/delete', [SettingsController::class, 'deleteView'], true, 'delete');
Route::post('/settings/account/delete', [SettingsController::class, 'delete'], true);



Route::get('/dashboard', [DashboardController::class, 'dashboard'], true, 'dashboard');
Route::post('/dashboard', [BookmarkController::class, 'update'], true);
Route::get('/favorite', [DashboardController::class, 'favorite'], true);
Route::delete('/favorite/{id}', [BookmarkApiController::class, 'removeFavorite'], true)->where('id', '\w{10}')->api();

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
Route::get('/teams/{id}/manager', [TeamsController::class, 'teamManageView'], true)->where('id', '\w{10}');
Route::post('/teams/{id}/manager', [TeamsController::class, 'teamManage'], true)->where('id', '\w{10}');
Route::get('/teams/{id}/leave', [TeamsController::class, 'leaveViewTeam'], true)->where('id', '\w{10}');
Route::post('/teams/{id}/leave', [TeamsController::class, 'leaveView'], true)->where('id', '\w{10}');
Route::get('/teams/', [TeamsController::class, 'getTeams'], true, 'teams');
Route::post('/teams/', [TeamsController::class, 'createTeams'], true);
Route::get('/teams/invite/{code}', [TeamsController::class, 'inviteCode'], true);

Route::post('/auth/login', [AuthApiController::class, 'login'])->api();
Route::get('/user/teams', [UserApiController::class, 'getTeams'])->api();
Route::put('/user/teams/{id}/favorite',[UserApiController::class, 'changeTeamFavorite'])->where('id', '\w{10}')->api();
Route::get('/user/', [UserApiController::class, 'getMe'])->api();
Route::get('/users/', [UserApiController::class, 'getUser'])->api();
Route::get('/teams/{id}', [TeamsApiController::class, 'getTeam'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/members', [TeamsApiController::class, 'getTeamMembers'])->where('id', '\w{10}')->api();
Route::delete('/teams/{id}/members/{member}', [TeamsApiController::class, 'deleteMember'])->where('id', '\w{10}')->api();
Route::put('/teams/{id}/members/{member}', [TeamsApiController::class, 'addMemberWithId'])->where('id', '\w{10}')->api();
Route::post('/teams/{id}/members/{member}/role', [TeamsApiController::class, 'changeRoleMember'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/settings', [TeamsApiController::class, 'getTeamSettings'])->where('id', '\w{10}')->api();


//Route::any('/404', function () {
//    return View::new('404');
//}, false);
Route::redirect('/', '/dashboard', true);
Router::init();
