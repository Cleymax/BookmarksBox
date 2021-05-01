<?php

use App\Database\Migration;

class Settings extends Migration
{

    function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS settings( key varchar(50) unique primary key not null, default_value text);",
            "CREATE TABLE IF NOT EXISTS teams_settings
            (
                team_id    varchar(10) not null references teams on update cascade on delete cascade,
                setting_id varchar(50) not null references settings on update cascade on delete restrict,
                value      text        not null,
                primary key (team_id, setting_id)
            );",
            "CREATE TYPE teams_roles as enum ('MEMBER', 'EDITOR','MANAGER','OWNER');",
            "CREATE TABLE IF NOT EXISTS teams_members
            (
                team_id  varchar(10) not null references teams on update cascade on delete restrict,
                user_id  integer     not null references users on update cascade on delete cascade,
                favorite boolean     not null default False,
                role     teams_roles not null default 'MEMBER',
                primary key (team_id, user_id)
            );"
        ];
    }

    function down(): array
    {
        return [
            "DROP TABLE IF EXISTS settings CASCADE ;",
            "DROP TABLE IF EXISTS teams_settings;",
            "DROP TYPE IF EXISTS teams_roles CASCADE;",
            "DROP TABLE IF EXISTS teams_members;"
        ];
    }
}
