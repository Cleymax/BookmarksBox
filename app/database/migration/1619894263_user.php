<?php

use App\Database\Migration;

class User extends Migration
{

    function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS users
            (
                id          serial       not null unique primary key,
                username    varchar(12)  not null unique,
                first_name  varchar(32),
                last_name   varchar(32),
                email       varchar(356) not null,
                password    varchar(200) null     default null,
                bio         text,
                totp        varchar(16),
                avatar      text         not null default '/img/default-avatar.png',
                verify      boolean      not null default False,
                verify_key  varchar(32),
                created_at  timestamp    not null default now(),
                modified_at timestamp    not null default now()
            );"
        ];
    }

    function down(): array
    {
        return [
            "DROP TABLE IF EXISTS users CASCADE ;"
        ];
    }
}
