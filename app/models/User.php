<?php

use App\Database\Query;
use App\Exceptions\NotFoundException;
use App\Models\Model;
use App\Security\Auth;

class User extends Model
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function resetTotp()
    {
        (new Query())
            ->update()
            ->from($this->table)
            ->set(['totp' => 'NULL'])
            ->where('id = ?')
            ->params([Auth::user()->id])
            ->execute();
    }

    public function changeTotp(string $secret)
    {
        (new Query())
            ->update()
            ->from($this->table)
            ->set(['totp' => '?'])
            ->where('id = ?')
            ->params([$secret, Auth::user()->id])
            ->execute();
    }

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public function getTeamsApi(array $fields = null): array
    {
        $this->table = 'teams';
        $query = (new Query())
            ->selectArray($fields)
            ->from($this->table . '_members')
            ->inner($this->table, 'team_id', 'id')
            ->where('user_id = ?')
            ->params([Auth::user()->id])
            ->order('team_id');

        if ($query->rowCount() == 0) {
            throw new NotFoundException('you have no team.');
        }

        return $query->all();
    }
}
