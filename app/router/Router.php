<?php

namespace App\Router;

use App\Security\Auth;
use App\Services\FlashService;
use App\Views\View;

class Router
{
    private static $instance;
    private $routes = [];

    public static function get(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function addRoute(Route $route): Route
    {
        $this->routes[] = $route;
        return $route;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function onRequest(): void
    {
        foreach (Router::get()->getRoutes() as $route) {
            $request_type = $_SERVER['REQUEST_METHOD'];
            $method = $route->getMethods();
            if ((is_array($method) && in_array($request_type, $method)) || (is_string($method) && strtoupper($method) == strtoupper($request_type))) {
                $parameters = [];
                preg_match_all('/{(.+?)}/', $route->getUri(), $mm, PREG_SET_ORDER);
                $path = preg_replace('/{(.+?)}/', '([^/]+)', $route->getUri());
                if (preg_match("#^$path$#i", $_GET['p'] ?? '', $mmm)) {
                    array_shift($mmm);
                    foreach ($mmm as $params) {
                        $index = array_search($params, $mmm);
                        array_push($parameters, [$mm[$index][1] => $params]);
                    }
                    foreach ($route->wheres as $where => $expression) {
                        foreach ($parameters as $para) {
                            if (array_key_exists($where, $para)) {
                                if (!preg_match("@$expression@", $para[$where])) {
                                    $this->not_found();
                                }
                            }
                        }
                    }
                    if ($route->isAuth() && !Auth::check()) {
                        $this->need_login();
                    }
                    $action = $route->getAction();
                    if (is_array($action)) {
                        $action_size = sizeof($action);
                        if ($action_size >= 2) {
                            $controller_path = $action[0];
                            $function = $action[1];
                            $controller = new $controller_path();
                            if (method_exists($controller, $function)) {
                                $this->call_functions($mmm, [$controller, $function]);
                            } else {
                                $this->not_found();
                            }
                        }
                    } else {
                        $this->call_functions($mmm, $action);
                    }
                    return;
                }
            }
        }
        $this->not_found();
    }

    private function call_functions(array $prams, $callback)
    {
        if (empty($prams)) {
            if (is_array($callback)) {
                $function = $callback[1];
                $return = $callback[0]->$function();
            } else {
                $return = $callback();
            }
        } else {
            $return = call_user_func_array($callback, array_values($prams));
        }
        if (is_string($return)) {
            echo $return;
        } else if ($return instanceof View) {
            ob_start();
            require_once(dirname(ROOT_PATH) . '/resources/views/' . str_replace('.', DIRECTORY_SEPARATOR, $return->getName()) . '.php');
            $content = ob_get_clean();
            require_once(dirname(ROOT_PATH) . '/resources/layouts/' . $return->getLayout() . '.php');
        }
        FlashService::onRequest();
    }

    public static function need_login(): void
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']) && !$_SESSION['user']['logged']) {
            FlashService::add('warning', "Merci d'entrer votre code de double authentification !", 15);
            header('Location: /auth/2fa');
        } else {
            FlashService::error("Tu dois être connecté pour accèder à ceci !", 5);
            header('Location: /auth/login');
        }
        die();
    }

    public static function get_url(string $s, array $params = []): string
    {
        $data = '';
        if (!empty($data)) {
            $data = http_build_query($params);
        }
        foreach (Router::get()->getRoutes() as $route) {
            if ($route->getName() == $s) {
                return $_ENV['BASE_URL'] . '/' . $route->getUri() . $data;
            }
        }
        return $_ENV['BASE_URL'] . $s . $data;
    }

    public static function not_found(): void
    {
        http_response_code(404);
    }

    /**
     * Debug the router. Show all routes with dd.
     */
    public static function debug(): void
    {
        foreach (Router::get()->getRoutes() as $routes) {
            dump($routes);
        }
        die();
    }

    /**
     * Initialize the default Router.
     */
    public static function init(): void
    {
        Router::get()->onRequest();
    }
}
