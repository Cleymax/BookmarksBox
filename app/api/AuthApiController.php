<?php

namespace App\Api;

use App\Controllers\Controller;
use App\Exceptions\TokenNotFoundException;
use App\Security\Auth;
use App\Security\AuthException;
use App\Services\JwtService;
use DateTimeImmutable;

class AuthApiController extends Controller
{
    /**
     * @throws AuthException
     * @throws TokenNotFoundException
     */
    public function login()
    {
        $body = getBody();
        $json = json_decode($body, true);

        if (!is_array($json)) {
            throw new \InvalidArgumentException("Please specify in the body login information.", 400);
        }

        $token = false;
        if (!isset($json['login']) || !isset($json['password'])) {
            if (isset($json['token'])) {
                $token = true;
            } else {
                throw new \InvalidArgumentException("Please specify in the body the login and the password or a user token", 400);
            }
        }

        if (!$token) {
            $login = htmlspecialchars($json['login']);
            $password = htmlspecialchars($json['password']);

            $user = Auth::verifyLogin($login, $password);
        } else {
            $user = Auth::verifyLoginToken(htmlspecialchars($json['token']));
        }

        if ($user->totp) {
            throw new \InvalidArgumentException("The user has the 2FA activated !", 400);
        }

        $now = new DateTimeImmutable();
        $expiry = $now->modify("+${_ENV['JWT_EXPIRE']}");

        $token = JwtService::create([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'iat' => $now->getTimestamp(),
            'exp' => $expiry->getTimestamp()
        ]);

        $this->respond_json([
            'type' => "success",
            'token' => $token,
            'expiry' => $expiry->getTimestamp()
        ]);
    }
}
