<?php

namespace App\Helper;

use App\Database\Query;
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
}
