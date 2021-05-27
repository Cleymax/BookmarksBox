<?php

use App\Database\Query;
use App\Exceptions\NotFoundException;
use App\Models\Model;
use App\Security\Auth;

class Teams extends Model
{
    public function create(array $values): string
    {
        $value = [];
        foreach ($values as $k => $v) {
            $value[$k] = '?';
        }

        $query = (new Query())
            ->insertArray(array_keys($values))
            ->into($this->table)
            ->values($value)
            ->params(array_values($values))
            ->returning('id');

        $team_id = $query->first()->id;


        $query = (new Query())
            ->insert('team_id', 'user_id', 'role')
            ->into('teams_members')
            ->values([
                'team_id' => '?',
                'user_id' => '?',
                'role' => "'OWNER'"
            ])
            ->params([$team_id, Auth::user()->id]);

        $query->execute();

        return $team_id;
    }

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public function getAllForMe(): array
    {
        $query = (new Query())
            ->select()
            ->from($this->table . '_members')
            ->inner($this->table, 'team_id', 'id')
            ->where('user_id = ?')
            ->params([Auth::user()->id])
            ->order('favorite', false);

        if ($query->rowCount() == 0) {
            throw new NotFoundException();
        } else {
            return $query->all();
        }
    }

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public function getMember(string $id): array
    {
        $query = (new Query())
            ->select()
            ->from($this->table . '_members')
            ->inner('users', 'user_id', 'id')
            ->where('team_id = ?')
            ->params([$id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException('Aucun membre');
        } else {
            return $query->all();
        }
    }

    public function getTeamByCode(string $code)
    {
        $query = (new Query())
            ->select()
            ->from($this->table)
            ->where('invite_code = ?')
            ->params(["$code"]);

        return $query->first();
    }

    public function join(string $id)
    {
        (new Query())
            ->insert('team_id', 'user_id')
            ->into($this->table . '_members')
            ->values(["?", "?"])
            ->params([$id, Auth::user()->id])->execute();
    }

    public function getPublic(): array
    {
        $q = (new Query())
            ->select('team_id')
            ->from($this->table . '_members')
            ->inner($this->table, 'team_id', 'id')
            ->where('user_id = ?');

        $query = (new Query())
            ->select()
            ->from($this->table)
            ->where('public = True')
            ->whereIn("id", $q, true)
            ->params([Auth::user()->id]);
        return $query->all();
    }

    public function leaveTeam(string $id)
    {
        (new Query())
            ->delete()
            ->from($this->table . '_members')
            ->where('team_id = ?', 'user_id = ?')
            ->params([$id, Auth::user()->id])->execute();
    }
}
