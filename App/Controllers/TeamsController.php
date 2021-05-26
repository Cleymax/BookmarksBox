<?php

namespace App\Controllers;

use App\Services\FlashService;
use App\Views\View;

class TeamsController extends Controller
{
    /**
     * TeamsController constructor.
     */
    public function __construct()
    {
        $this->loadModel('Teams');
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

    public function folderView(string $id)
    {
        $equipes = $this->Teams->getAllForMe();
        $data = $this->Teams->getById($id);
        $this->render(View::new('teams.dashboard', 'dashboard'), 'Equipe ' . $data->name, ['data' => $data, 'id' => $id,'equipes' => $equipes]);
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
        try {
            $data = $this->Teams->getById($id);
            $members = $this->Teams->getMember($id);
        } catch (\Exception $e) {
            $members = [];
            FlashService::error($e->getMessage(), 4);
        }

        $this->render(View::new('teams.manager', 'dashboard'), 'Equipe ' . $data->name, ['data' => $data, 'id' => $id, 'members' => $members]);
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
            }
        } catch (\Exception $e) {
            FlashService::error($e->getMessage(), 4);
        }
        $this->render(View::new('teams.invite', 'dashboard'), 'Rejoindre une équipe', ['equipes' => $data, 'equipe' => $response ?? []]);
    }
}
