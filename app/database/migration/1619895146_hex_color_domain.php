<?php

use App\Database\Migration;

class Hex_color_domain extends Migration
{

    function up(): array
    {
        return ["CREATE DOMAIN hex_color AS varchar(7) check ( VALUE ~ '^#[A-Za-z0-9]{6}$');"];
    }

    function down(): array
    {
        return ["DROP DOMAIN IF EXISTS hex_color CASCADE;"];
    }
}
