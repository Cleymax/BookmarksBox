<?php

namespace App\Services\Debugbar;

use App\Database\Database;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PDO\TraceablePDO;
use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;

class DebugBarService
{
    private static $debug_bar;

    /**
     * @throws \DebugBar\DebugBarException
     */
    public static function getDebugBar(): DebugBar
    {
        if (self::$debug_bar == null) {
            $db = new StandardDebugBar();
            $pdoCollector = new PDOCollector();
            $pdoCollector->addConnection(new TraceablePDO(Database::get()), 'main-db');
            $db->addCollector($pdoCollector);
            $db->addCollector(new ConfigCollector($_ENV));
            $db->addCollector(new RouteCollector());
            self::$debug_bar = $db;
        }
        return self::$debug_bar;
    }
}
