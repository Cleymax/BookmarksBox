<?php
use App\Database\Migration;

class Bookmark_description extends Migration
{

    function up(): array
    {
        return ["ALTER TABLE bookmarks ADD COLUMN description TEXT"];
    }

    function down(): array
    {
        return [];
    }
}
