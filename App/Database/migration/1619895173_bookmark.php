<?php

use App\Database\Migration;

class Bookmark extends Migration
{

    function up(): array
    {
        return [
            "CREATE TYPE bookmark_difficulty as enum ('EASY', 'MEDIUM', 'PRO');",
            "CREATE TABLE IF NOT EXISTS bookmarks
            (
                id           varchar(10)         not null unique primary key default random_string(10),
                title        text                not null,
                link         text                not null,
                thumbnail    text,
                reading_time interval,
                pin          boolean             not null                    default False,
                difficulty   bookmark_difficulty not null                    default 'EASY',
                created_by   integer             references users on update cascade on delete set null,
                created_at   timestamp           not null                    default now(),
                modified_at  timestamp           not null                    default now()
            );",
            "CREATE TRIGGER update_column BEFORE UPDATE ON bookmarks FOR EACH ROW EXECUTE FUNCTION update_modified_column();",
            "CREATE TABLE IF NOT EXISTS bookmark_comments
            (
                id          varchar(10) not null unique primary key default random_string(10),
                author_id   integer     not null references users on update cascade on delete cascade,
                bookmark_id varchar(10) not null references bookmarks on update cascade on delete cascade,
                content     text        not null,
                created_at  timestamp   not null                    default now(),
                modified_at timestamp   not null                    default now()
            );",
            "CREATE TRIGGER update_column BEFORE UPDATE ON bookmark_comments FOR EACH ROW EXECUTE FUNCTION update_modified_column();"
        ];
    }

    function down(): array
    {
        return [
            "DROP TYPE IF EXISTS bookmark_difficulty CASCADE;",
            "DROP TABLE IF EXISTS bookmarks CASCADE;",
            "DROP TRIGGER IF EXISTS update_column ON bookmarks;",
            "DROP TABLE IF EXISTS bookmark_comments;",
            "DROP TRIGGER IF EXISTS update_column ON bookmark_comments;",
        ];
    }
}
