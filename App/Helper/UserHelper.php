<?php

namespace App\Helper;

use App\Database\Query;
use App\Security\Auth;

class UserHelper
{
    public static function has2fa(): bool
    {
        $query = (new Query())
            ->select("id", "totp")
            ->from("users")
            ->where("id = ?")
            ->params([Auth::user()->id]);

        return $query->first()->totp != null;
    }
}
