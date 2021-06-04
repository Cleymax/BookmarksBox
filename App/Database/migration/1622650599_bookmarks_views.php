<?php

use App\Database\Migration;

class Bookmarks_views extends Migration
{

    function up(): array
    {
        return [
            "CREATE TABLE bookmark_view (bookarmk_id VARCHAR(10) NOT NULL REFERENCES bookmarks ON update cascade on delete cascade);"
        ];
    }

    function down(): array
    {
        return [

        ];
    }
}
