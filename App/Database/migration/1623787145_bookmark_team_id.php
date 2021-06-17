<?php

use App\Database\Migration;

class Bookmark_team_id extends Migration
{

    function up(): array
    {
        return [
            "ALTER TABLE bookmarks ADD COLUMN team_id VARCHAR(10) NULL REFERENCES teams ON UPDATE cascade on delete cascade;"
        ];
    }

    function down(): array
    {
        return [];
    }
}
