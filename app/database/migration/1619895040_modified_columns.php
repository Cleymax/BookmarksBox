<?php

use App\Database\Migration;

class Modified_columns extends Migration
{

    function up(): array
    {
        return [
            "CREATE OR REPLACE FUNCTION update_modified_column() RETURNS trigger AS
            '
                BEGIN
                    NEW.modified_at = NOW();
                    RETURN NEW;
                END;
            ' LANGUAGE plpgsql;",
            "CREATE TRIGGER update_column
            BEFORE UPDATE
            ON users
            FOR EACH ROW
            EXECUTE FUNCTION update_modified_column();"
        ];
    }

    function down(): array
    {
      return  ["DROP FUNCTION IF EXISTS update_modified_column() CASCADE;"];
    }
}
