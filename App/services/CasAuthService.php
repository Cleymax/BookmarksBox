<?php

namespace App\Services;

class CasAuthService
{
    public static function forceLogin(): void
    {
        http_response_code(301);
        header('Location: ' . ($_ENV['CAS_PORT'] == "443" ? "https://" : "http://") . $_ENV['CAS_SERVER_HOSTNAME'] . $_ENV['CAS_BASE'] . "/login?service=" . urlencode(get_query_url('cas')));
        die();
    }

    /**
     * @param string $ticket
     * @return false|string
     */
    public static function verify_ticket(string $ticket)
    {
        $url = ($_ENV['CAS_PORT'] == "443" ? "https://" : "http://") . $_ENV['CAS_SERVER_HOSTNAME'] . $_ENV['CAS_BASE'] . "/serviceValidate?ticket=" . $ticket . "&service=" . urlencode(get_query_url('cas'));
        $response = file_get_contents($url);
        if (preg_match('/cas:authenticationSuccess/', $response)) {
            return $response;
        } else {
            return false;
        }
    }

    public static function getUser($response)
    {
        preg_match('@<cas:user>(?<username>.+)</cas:user>@m', $response, $r);
        return $r['username'] ?? null;
    }
}
