<?php

namespace App\Helper;

use App\Database\Query;
use App\Security\Auth;

class TeamHelper
{
    public static function isOwner(string $team_id): bool
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('user_id = ?', 'team_id = ?')
            ->params([Auth::user()->id, $team_id]);

        $role = $query->first()->role;
        return $role == 'OWNER';
    }

    public static function canManageWithRole(string $role): bool
    {
        return $role == 'OWNER' || $role == 'MANAGER';
    }

    public static function canEditWithRole(string $role): bool
    {
        return $role == 'OWNER' || $role == 'MANAGER' || $role == 'EDITOR';
    }

    public static function canManage(string $team_id): bool
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('user_id = ?', 'team_id = ?')
            ->params([Auth::user()->id, $team_id]);

        $role = $query->first()->role ?? 'not-member';

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

    public static function getRole(string $team_id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('user_id = ?', 'team_id = ?')
            ->params([Auth::user()->id, $team_id]);

        if ($query->rowCount() == 0) {
            return null;
        } else {
            return $query->first()->role;
        }
    }

    public static function getRoles(): array
    {
        return [
            'MEMBER' => 'Membre',
            'EDITOR' => 'Editeur',
            'MANAGER' => 'Manager',
            'OWNER' => 'Propriétaire'
        ];
    }

    public static function canEdit(string $id)
    {
        $query = (new Query())
            ->select('role')
            ->from('teams_members')
            ->where('user_id = ?', 'team_id = ?')
            ->params([Auth::user()->id, $id]);

        $role = $query->first()->role ?? 'not-member';

        return self::canEditWithRole($role);
    }
}
