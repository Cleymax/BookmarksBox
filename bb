#!/usr/bin/env php
<?php

use App\Database\Database;
use App\Tools\Str;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/tools/Str.php';

$argv = $argv ?? $_SERVER['argv'] ?? [];
array_shift($argv);

$env = Dotenv::createImmutable(__DIR__);
$env->load();

switch ($argv[0]) {
    case "key":
        dump("Are you sure to generate a new salt key ? (y/N)");
        $ready = readline();
        if ($ready && $ready == 'y') {
            file_put_contents('.env', str_replace('SALT=' . $_ENV['SALT'], 'SALT=' . Str::random(), file_get_contents('.env')));
            dd('New salt key generate in .env file !');
        } else {
            dd("Cancel");
        }
    case "database":
        Database::set(
            new Database(
                $_ENV['DB_DATABASE'],
                $_ENV['DB_USER'] ?? 'postgres',
                $_ENV['DB_PASSWORD'] ?? 'postgres',
                $_ENV['DB_HOST'] ?? 'localhost',
                $_ENV['DB_PORT'] ?? '5432'
            )
        );
        dd(Database::get()->getAttribute(PDO::ATTR_CONNECTION_STATUS));
    default:
        dd('Unknown command !');
}
