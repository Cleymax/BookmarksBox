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
    http_response_code(HTTP_REDIRECT_PERM);
    header('Location: ' . $url);
}

function getBody(): string
{
    return file_get_contents('php://input');
}
