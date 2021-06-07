<?php
use App\Database\Migration;

class Team_update_public_col extends Migration
{

    function up(): array
    {
        return [
            "ALTER TABLE teams RENAME COLUMN public TO visibility;"
        ];
    }

    function down(): array
    {
        // TODO: Implement down() method.
    }
}
