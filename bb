#!/usr/bin/env php
<?php

use App\Database\Database;
use App\Tools\Str;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/tools/Str.php';
require_once __DIR__ . '/app/database/Database.php';

$argv = $argv ?? $_SERVER['argv'] ?? [];
array_shift($argv);

switch ($argv[0]) {
    case "key":
        $env = Dotenv::createImmutable(__DIR__);
        $env->load();
        dump("Are you sure to generate a new salt key ? (y/N)");
        $ready = readline();
        if ($ready && $ready == 'y') {
            file_put_contents('.env', str_replace('SALT=' . $_ENV['SALT'], 'SALT=' . Str::random(), file_get_contents('.env')));
            dd('New salt key generate in .env file !');
        } else {
            dd("Cancel");
        }
    case"env":
        if (!file_exists('.env')) {
            copy('.env.example', '.env');
            dd("Env file created !");
        } else {
            dd("Env file already exist !");
        }
    case "mode":
        $env = Dotenv::createImmutable(__DIR__);
        $env->load();
        if (sizeof($argv) != 2) {
            dd("php bb mode <dev,production>");
        }
        if ($argv[1] == 'dev') {
            file_put_contents('.env', str_replace('MODE=' . $_ENV['MODE'], 'MODE=dev', file_get_contents('.env')));
            dd("Website mode: dev");
        }else {
            file_put_contents('.env', str_replace('MODE=' . $_ENV['MODE'], 'MODE=production', file_get_contents('.env')));
            dd("Website mode: production");
        }
    case "database":
        $env = Dotenv::createImmutable(__DIR__);
        $env->load();
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
