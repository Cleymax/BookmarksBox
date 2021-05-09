<?php

use App\Database\Query;
use App\Models\Model;
use App\Security\Auth;
use App\Security\AuthException;

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

    public function editSettings(array $values)
    {

        $value = [];
        foreach ($values as $k => $v) {
            $value[$k] = '?';
        }

        $query = (new Query())
            ->update()
            ->into($this->table)
            ->where("id = ?")
            ->set($value)
            ->params(array_merge(array_values($values), [Auth::user()->id]))
            ->returning("email", "username", "bio", "avatar");

        $response = $query->first();

        return $response;
    }
}
