<?php

use App\Database\Migration;

class Password_forgot extends Migration
{

    function up(): array
    {
        return [
            "ALTER TABLE users ADD COLUMN password_reset_key VARCHAR(32);"
        ];
    }

    function down(): array
    {
        return [
            "ALTER TABLE users DROP COLUMN password_reset_key;"
        ];
    }
}
