<?php

namespace App;

use App\Database\Database;
use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    require_once ROOT_PATH . '/../' . $class_name . '.php';
});

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required('DB_DATABASE');

Database::set(
    new Database(
        $_ENV['DB_DATABASE'],
        $_ENV['DB_USER'] ?? 'postgres',
        $_ENV['DB_PASSWORD'] ?? 'postgres',
        $_ENV['DB_HOST'] ?? 'localhost',
        $_ENV['DB_PORT'] ?? '5432'
    )
);

if (session_status() == PHP_SESSION_NONE) {
    session_name("BB_session");
    session_start();
}
