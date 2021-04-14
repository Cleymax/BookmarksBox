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
                    preg_match_all('/{.+}/', $route->getUri(), $mm);
                    $path = preg_replace('/{.+}/', '([^/]+)', $route->getUri());
                    if (preg_match("#^$path$#i", $_GET['p'], $mmm)) {
                        if ($route->isAuth() && !Auth::check()) {
                            $this->need_login();
                        }
                        $action = $route->getAction();
                        $params_count = sizeof($mmm);
                        if (is_array($action)) {
                            $action_size = sizeof($action);
                            if ($action_size >= 2) {
                                $controller_path = $action[0];
                                $function = $action[1];
                                require_once ROOT_PATH . '/app/controllers/' . $controller_path . '.php';
                                $controller = new $controller_path();
                                if (method_exists($controller, $function)) {
                                    if ($params_count == 1) {
                                        $controller->$function();
                                    } else {
                                        unset($mmm[0]);
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
                            if ($params_count == 1) {
                                $action();
                            } else {
                                unset($mmm[0]);
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
        dd("Need authentificaiton");
    }

    private function not_found()
    {
        http_response_code(404);
        dd("Page pas trouvé mec !");
    }
}
