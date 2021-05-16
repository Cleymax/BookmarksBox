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
            ->params([Auth::user()->id]);

        if ($query->rowCount() == 0) {
            throw new NotFoundException();
        } else {
            return $query->all();
        }
    }
}
