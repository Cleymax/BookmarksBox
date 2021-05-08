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
        $render = DebugBarService::getDebugBar()->getJavascriptRenderer($_ENV['BASE_URL'] . '/debugbar/');
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
                header('Location: ' . $_ENV['BASE_URL'] . '/' . $route->getUri());
                die();
            }
        }
        header('Location: ' . $_ENV['BASE_URL'] . '/' . $s);
        DebugBarService::getDebugBar()->collect();
        die();
    }

    /**
     * @throws \Exception
     */
    public function checkPost(string $value, string $message, ?string $regex = null): void
    {
        if (!isset($_POST[$value]) || $_POST[$value] == null || $_POST[$value] == '') {
            throw new \Exception($message);
        }
        if (!is_null($regex)) {
            preg_match("@$regex@", $_POST[$value], $matchs);
            if (empty($matchs)) {
                throw new \Exception($message);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function checkEmail(string $value, string $message): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception($message);
        }
    }

    public function checkGet(string $value, string $message, ?string $regex = null): void
    {
        if (!isset($_GET[$value]) || $_GET[$value] == null || $_GET[$value] == '') {
            throw new \Exception($message);
        }
        if (!is_null($regex)) {
            preg_match("@$regex@", $_GET[$value], $matchs);
            if (empty($matchs)) {
                throw new \Exception($message);
            }
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

    public function loadModel(string $model, ?string $table = null)
    {
        require ROOT_PATH . "/../app/models/$model.php";
        if (is_null($table)) {
            $this->$model = new $model();
        } else {
            $this->$model = new $model($table);
        }
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return file_get_contents('php://input');
    }

    /**
     * @param array $require
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public function getRequestValue(array $require, array $fields): array
    {
        $request_values = [];

        if ($this->need_json()) {
            $json = json_decode($this->getBody(), true);
            if (is_object($json) || !is_array($json)) {
                throw new \Exception('Need to send a array !');
            }
            $request_body = $json;
        } else {
            $request_body = $_POST;
        }
        foreach ($request_body as $k => $v) {
            if (!array_key_exists($k, $fields)) {
                throw new \Exception('unkhown key: ' . $k, 400);
            }
            if ($this->need_json() && gettype($v) != gettype($fields[$k])) {
                throw new \Exception('key: ' . $k . ' need to be a ' . gettype($fields[$k]), 400);
            }
            $request_values[$k] = $v;
        }

        foreach ($require as $r) {
            if (!isset($request_values[$r])) {
                throw new \Exception('Key: ' . $r . ' is require !', 400);
            }
        }

        return $request_values;
    }
}
