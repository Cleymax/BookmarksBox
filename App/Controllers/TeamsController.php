<?php

namespace App\Controllers;

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
        $request_values =  $this->getRequestValue(['name'], [
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
        $data = $this->Teams->getById($id);
        $this->render(View::new('teams.dashboard'), 'Equipe ' . $data->name, ['data' => $data]);
    }

    public function getTeams()
    {
        $data = $this->Teams->getAllForMe();
        if ($this->need_json()) {
            $this->respond_json($data);
        } else {
            $this->render(View::new('teams.all'), 'Mes équipes ', ['data' => $data]);
        }
    }
}
