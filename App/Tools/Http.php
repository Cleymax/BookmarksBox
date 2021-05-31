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
            return $_ENV['BASE_URL'] . '/' . $route->getUri() . (empty($query) ? '' : '?' . urldecode($query));
        }
    }
    return $_ENV['BASE_URL'] . $name . urlencode($query);
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
    header('Location: ' . $url . $name);
}

function getBody(): string
{
    return file_get_contents('php://input');
}

/**
 * @return mixed
 */
function get_body_json()
{
    return json_decode(getBody(), true);
}

function cors(): void
{
    header("Access-Control-Allow-Origin: {$_ENV['BASE_URL']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }
        exit(0);
    }
}

function headers_security(): void
{
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    header('Referrer-Policy: same-origin');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
    header('X-Frame-Options: sameorigin');
    header('X-XSS-Protection: 1; mode=block');
    header("Content-Security-Policy: default-src 'self' ${_ENV['BASE_URL']}; img-src 'self' data: https://*; child-src 'none';style-src 'self' 'unsafe-inline'; font-src 'self' fonts.gstatic.com; script-src ${_ENV['BASE_URL']} 'unsafe-inline'; worker-src 'none';");
}
