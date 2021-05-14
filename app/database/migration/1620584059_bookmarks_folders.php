<?php
use App\Database\Migration;

class Bookmarks_folders extends Migration
{

    function up():array
    {
        return ['ALTER TABLE bookmarks ADD COLUMN IF NOT EXISTS "folder" VARCHAR(10) NULL REFERENCES folders ON UPDATE cascade ON delete cascade;'];
    }

    function down():array
    {
        return [];
    }
}
