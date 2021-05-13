<?php

use App\Router\Router;


/**
 * Generate URL-encoded query string.
 *
 * @param string $name the name of route or the relative path.
 * @param array $data
 * @return string
 * @see \http_build_query()
 * @example
 * ```
 * $url = get_query_url('login', [
 *  'id' => 5262
 * ]);
 * ```
 */
function get_query_url(string $name, array $data = []): string
{
    $query = http_build_query($data);
    foreach (Router::get()->getRoutes() as $route) {
        if ($route->getName() == $name) {
            return $_ENV['BASE_URL'] . '/' . $route->getUri() . (empty($query) ? '' : '?' . $query);
        }
    }
    return $_ENV['BASE_URL'] . $name . $query;
}

function redirect(string $name): void
{
    $url = $_ENV['BASE_URL'] . '/';
    foreach (Router::get()->getRoutes() as $route) {
        if ($route->getName() == $name) {
            $url .= $route->getUri();
        }
    }
    http_response_code(301);
    header('Location: ' . $url);
}

function getBody(): string
{
    return file_get_contents('php://input');
}

function cors(): void {
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        exit(0);
    }
}
