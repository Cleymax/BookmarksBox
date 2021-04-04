<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();
