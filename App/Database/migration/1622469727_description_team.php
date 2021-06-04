<?php

use App\Database\Migration;

class Description_team extends Migration
{

    function up(): array
    {
       return [
           "ALTER TABLE teams ADD COLUMN description TEXT;"
       ];
    }

    function down(): array
    {
        return [];
    }
}
