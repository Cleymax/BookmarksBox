<?php


class Route
{
    public static $METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    private $uri;
    private $methods;
    private $auth;
    private $action;
    private $name;

    /**
     * Route constructor.
     * @param string $uri
     * @param array|string $methods
     * @param Closure|array $action
     * @param bool $auth the user need to be not null. The default value is false
     */
    public function __construct(string $uri, $methods, $action, bool $auth = false)
    {
        $this->uri = trim($uri, '/');
        $this->methods = $methods;
        $this->action = $action;
        $this->auth = $auth;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array|string
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->auth;
    }

    /**
     * @return array|Closure
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public static function get(string $uri, $action, bool $auth = false): Route
    {
        return self::addRoute('GET', $uri, $action, $auth);
    }

    public static function post(string $uri, $action, bool $auth = false): Route
    {
        return self::addRoute('GET', $uri, $action, $auth);
    }

    public static function any(string $uri, $action, bool $auth = false): Route
    {
        return self::addRoute(self::$METHODS, $uri, $action, $auth);
    }


    public static function view(string $uri, View $view, bool $auth = false): Route
    {
        return self::addRoute(self::$METHODS, $uri, function () use ($view) {
            return $view;
        }, $auth);
    }

    public static function views(string $uri, string $view, string $layout = 'default', bool $auth = false): Route
    {
        return self::addRoute(self::$METHODS, $uri, function () use ($view, $layout) {
            return new View($view, $layout);
        });
    }

    public static function addRoute($methods, string $uri, $action = null, bool $auth = false): Route
    {
        return Router::get()->addRoute(new Route($uri, $methods, $action, $auth));
    }
}
