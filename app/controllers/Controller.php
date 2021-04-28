<?php

namespace App\Controllers;

use App\Router\Router;
use App\Services\CsrfService;
use App\Services\Debugbar\DebugBarService;
use App\Views\View;

abstract class Controller
{
    public function render(View $view, string $title, array $data = [])
    {
        $render = DebugBarService::getDebugBar()->getJavascriptRenderer('/debugbar/');
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
        DebugBarService::getDebugBar()->collect();
        die();
    }

    public function check(?string $value, string $message): void
    {
        if ($value == null || $value == '' || trim($value) == '') {
            throw new \Exception($message);
        }
    }

    public function checkCsrf()
    {
        if (!isset($_POST['_csrf_token']) || $_POST['_csrf_token'] == null || $_POST['_csrf_token'] == '') {
            throw new \Exception("Erreur lors de l'envoie de la requete ! Réésayez !");
        }

        if (!CsrfService::verify($_POST['_csrf_token'])) {
            throw new \Exception("Erreur lors de l'envoie de la requete ! Réésayez !");
        }
    }
}
