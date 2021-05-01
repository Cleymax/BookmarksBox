<?php

namespace App;

use App\Database\Database;
use App\Services\Debugbar\DebugBarService;
use DebugBar\DebugBar;
use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    require_once ROOT_PATH . '/../' . $class_name . '.php';
});

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required('DB_DATABASE');

if ($_ENV['MODE'] == 'dev') {
    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler());
    $whoops->register();
}

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

register_shutdown_function(function (DebugBar $debug) {
    if ($debug) {
        $debug->collect();
    }
}, DebugBarService::getDebugBar());

function debug(string $message)
{
    DebugBarService::getDebugBar()['messages']->addMessage($message, 'debug');
}
