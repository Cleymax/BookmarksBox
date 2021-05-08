<?php

use App\Database\Query;
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
}
