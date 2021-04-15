<?php

namespace App\Router;

use App\Security\Auth;

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
        $this->routes[$route->getUri()] = $route;
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
        if (!empty($_GET) && isset($_GET['p'])) {
            foreach (Router::get()->getRoutes() as $route) {
                $request_type = $_SERVER['REQUEST_METHOD'];
                $method = $route->getMethods();
                if ((is_array($method) && in_array($request_type, $method)) || (is_string($method) && strtoupper($method) == strtoupper($request_type))) {
                    $parameters = [];
                    preg_match_all('/{(.+?)}/', $route->getUri(), $mm, PREG_SET_ORDER);
                    $path = preg_replace('/{(.+?)}/', '([^/]+)', $route->getUri());
                    if (preg_match("#^$path$#i", $_GET['p'], $mmm)) {
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
                                require_once ROOT_PATH . '/app/controllers/' . $controller_path . '.php';
                                $controller = new $controller_path();
                                if (method_exists($controller, $function)) {
                                    if (empty($mmm)) {
                                        $controller->$function();
                                    } else {
                                        $return = call_user_func_array([$controller, $function], array_values($mmm));
                                        if (is_string($return)) {
                                            echo $return;
                                        }
                                    }
                                } else {
                                    $this->not_found();
                                }
                            }
                        } else {
                            if (empty($mmm)) {
                                $action();
                            } else {
                                $return = call_user_func_array($action, array_values($mmm));
                                if (is_string($return)) {
                                    echo $return;
                                }
                            }
                        }
                        return;
                    }
                }
            }
            $this->not_found();
        } else {
            dd("Acceuil");
        }
    }

    private function need_login()
    {
        http_response_code(401);
    }

    private function not_found()
    {
        http_response_code(404);
    }
}
