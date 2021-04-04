<?php


class Router
{
    private static $instance;
    private $routes = [];

    public static function get(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function addRoute(Route $route): Route
    {
        $this->routes[$route->getUri()] = $route;
        return $route;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
