<?php

use App\Database\Migration;

class Folder extends Migration
{

    function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS folders
            (
                id               varchar(10) not null unique primary key default random_string(10),
                name             varchar(20) not null,
                parent_id_folder varchar(10) references folders on update cascade on delete cascade,
                color            hex_color   not null                    default '#ffffff',
                team_id          varchar(10) references teams on update cascade on delete cascade,
                user_id          integer references users on update cascade on delete cascade,
                created_at       timestamp   not null                    default now(),
                modified_at      timestamp   not null                    default now()
            );",
            "CREATE TRIGGER update_column BEFORE UPDATE ON folders FOR EACH ROW EXECUTE FUNCTION update_modified_column();"
        ];
    }

    function down(): array
    {
        return [
            "DROP TABLE IF EXISTS folders;",
            "DROP TRIGGER IF EXISTS update_column ON folders;"
        ];
    }
}
