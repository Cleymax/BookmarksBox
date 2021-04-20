<?php

namespace App\Controllers;

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
}
