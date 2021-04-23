<?php

namespace App\Router;

use App\Views\View;

/**
 * Class Route, represent a endpoint of the website, api.
 * @package app\Router
 * @see \App\Router\Router
 * @author Clément PERRIN <clement.perrin@etu.univ-smb.fr>
 */
class Route
{
    /**
     * @var string[] all type of route
     */
    public static $METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    /**
     * @var string the uri of the endpoint.
     */
    private $uri;
    /**
     * @var array|string the method.
     */
    private $methods;
    /**
     * @var bool the user need to be.
     */
    private $auth;
    /**
     * @var array|\Closure the function call when the endpoint is called.
     */
    private $action;
    /**
     * The regular expression requirements.
     *
     * @var array
     */
    public $wheres = [];

    /**
     * Name of the route.
     * @var string
     */
    public $name;

    /**
     * Route constructor.
     * @param string $uri
     * @param array|string $methods
     * @param array|\Closure $action
     * @param string $name
     * @param bool $auth the user need to be not null. The default value is false
     */
    public function __construct(string $uri, $methods, $action, string $name = '', bool $auth = false)
    {
        $this->uri = trim($uri, '/');
        $this->name = $name;
        $this->methods = $methods;
        $this->action = $action;
        $this->auth = $auth;
    }

    /**
     * Retrieve the uri of the route.
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Return the method(s) of the route.
     * @return array|string
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Return if the route need to be auth.
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->auth;
    }

    /**
     * Return the function call or the array with the controller class and function.
     * @return array|\Closure
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set a regular expression requirement on the route.
     * @param string $name
     * @param string $expression
     * @return $this
     */
    public function where(string $name, string $expression): self
    {
        $this->wheres[$name] = $expression;
        return $this;
    }

    /**
     * Set the regulars expressions requirement on the route.
     * @param array $where
     * @return $this
     */
    public function whereArray(array $where): self
    {
        $this->wheres = $where;
        return $this;
    }

    /**
     * Get the name of the route.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Register a new GET route with the router.
     * @param string $uri
     * @param $action
     * @param string $name
     * @param bool $auth
     * @return Route
     */
    public static function get(string $uri, $action, bool $auth = false, string $name = ''): Route
    {
        return self::addRoute('GET', $uri, $action, $name, $auth);
    }

    /**
     * Register a new POST route with the router.
     * @param string $uri
     * @param $action
     * @param string $name
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function post(string $uri, $action, bool $auth = false, string $name = ''): self
    {
        return self::addRoute('POST', $uri, $action, $name, $auth);
    }

    /**
     * Register a new PUT route with the router.
     * @param string $uri
     * @param $action
     * @param string $name
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function put(string $uri, $action, bool $auth = false, string $name = ''): self
    {
        return self::addRoute('PUT', $uri, $action, $name, $auth);
    }

    /**
     * Register a new DELETE route with the router.
     * @param string $uri
     * @param $action
     * @param string $name
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function delete(string $uri, $action, bool $auth = false, string $name = ''): self
    {
        return self::addRoute('DELETE', $uri, $action, $name, $auth);
    }

    /**
     * Register a new route responding to all verbs.
     * @param string $uri
     * @param $action
     * @param string $name
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function any(string $uri, $action, bool $auth = false, string $name = ''): self
    {
        return self::addRoute(self::$METHODS, $uri, $action, $name, $auth);
    }

    /**
     * Register a new route that returns a view.
     * @param string $uri
     * @param \App\Views\View $view
     * @param string $name
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function view(string $uri, View $view, bool $auth = false, string $name = ''): self
    {
        return self::addRoute('GET', $uri, function () use ($view) {
            return $view;
        }, $name, $auth);
    }

    /**
     * Create a redirect from one URI to another.
     * @param string $uri
     * @param string $destination
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function redirect(string $uri, string $destination, bool $auth = false): self
    {
        return self::addRoute(self::$METHODS, $uri, function () use ($destination) {
            http_response_code(302);
            header('Location: ' . $destination);
        }, $auth);
    }

    /**
     * Add a Route to the default router.
     * @param $methods
     * @param string $uri
     * @param null $action
     * @param string $name
     * @param bool $auth
     * @return \App\Router\Route
     */
    public static function addRoute($methods, string $uri, $action = null, string $name = '', bool $auth = false): self
    {
        return Router::get()->addRoute(new Route($uri, $methods, $action, $name, $auth));
    }
}
