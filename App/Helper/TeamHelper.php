<?php

namespace App\Helper;

use App\Database\Query;
use App\Exceptions\InvalidParamException;
use App\Security\Auth;

class TeamHelper
{
    public static function canManageWithRole(string $role): bool
    {
        return $role == 'OWNER' || $role == 'MANAGER';
    }

    public static function canManage(string $team_id): bool
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('user_id = ? team_id = ?')
            ->params([Auth::user()->id, $team_id]);

        $role = $query->first()->role;

        return self::canManageWithRole($role);
    }

    /**
     * @throws \App\Exceptions\InvalidParamException
     */
    public static function checkRoleExist($role): void
    {
        $roles = ['MEMBER', 'EDITOR', 'MANAGER', 'OWNER'];
        if (!in_array($role, $roles)) {
            throw new InvalidParamException("role", join(',', $roles));
        }
    }
}
