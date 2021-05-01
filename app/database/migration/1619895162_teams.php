<?php

use App\Database\Migration;

class Teams extends Migration
{

    function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS teams
            (
                id          varchar(10) not null unique primary key default random_string(10),
                invite_code varchar(6)  null,
                name        varchar(20) not null,
                icon        text,
                public      boolean     not null                    default false,
                created_at  timestamp   not null                    default now(),
                modified_at timestamp   not null                    default now()
            );",
            "CREATE TRIGGER update_column BEFORE UPDATE ON teams FOR EACH ROW EXECUTE FUNCTION update_modified_column();"
        ];
    }

    function down(): array
    {
        return [
            "DROP TABLE IF EXISTS teams CASCADE ;",
            "DROP TRIGGER IF EXISTS update_column ON teams;"
        ];
    }
}
