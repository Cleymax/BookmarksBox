<?php

namespace App;

use App\Api\AuthApiController;
use App\Api\BookmarkApiController;
use App\Api\FolderApiController;
use App\Api\ScrapingApiController;
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
Route::post('/settings/account/delete', [UserApiController::class, 'delete'], true);

Route::get('/dashboard', [DashboardController::class, 'dashboard'], true, 'dashboard');
Route::get('/recherche', [DashboardController::class, 'recherche'], true, 'recherche');
Route::post('/dashboard', [BookmarkController::class, 'update'], true);
Route::get('/favorite', [DashboardController::class, 'favorite'], true);
Route::delete('/favorite/{id}', [BookmarkApiController::class, 'removeFavorite'], true)->where('id', '\w{10}')->api();

Route::get('/folder/{id}', [FolderController::class, 'folderView'], true, 'folder')->where('id', '\w{10}');
Route::get('/folders',  [FolderApiController::class, 'getAllFolders'], true)->api();
Route::post('/folder/create/{name}/color/{color}', [FolderApiController::class, 'createFolder'], true)->api();
Route::get('/folder/{id}/delete', [FolderApiController::class, 'deleteFolder'], true)->where('id', '\w{10}')->api();
Route::get('/folder/create/{name}/color/{color}', [FolderApiController::class, 'createFolder'], true)->api();
Route::get('/folder/{id}',  [FolderApiController::class, 'getFolder'], true)->where('id', '\w{10}')->api();
Route::get('/isFolder/{id}', [FolderApiController::class, 'isFolder'], true)->where('id', '\w{10}')->api();
Route::get('/folder/{id}/bookmarks', [FolderApiController::class, 'getFolderBookmark'], false)->api();
Route::post('/folder', [FolderApiController::class, 'createFolder'], false)->api();
Route::get('/folder/{id}/move/{idFolders}', [FolderApiController::class, 'moveFolder'], true)->where('id', '\w{10}')->api();
Route::post('/folder/edit/{id}', [FolderApiController::class, 'editFolder'], true)->where('id', '\w{10}')->api();

Route::get('/bookmarks', [BookmarkApiController::class, 'getAllBookmarks'], true)->api();
Route::get('/bookmark/{id}', [BookmarkApiController::class, 'getBookmark'], true)->where('id', '\w{10}')->api();
Route::get('/bookmark/{id}/move/{idFolders}', [BookmarkApiController::class, 'moveBookmark'], true)->where('id', '\w{10}')->api();
Route::get('/bookmark/{id}/favorite/add', [BookmarkApiController::class, 'addFavorite'], true)->where('id', '\w{10}')->api();
Route::get('/bookmark/{id}/favorite/remove', [BookmarkApiController::class, 'removeFavorite'], true)->where('id', '\w{10}')->api();
Route::get('/bookmark/{id}/favorite/isFavorite', [BookmarkApiController::class, 'isFavorite'], true)->where('id', '\w{10}')->api();
Route::get('/bookmark/{id}/delete', [BookmarkApiController::class, 'delete'], true)->where('id', '\w{10}')->api();
Route::post('/bookmark', [BookmarkApiController::class, 'createBookmark'], true)->api();
Route::post('/bookmark/edit/{id}', [BookmarkApiController::class, 'editBookmark'], true)->where('id', '\w{10}')->api();


Route::get('/teams/create', [TeamsController::class, 'createView'], true, 'teams_add');
Route::post('/teams/create', [TeamsController::class, 'create'], true);
Route::get('/teams/{id}', [TeamsController::class, 'teamView'])->where('id', '\w{10}');
Route::get('/teams/{id}/folder/{folder}', [TeamsController::class, 'folderView'])->where('folder', '\w{10}')->where('id', '\w{10}');
Route::get('/teams/{id}/manager', [TeamsController::class, 'teamManageView'], true)->where('id', '\w{10}');
Route::post('/teams/{id}/manager', [TeamsController::class, 'teamManage'], true)->where('id', '\w{10}');
Route::get('/teams/{id}/leave', [TeamsController::class, 'leaveViewTeam'], true)->where('id', '\w{10}');
Route::post('/teams/{id}/leave', [TeamsController::class, 'leaveView'], true)->where('id', '\w{10}');
Route::get('/teams/', [TeamsController::class, 'getTeams'], false, 'teams');
Route::post('/teams/', [TeamsController::class, 'createTeams'], true);
Route::get('/teams/invite/{code}', [TeamsController::class, 'inviteCode'], true);

Route::post('/auth/login', [AuthApiController::class, 'login'])->api();
Route::get('/user/teams', [UserApiController::class, 'getTeams'])->api();
Route::put('/user/teams/{id}/favorite', [UserApiController::class, 'changeTeamFavorite'])->where('id', '\w{10}')->api();
Route::get('/user/', [UserApiController::class, 'getMe'])->api();
Route::get('/users/', [UserApiController::class, 'getUser'])->api();
Route::get('/teams/{id}', [TeamsApiController::class, 'getTeam'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/members', [TeamsApiController::class, 'getTeamMembers'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/folders-main', [TeamsApiController::class, 'getTeamFolderMain'])->where('id', '\w{10}')->api();
Route::delete('/teams/{id}/members/{member}', [TeamsApiController::class, 'deleteMember'])->where('id', '\w{10}')->api();
Route::put('/teams/{id}/members/{member}', [TeamsApiController::class, 'addMemberWithId'])->where('id', '\w{10}')->api();
Route::post('/teams/{id}/members/{member}/role', [TeamsApiController::class, 'changeRoleMember'])->where('id', '\w{10}')->api();
Route::get('/teams/{id}/settings', [TeamsApiController::class, 'getTeamSettings'])->where('id', '\w{10}')->api();

Route::get('/folders/{id}', [FolderApiController::class, 'getFolderById'])->where('id', '\w{10}')->api();
Route::get('/folders-main', [FolderApiController::class, 'getFolders'])->where('id', '\w{10}')->api();

Route::get('/scrape', [ScrapingApiController::class, 'scrape'])->api();

Route::redirect('/', '/teams', true);
Router::init();
