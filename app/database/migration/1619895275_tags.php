<?php

use App\Database\Migration;

class Tags extends Migration
{

    function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS tags
            (
                id          varchar(10) not null unique primary key default random_string(10),
                name        varchar(20) not null,
                color       hex_color   not null                    default '#ffffff',
                created_at  timestamp   not null                    default now(),
                modified_at timestamp   not null                    default now()
            );",
            "CREATE TRIGGER update_column BEFORE UPDATE ON tags FOR EACH ROW EXECUTE FUNCTION update_modified_column();",
            "CREATE TABLE IF NOT EXISTS bookmark_tags
            (
                bookmark_id varchar(10) not null references bookmarks on update cascade on delete cascade,
                tag_id      varchar(10) not null references tags on update CASCADE on delete cascade,
                primary key (bookmark_id, tag_id)
            );",
            "CREATE TABLE IF NOT EXISTS user_favorite_bookmarks
            (
                user_id     integer     not null references users on update cascade on delete cascade,
                bookmark_id varchar(10) not null references bookmarks on update cascade on delete cascade,
                primary key (user_id, bookmark_id)
            );"
        ];
    }

    function down(): array
    {
        return [
            "DROP TABLE IF EXISTS tags CASCADE ;",
            "DROP TRIGGER IF EXISTS update_column ON tags;",
            "DROP TABLE IF EXISTS bookmark_tags;",
            "DROP TABLE IF EXISTS user_favorite_bookmarks;"
        ];
    }
}
