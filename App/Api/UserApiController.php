<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Database\QueryApi;
use App\Exceptions\InvalidParamException;
use App\Exceptions\ProtectFieldException;
use App\Exceptions\TokenNotFoundException;
use App\Exceptions\UnknownFieldException;
use App\Security\Auth;

class UserApiController extends Controller
{

    /**
     * @throws UnknownFieldException
     * @throws InvalidParamException
     * @throws ProtectFieldException
     * @throws TokenNotFoundException
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

        $this->respond_json([
            'count' => $query->rowCount(),
            'data' => $query->all()
        ]);
    }

    /**
     * @throws ProtectFieldException
     * @throws InvalidParamException
     * @throws UnknownFieldException
     * @throws TokenNotFoundException
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

    /**
     * @throws \App\Exceptions\ProtectFieldException
     * @throws \App\Exceptions\InvalidParamException
     * @throws \App\Exceptions\UnknownFieldException
     */
    public function getUser()
    {
        $query = (new QueryApi())
            ->select()
            ->from('users');

        $query->setProtect(['totp', 'password', 'password_reset_key', 'verify_key']);
        $query->setPossibility(['id', 'username', 'last_name', 'first_name']);
        $query->setDefault(['id', 'username','first_name','last_name']);
        $query->setSearch('username');
        $query->build();

        $this->respond_json([
            'count' => $query->rowCount(),
            'data' => $query->all()
        ]);
    }
}
