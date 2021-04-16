<?php

namespace App;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    require_once ROOT_PATH . '/../' . strtolower($class_name) . '.php';
});

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();
