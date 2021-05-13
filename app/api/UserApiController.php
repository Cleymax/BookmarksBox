<?php
namespace App\Api;

use App\Controllers\Controller;
use App\Database\QueryApi;
use App\Security\Auth;

class UserApiController extends Controller
{

    /**
     * @throws \App\Exceptions\UnknownFieldException
     * @throws \App\Exceptions\InvalidParamException
     * @throws \App\Exceptions\ProtectFieldException
     * @throws \App\Exceptions\TokenNotFoundException
     */
    public function getTeams()
    {
        $query = (new QueryApi())
            ->select()
            ->from('teams_members')
            ->inner('teams', 'team_id', 'id')
            ->where('user_id = ?')
            ->params([Auth::userApi()->id]);

        $query->setProtect(['totp', 'password', 'password_reset_key', 'verify_key']);
        $query->setPossibility(['favorite', 'role', 'team_id', 'invite_code', 'icon', 'name', 'public', 'created_at', 'modified_at']);
        $query->setDefault(['team_id', 'name', 'public', 'icon']);
        $query->build();

        $this->respond_json($query->all());
    }

    /**
     * @throws \App\Exceptions\ProtectFieldException
     * @throws \App\Exceptions\InvalidParamException
     * @throws \App\Exceptions\UnknownFieldException
     * @throws \App\Exceptions\TokenNotFoundException
     */
    public function getMe()
    {
        $query = (new QueryApi())
            ->select()
            ->from('users')
            ->where('id = ?')
            ->params([Auth::userApi()->id]);

        $query->setProtect(['totp', 'password', 'password_reset_key', 'verify_key']);
        $query->setPossibility(['id', 'username', 'last_name', 'first_name', 'email', 'bio', 'avatar', 'verify', 'created_at', 'modified_at']);
        $query->setDefault(['id', 'username', 'email']);
        $query->build();
        $this->respond_json($query->first());
    }
}
