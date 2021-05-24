<?php

use App\Database\Migration;

class User_token extends Migration
{

    function up(): array
    {
        return ["
            CREATE TABLE IF NOT EXISTS users_tokens (
                id SERIAL NOT NULL UNIQUE PRIMARY KEY ,
                token VARCHAR(16) NOT NULL UNIQUE,
                user_id integer not null references users on update cascade on DELETE CASCADE ,
                created_at timestamp    not null default now()
            );
     "];
    }

    function down(): array
    {
        return ["DROP TABLE IF EXISTS users_tokens CASCADE;"];
    }
}
