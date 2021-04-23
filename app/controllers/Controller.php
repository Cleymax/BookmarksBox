<?php

namespace App\Controllers;

use App\Router\Router;
use App\Security\AuthException;
use App\Services\FlashService;
use App\Views\View;

abstract class Controller
{
    public function render(View $view, string $title, array $data = [])
    {
        extract($data);
        ob_start();
        require_once(dirname(ROOT_PATH) . '/resources/views/' . str_replace('.', DIRECTORY_SEPARATOR, $view->getName()) . '.php');
        $content = ob_get_clean();
        require_once(dirname(ROOT_PATH) . '/resources/layouts/' . $view->getLayout() . '.php');
    }

    public function need_json(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json' || isset($_GET['json']);
    }

    public function respond_json($json)
    {
        header("Content-Type: application/json");
        echo json_encode($json);
    }

    public function redirect(string $s)
    {
        foreach (Router::get()->getRoutes() as $route) {
            if ($route->getName() == $s) {
                header('Location: /' . $route->getUri());
                return;
            }
        }
        header('Location: ' . $s);
    }

    public function check(?string $value, string $message): void
    {
        if ($value == null || $value == '' || trim($value) == '') {
            throw new AuthException($message);
        }
    }
}
