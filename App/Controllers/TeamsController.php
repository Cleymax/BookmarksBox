<?php

namespace App\Controllers;

use App\Database\Query;
use App\Exceptions\UserNotFoundException;
use App\Helper\TeamHelper;
use App\Helper\UserHelper;
use App\Security\Auth;
use App\Security\AuthException;
use App\Services\FileUploader;
use App\Services\FlashService;
use App\Services\IdenticonService;
use App\Views\View;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class TeamsController extends Controller
{
    /**
     * TeamsController constructor.
     */
    public function __construct()
    {
        $this->loadModel('Bookmarks');
        $this->loadModel('Teams');
        $this->loadModel('Folders');
    }

    /**
     * @throws \Exception
     */
    public function createTeams()
    {
        $request_values = $this->getRequestValue(['name'], [
            'invite_code' => '',
            'name' => '',
            'icon' => '',
            'public' => true,
        ]);

        $id = $this->Teams->create($request_values);

        if ($this->need_json()) {
            $this->respond_json([
                'status' => 'ok',
                'id' => $id,
                'link' => $_ENV['BASE_URL'] . '/teams/' . $id
            ]);
        } else {
            $this->redirect('teams/' . $id . '/');
        }
    }

    public function teamView(string $id)
    {
        try {
            $equipe = $this->Teams->getById($id);
            $data = $this->Bookmarks->getAllForTeam($id);
            $equipes = $this->Teams->getAllForMe();
            $folders = $this->Folders->getAllForTeam($id);
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('teams.dashboard', 'dashboard'), 'Equipe ' . $equipe->name, ['data' => $data ?? [], 'id' => $id, 'equipes' => $equipes ?? [], 'equipe' => $equipe, 'folders' => $folders ?? []]);
    }

    public function folderView(string $team_id, string $folder_id)
    {
        try {
            $equipe = $this->Teams->getById($team_id);
            $data = $this->Bookmarks->getAllForTeamInDir($team_id, $folder_id);
            $equipes = $this->Teams->getAllForMe();
            $folders = $this->Folders->getAllForTeamInDir($team_id, $folder_id);
        } catch (\Exception $e) {
            FlashService::error($e->getMessage());
            http_response_code($e->getCode());
        }
        $this->render(View::new('teams.dashboard', 'dashboard'), 'Equipe ' . $equipe->name, ['data' => $data ?? [], 'id' => $team_id, 'equipes' => $equipes ?? [], 'equipe' => $equipe, 'folders' => $folders ?? []]);
    }

    public function getTeams()
    {
        $data = $this->Teams->getAllForMe();
        $public = $this->Teams->getPublic();
        if ($this->need_json()) {
            $this->respond_json($data);
        } else {
            $this->render(View::new('teams.all', 'dashboard'), 'Mes équipes ', ['equipes' => $data, 'equipes_public' => $public ?? []]);
        }
    }

    public function teamManageView(string $id)
    {
        if (!TeamHelper::canManage($id)) {
            FlashService::error("Tu n'as pas la permissionde faire cela !");
            $this->redirect('teams');
        }
        try {
            $equipes = $this->Teams->getAllForMe();
            $data = $this->Teams->getById($id);
            $members = $this->Teams->getMember($id);
        } catch (\Exception $e) {
            $members = [];
            FlashService::error($e->getMessage(), 4);
        }

        $this->render(View::new('teams.manager', 'dashboard'), 'Equipe ' . $data->name, ['equipes' => $equipes ?? [], 'data' => $data, 'id' => $id, 'members' => $members]);
    }

    public function teamManage(string $id)
    {
        if (!TeamHelper::canManage($id)) {
            FlashService::error("Tu n'as pas la permissionde faire cela !");
            $this->redirect('teams');
        }
        try {
            $equipes = $this->Teams->getAllForMe();
            $this->checkCsrf();
            if (isset($_POST['action'])) {
                $this->Teams->regenerateInviteCode($id);
            } elseif (isset($_POST['delete'])) {
                $this->Teams->deleteInviteCode($id);
            } else if (isset($_POST['delete-teams'])) {
                if (!TeamHelper::isOwner($id)) {
                    FlashService::error("Tu n'as pas la permissionde faire cela !");
                } else {
                    $this->checkPost('name', 'Merci de préciser le nom de l\'équipe !');
                    $has_2fa = UserHelper::has2fa();
                    if ($has_2fa && !isset($_POST['code2fa'])) {
                        FlashService::error("Double authentification activé ! Merci de préciser votre code.");
                    }

                    if ($this->Teams->getById($id, 'name')->name !== htmlspecialchars($_POST['name'])) {
                        throw new \InvalidArgumentException("Nom d'équipe éronné !");
                    }
                    if ($has_2fa) {
                        $query = (new Query())
                            ->select("id", "totp")
                            ->from("users")
                            ->where("id = ?")
                            ->params([Auth::user()->id]);

                        if ($query->rowCount() == 0) {
                            throw new UserNotFoundException();
                        }

                        $response = $query->first();

                        $g = new GoogleAuthenticator();

                        if (!$g->checkCode($response->totp, htmlspecialchars($_POST['code2fa']))) {
                            throw  new AuthException("Code éronné !");
                        }
                    }
                    $this->Teams->delete($id);
                    FlashService::success("Equipe supprimé !");
                    $this->redirect('teams');
                }
            } else {
                $new_path = FileUploader::getFileUpload('file');
                if (!empty($new_path)) {
                    $_POST['icon'] = $new_path['name'];
                }
                $request_values = $this->getRequestValue(["visibility" => false, 'description' => '', 'name' => ''], ['file' => '', 'icon' => '']);
                $this->Teams->editSettings($id, $request_values);
            }

            $members = $this->Teams->getMember($id);
        } catch (\Exception $e) {
            $members = [];
            FlashService::error($e->getMessage(), 4);
        }
        $data = $this->Teams->getById($id);
        $this->render(View::new('teams.manager', 'dashboard'), 'Equipe ' . $data->name, ['equipes' => $equipes, 'data' => $data, 'id' => $id, 'members' => $members]);
    }

    public function inviteCode($code)
    {
        try {
            $data = $this->Teams->getAllForMe();
            $response = $this->Teams->getTeamByCode($code);
            if ($response) {
                $this->Teams->join($response->id);
                FlashService::success("Bienvenue dans l'équipe " . $response->name);
            } else {
                FlashService::error("Code invalide !", 2);
                $this->redirect('teams');
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage(), 4);
        }
        $this->render(View::new('teams.invite', 'dashboard'), 'Rejoindre une équipe', ['equipes' => $data, 'equipe' => $response ?? []]);
    }

    public function leaveViewTeam(string $id)
    {
        try {
            $data = $this->Teams->getAllForMe();
        } catch (\Exception $e) {
            FlashService::error($e->getMessage(), 4);
        }
        $this->render(View::new('teams.leave', 'dashboard'), 'Quitter une équipe', ['equipes' => $data]);
    }

    public function leaveView(string $id)
    {
        try {
            $this->Teams->leaveTeam($id);
            FlashService::success('Vous venez de quitter une équipe avec succès !');
        } catch (\Exception $e) {
            FlashService::error($e->getMessage(), 4);
        }
        $this->redirect('teams');
    }

    public function createView()
    {
        $equipes = $this->Teams->getAllForMe();
        $this->render(View::new('teams.create', 'dashboard'), 'Créér une équipe', ['equipes' => $equipes]);
    }

    public function create()
    {
        try {
            $this->checkPost('name', "Nom de l'équipe réquis !");
            $new_path = FileUploader::getFileUpload('file');
            if (!empty($new_path)) {
                $_POST['icon'] = $new_path['name'];
            } else {
                $_POST['icon'] = IdenticonService::generate(htmlspecialchars($_POST['name']))['name'];
            }
            $request_values = $this->getRequestValue(['name' => ''], ['file' => '', 'icon' => '', 'description' => '']);
            $id = $this->Teams->createTeams($request_values);
            FlashService::success('Equipe créée avec succès !');
        } catch (\Exception $e) {
            FlashService::error($e->getMessage(), 4);
        }
        if (isset($id)) {
            $this->redirect("teams/$id/manager");
        } else {
            $this->redirect('teams');
        }
    }
}
