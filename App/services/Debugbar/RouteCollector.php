<?php

namespace App\Services\Debugbar;

use App\Router\Route;
use App\Router\Router;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class RouteCollector extends DataCollector implements Renderable
{

    /**
     * {@inheritDoc}
     */
    public function collect(): array
    {
        return Router::get_current() != null ? $this->getRouteInformation(Router::get_current()) : [];
    }

    function getName(): string
    {
        return 'route';
    }

    function getWidgets(): array
    {
        $name = $this->getName();
        $widgets = [
            "$name" => [
                "icon" => "share",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "$name",
                "default" => "{}"
            ]
        ];
        $widgets['currentroute'] = [
            "icon" => "share",
            "tooltip" => "Route",
            "map" => "route.uri",
            "default" => ""
        ];
        return $widgets;
    }

    private function getRouteInformation(Route $route): array
    {
        $methods = is_string($route->getMethods()) ? $route->getMethods() : join(', ', $route->getMethods());
        $uri = $methods . ' ' . $route->getUri();
        $action = $route->getAction();

        $result = [
            'uri' => $uri,
            'name' =>  $route->getName() ?? 'Aucun'
        ];
        if (is_array($action)) {
            $a = [
                'controller' => $action[0],
                'method' =>  $action[1]
            ];
        } else {
            $a = [
                'action' => $action
            ];
        }
        return array_merge($result, $a);
    }
}
