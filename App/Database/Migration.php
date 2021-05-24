<?php

namespace App\Database;

abstract class Migration
{
    abstract function up(): array;

    abstract function down(): array;
}
