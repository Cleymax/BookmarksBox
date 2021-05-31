#!/usr/bin/env php
<?php

use App\Database\Database;
use App\Tools\Str;
use Dotenv\Dotenv;
use Faker\Factory;

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/App/tools/Str.php';
require_once __DIR__ . '/App/database/Migration.php';
require_once __DIR__ . '/App/database/Database.php';

$argv = $argv ?? $_SERVER['argv'] ?? [];
array_shift($argv);

switch ($argv[0]) {
    case "salt-key":
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
    case "app-key":
        $env = Dotenv::createImmutable(__DIR__);
        $env->load();
        dump("Are you sure to generate a new application key ? (y/N)");
        $ready = readline();
        if ($ready && $ready == 'y') {
            file_put_contents('.env', str_replace('APP_KEY=' . $_ENV['APP_KEY'], 'APP_KEY=' . Str::random(), file_get_contents('.env')));
            dd('New application key generate in .env file !');
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
    case "migrate":
        if (sizeof($argv) < 2) {
            dd("php bb migrate <up,down>");
        }

        $folder_path = __DIR__ . '/app/database/migration/LAST_UPDATE';
        $time = 0;
        $files = scandir(__DIR__ . '/app/database/migration/');
        $files = array_slice($files, 2);
        if (file_exists($folder_path)) {
            array_pop($files);
            $check = $argv[2] ?? '';
            if ($check != '--no-check') {
                $time = intval(file_get_contents($folder_path));
            }
        }
        sort($files);
        $request = [];

        foreach ($files as $file) {
            require_once __DIR__ . '/app/database/migration/' . $file;
            $time_file = intval(explode('_', $file)[0]);

            $class_name = ucfirst(str_replace('.php', '', str_replace(explode('_', $file)[0] . '_', '', $file)));
            $migration = new $class_name();
            if ($argv[1] == 'up') {
                if ($time_file >= $time) {
                    dump(" > " . $file);
                    $request = array_merge($request, $migration->up());
                }
            } else {
                dump(" > " . $file);
                $request = array_merge($request, $migration->down());
            }
        }

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
        foreach ($request as $r) {
            Database::get()->query(str_replace(array("\n", "\r"), '', $r));
        }
        file_put_contents($folder_path, time());
        dd("Migration go !");
    case "migration":
        if (sizeof($argv) != 2) {
            dd("php bb migration <name>");
        }
        $name = ucfirst(strtolower($argv[1]));
        ob_start();
        include __DIR__ . '/app/database/MigrationTemplate.php';
        $contents = ob_get_clean();
        file_put_contents(__DIR__ . '/app/database/migration/' . time() . '_' . strtolower($name) . '.php', $contents);
        dd("new migration created !");
    case "mode":
        $env = Dotenv::createImmutable(__DIR__);
        $env->load();
        if (sizeof($argv) != 2) {
            dd("php bb mode <dev,production>");
        }
        if ($argv[1] == 'dev') {
            file_put_contents('.env', str_replace('MODE=' . $_ENV['MODE'], 'MODE=dev', file_get_contents('.env')));
            dd("Website mode: dev");
        } else {
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
