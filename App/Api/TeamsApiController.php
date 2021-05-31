<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\Query;
use App\Database\QueryApi;
use App\Exceptions\InvalidParamException;
use App\Exceptions\MissingAccessException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ProtectFieldException;
use App\Exceptions\TokenNotFoundException;
use App\Exceptions\UnknownFieldException;
use App\Helper\TeamHelper;
use App\Security\Auth;

class TeamsApiController extends Controller
{
    /**
     * @param string $id
     * @throws TokenNotFoundException
     * @throws InvalidParamException
     * @throws ProtectFieldException
     * @throws UnknownFieldException
     */
    public function getTeam(string $id)
    {
        $query = (new QueryApi())
            ->select()
            ->from('teams_members')
            ->inner('teams', 'team_id', 'id')
            ->where('teams_members.user_id = ?', 'teams_members.team_id = ?')
            ->params([Auth::userApi()->id, $id]);

        $query->setPossibility(['favorite', 'role', 'team_id', 'invite_code', 'icon', 'name', 'public', 'created_at', 'modified_at']);
        $query->setDefault(['team_id', 'name', 'public', 'created_at']);
        $query->build();

        $this->respond_json($query->first());
    }

    /**
     * @param string $id
     * @throws InvalidParamException
     * @throws ProtectFieldException
     * @throws UnknownFieldException
     * @throws TokenNotFoundException
     * @throws NotFoundException
     * @throws MissingAccessException
     */
    public function getTeamMembers(string $id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$id, Auth::userApi()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Equipe non trouvé !');
        }

        $response = $query->first();

        if (!TeamHelper::canManageWithRole($response->role)) {
            throw new MissingAccessException("Tu n'as pas assez d'accès !");
        }

        $query = (new QueryApi())
            ->select()
            ->from('teams_members')
            ->inner('users', 'user_id', 'id')
            ->where('teams_members.team_id = ?')
            ->params([$id]);

        $query->setPossibility(['role', 'team_id', 'invite_code', 'icon', 'name', 'public', 'created_at', 'modified_at']);
        $query->setDefault(['id', 'username', 'role']);
        $query->build();

        $this->respond_json([
            'team_id' => $id,
            'count' => $query->rowCount(),
            'data' => $query->all()
        ]);
    }

    /**
     * @param string $id
     * @throws InvalidParamException
     * @throws ProtectFieldException
     * @throws UnknownFieldException
     * @throws TokenNotFoundException
     * @throws NotFoundException
     * @throws MissingAccessException
     */
    public function getTeamSettings(string $id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$id, Auth::userApi()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Equipe non trouvé !');
        }

        $response = $query->first();

        if (!TeamHelper::canManageWithRole($response->role)) {
            throw new MissingAccessException("Tu n'as pas assez d'accès !");
        }

        $query = (new QueryApi())
            ->select()
            ->from('teams_settings')
            ->inner('settings', 'setting_id', 'key')
            ->where('team_id = ?')
            ->params([$id]);

        $query->setPossibility(['setting_id', 'value', 'default_value']);
        $query->setDefault(['setting_id', 'value']);
        $query->build();

        $this->respond_json([
            'team_id' => $id,
            'count' => $query->rowCount(),
            'data' => $query->all()
        ]);
    }

    /**
     * @throws \App\Exceptions\TokenNotFoundException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\MissingAccessException
     * @throws \App\Exceptions\InvalidParamException
     */
    public function changeRoleMember(string $team_id, int $member_id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$team_id, Auth::userApi()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Equipe non trouvé !');
        }

        $response = $query->first();

        if (!TeamHelper::canManageWithRole($response->role)) {
            throw new MissingAccessException("Tu n'as pas la permission !");
        }

        $json = get_body_json();
        $role = $json['role'] ?? '';

        TeamHelper::checkRoleExist($role);

        $query = (new Query())
            ->update()
            ->from('teams_members')
            ->set([
                'role' => '?'
            ])
            ->where('team_id = ?', 'user_id = ?')
            ->params([$role, $team_id, $member_id]);

        $query->execute();
        $this->respond_json(['ok' => "user's role modified !"]);
    }

    /**
     * @throws \App\Exceptions\TokenNotFoundException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\MissingAccessException
     */
    public function addMemberWithId(string $team_id, int $member_id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$team_id, Auth::userApi()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Equipe non trouvé !');
        }

        $response = $query->first();

        if (!TeamHelper::canManageWithRole($response->role)) {
            throw new MissingAccessException("Tu n'as pas la permission !");
        }
        $query = (new Query())
            ->insert('team_id', 'user_id')
            ->into('teams_members')
            ->values(['?', '?'])
            ->params([$team_id, $member_id]);

        $query->execute();

        $this->loadModel('User', 'Users');

        $member = $this->User->getById($member_id, 'username', 'id', 'avatar', 'first_name', 'last_name');

        $this->respond_json(['member' => $member, 'message' => 'user added !']);
    }

    /**
     * @throws \App\Exceptions\TokenNotFoundException
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\MissingAccessException
     */
    public function deleteMember(string $team_id, int $member_id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$team_id, Auth::userApi()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Equipe non trouvé !');
        }

        $response = $query->first();

        if (!TeamHelper::canManageWithRole($response->role)) {
            throw new MissingAccessException("Tu n'as pas la permission !");
        }
        $query = (new Query())
            ->delete()
            ->from('teams_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$team_id, $member_id]);

        $query->execute();
        $this->respond_json(['ok' => 'user removed !']);
    }
}
