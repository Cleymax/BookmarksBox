<?php

namespace App\Security;

use App\Database\Query;
use App\Router\Router;
use App\Services\FlashService;
use App\Services\MailService;
use App\Tools\Str;
use PHPMailer\PHPMailer\Exception;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use function App\debug;

class Auth
{
    private const SESSION_NAME = 'BB';

    public static function register(string $mail, string $username, string $password, string $confirm): bool
    {
        if ($_POST['password'] != $_POST['confirm']) {
            FlashService::error("Votre mot de passe n'est pas identique");
        }

        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        } else {
            $query = (new Query())
                ->select("id", "email", "username")
                ->from("users")
                ->where("email = ? or username = ?")
                ->limit(1)
                ->params([$mail, $username]);

            $count = $query->rowCount();

            if ($count == 1) {
                throw new AuthException('Votre mail ou votre nom d\' utilisateur est déjà utilisé');
            }

            $password = password_hash($_ENV['SALT'] . $password, PASSWORD_BCRYPT);

            $key = Str::random(32);

            $query = (new Query())
                ->insert("email", "username", "password", "verify_key")
                ->into("users")
                ->values(["?", "?", "?", "?"])
                ->params([$mail, $username, $password, $key])
                ->returning('id');

            $response = $query->first();

            try {
                $url = Router::get_url('verify_account', [
                    'id' => $response->id,
                    'key' => $key
                ]);
                MailService::send($mail, 'Confirmation de compte.', '<!DOCTYPE html>
            <html lang="fr">
              <head>
                <meta name="viewport" content="width=device-width" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>' . $_ENV['SMTP_FROM'] . '</title>
                <style>

                  img {
                    border: none;
                    -ms-interpolation-mode: bicubic;
                    max-width: 100%;
                  }

                  body {
                    background-color: #f6f6f6;
                    font-family: sans-serif;
                    -webkit-font-smoothing: antialiased;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%;
                  }

                  table {
                    border-collapse: separate;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    width: 100%; }
                    table td {
                      font-family: sans-serif;
                      font-size: 14px;
                      vertical-align: top;
                  }

                  /* -------------------------------------
                      BODY & CONTAINER
                  ------------------------------------- */

                  .body {
                    background-color: #f6f6f6;
                    width: 100%;
                  }

                  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                  .container {
                    display: block;
                    margin: 0 auto !important;
                    /* makes it centered */
                    max-width: 580px;
                    padding: 10px;
                    width: 580px;
                  }

                  /* This should also be a block element, so that it will fill 100% of the .container */
                  .content {
                    box-sizing: border-box;
                    display: block;
                    margin: 0 auto;
                    max-width: 580px;
                    padding: 10px;
                  }

                  /* -------------------------------------
                      HEADER, FOOTER, MAIN
                  ------------------------------------- */
                  .main {
                    background: #ffffff;
                    border-radius: 3px;
                    width: 100%;
                  }

                  .wrapper {
                    box-sizing: border-box;
                    padding: 20px;
                  }

                  .content-block {
                    padding-bottom: 10px;
                    padding-top: 10px;
                  }

                  .footer {
                    clear: both;
                    margin-top: 10px;
                    text-align: center;
                    width: 100%;
                  }
                    .footer td,
                    .footer p,
                    .footer span,
                    .footer a {
                      color: #999999;
                      font-size: 12px;
                      text-align: center;
                  }

                  h1,
                  h2,
                  h3,
                  h4 {
                    color: #000000;
                    font-family: sans-serif;
                    font-weight: 400;
                    line-height: 1.4;
                    margin: 0;
                    margin-bottom: 30px;
                  }

                  h1 {
                    font-size: 35px;
                    font-weight: 300;
                    text-align: center;
                    text-transform: capitalize;
                  }

                  p,
                  ul,
                  ol {
                    font-family: sans-serif;
                    font-size: 14px;
                    font-weight: normal;
                    margin: 0;
                    margin-bottom: 15px;
                  }
                    p li,
                    ul li,
                    ol li {
                      list-style-position: inside;
                      margin-left: 5px;
                  }

                  a {
                    color: #3498db;
                    text-decoration: underline;
                  }

                  .btn {
                    box-sizing: border-box;
                    width: 100%; }
                    .btn > tbody > tr > td {
                      padding-bottom: 15px; }
                    .btn table {
                      width: auto;
                  }
                    .btn table td {
                      background-color: #ffffff;
                      border-radius: 5px;
                      text-align: center;
                  }
                    .btn a {
                      background-color: #ffffff;
                      border: solid 1px #3498db;
                      border-radius: 5px;
                      box-sizing: border-box;
                      color: #3498db;
                      cursor: pointer;
                      display: inline-block;
                      font-size: 14px;
                      font-weight: bold;
                      margin: 0;
                      padding: 12px 25px;
                      text-decoration: none;
                      text-transform: capitalize;
                  }

                  .btn-primary table td {
                    background-color: #3498db;
                  }

                  .btn-primary a {
                    background-color: #3498db;
                    border-color: #3498db;
                    color: #ffffff !important;
                  }

                  .last {
                    margin-bottom: 0;
                  }

                  .first {
                    margin-top: 0;
                  }

                  .align-center {
                    text-align: center;
                  }

                  .align-right {
                    text-align: right;
                  }

                  .align-left {
                    text-align: left;
                  }

                  .clear {
                    clear: both;
                  }

                  .mt0 {
                    margin-top: 0;
                  }

                  .mb0 {
                    margin-bottom: 0;
                  }

                  .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
                    max-width: 0;
                    opacity: 0;
                    overflow: hidden;
                    mso-hide: all;
                    visibility: hidden;
                    width: 0;
                  }

                  .powered-by a {
                    text-decoration: none;
                  }

                  hr {
                    border: 0;
                    border-bottom: 1px solid #f6f6f6;
                    margin: 20px 0;
                  }

                  @media only screen and (max-width: 620px) {
                    table[class=body] h1 {
                      font-size: 28px !important;
                      margin-bottom: 10px !important;
                    }
                    table[class=body] p,
                    table[class=body] ul,
                    table[class=body] ol,
                    table[class=body] td,
                    table[class=body] span,
                    table[class=body] a {
                      font-size: 16px !important;
                    }
                    table[class=body] .wrapper,
                    table[class=body] .article {
                      padding: 10px !important;
                    }
                    table[class=body] .content {
                      padding: 0 !important;
                    }
                    table[class=body] .container {
                      padding: 0 !important;
                      width: 100% !important;
                    }
                    table[class=body] .main {
                      border-left-width: 0 !important;
                      border-radius: 0 !important;
                      border-right-width: 0 !important;
                    }
                    table[class=body] .btn table {
                      width: 100% !important;
                    }
                    table[class=body] .btn a {
                      width: 100% !important;
                    }
                    table[class=body] .img-responsive {
                      height: auto !important;
                      max-width: 100% !important;
                      width: auto !important;
                    }
                  }

                  /* -------------------------------------
                      PRESERVE THESE STYLES IN THE HEAD
                  ------------------------------------- */
                  @media all {
                    .ExternalClass {
                      width: 100%;
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                      line-height: 100%;
                    }
                    .apple-link a {
                      color: inherit !important;
                      font-family: inherit !important;
                      font-size: inherit !important;
                      font-weight: inherit !important;
                      line-height: inherit !important;
                      text-decoration: none !important;
                    }
                    #MessageViewBody a {
                      color: inherit;
                      text-decoration: none;
                      font-size: inherit;
                      font-family: inherit;
                      font-weight: inherit;
                      line-height: inherit;
                    }
                    .btn-primary table td:hover {
                      background-color: #34495e !important;
                    }
                    .btn-primary a:hover {
                      background-color: #34495e !important;
                      border-color: #34495e !important;
                    }
                  }

                </style>
              </head>
              <body class="">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                  <tr>
                    <td>&nbsp;</td>
                    <td class="container">
                      <div class="content">

                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role="presentation" class="main">

                          <!-- START MAIN CONTENT AREA -->
                          <tr>
                            <td class="wrapper">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>
                                    <p>Bonjour !</p>
                                    <p>Validez votre compte en cliquant sur le bouton ci dessous !</p>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                      <tbody>
                                        <tr>
                                          <td align="left">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                              <tbody>
                                                <tr>
                                                  <td> <a href="' . $url . '" target="_blank">Vérifié</a> </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <p>Cette email a été envoyé à ' . $mail . '.</p>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <div class="footer">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td class="content-block powered-by">
                                Par <a href="https://clementperrin.fr">Clément PERRIN & Raphaël HIEN</a>.
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
              </body>
            </html>');
            } catch (Exception $e) {
                throw new AuthException('Erreur lors de l\'envoie de l\'email !');
            }
            return true;
        }
    }

    public static function login(string $email, string $password, bool $remember = false): bool
    {
        preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/', $password, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Votre mot de passe doit faire 6 caractères avec un chiffre et une majuscule !');
        }

        if ($_POST['login'] == 'iutannecy') {

        } else {
            $query = (new Query())
                ->select("id", "verify", "password", "email", "last_name", "first_name", "username", "totp")
                ->from("users")
                ->where("LOWER(email) LIKE LOWER(?) or LOWER(username) LIKE LOWER(?)")
                ->params([$email, $email]);
            $count = $query->rowCount();
            $response = $query->first();

            if (!$count || !password_verify($_ENV['SALT'] . $password, $response->password)) {
                throw new AuthException('Identifiant ou mot de passe incorrect !');
            }
            if (!$response->verify) {
                throw new AuthException('Compte non verifié ! Regardez vos emails !');
            }
            if ($remember) {
                setcookie(self::SESSION_NAME . '_RM', $response->id . '-' . sha1($response->email . $response->password), time() + 3600 * 24 * 30, '/', false, true);
            }

            $hasTotp = $response->totp != null;
            $_SESSION['user'] = array(
                'id' => $response->id,
                'email' => $email,
                'username' => $response->username,
                'logged' => !$hasTotp
            );
            return !$hasTotp;
        }
    }

    public static function remember_me(): bool
    {
        if (!empty($_COOKIE) && isset($_COOKIE[self::SESSION_NAME . '_RM'])) {
            $parts = explode("-", $_COOKIE[self::SESSION_NAME . '_RM']);

            if (sizeof($parts) != 2 || !is_numeric($parts[0])) {
                return false;
            }

            $id = intval($parts[0]);

            $query = (new Query())
                ->select("email", "password", "username")
                ->from("users")
                ->where("id = ?")
                ->limit(1)
                ->params([$id]);

            $response = $query->first();
            $mail = $response->email;
            $password = $response->password;

            if (sha1($mail . $password) == $parts[1]) {
                $_SESSION['user'] = array(
                    'id' => $id,
                    'email' => $mail,
                    'username' => $response->username,
                    'logged' => true
                );
                return true;
            }
        }
        return false;
    }

    public static function totp($code): bool
    {
        preg_match('/[0-9]{6}/', $code, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            throw new AuthException('Merci de rentrer un code corect !');
        }

        $query = (new Query())
            ->select("id", "totp")
            ->from("users")
            ->where("id = ?")
            ->params([self::user()->id]);

        if ($query->rowCount() == 0) {
            throw new AuthException('Utilisateur non trouvé !');
        }

        $response = $query->first();

        $g = new GoogleAuthenticator();
        return $g->checkCode($response->totp, $code);
    }

    public static function logout()
    {
        setcookie(self::SESSION_NAME . '_RM', '', time() - 1000, '/', false, true);
        $_SESSION['user'] = [];
        FlashService::success("Déconnexion réusis !", 5);
        header('Location: /auth/login');
        die();
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['logged'];
    }

    public static function user(): object
    {
        return (object)$_SESSION['user'];
    }

    public static function verify($id, string $key): bool
    {
        if (!is_numeric($id)) {
            throw new \Exception('Compte non trouvé !');
        }

        $query = (new Query())
            ->select('verify', 'verify_key')
            ->from('users')
            ->where('id = ?')
            ->params([$id]);

        $response = $query->first();

        if ($response->verify) {
            throw new \Exception('Compte déjà verifié !');
        }

        if ($response->verify_key != $key) {
            throw new \Exception('Lien invalide !');
        }

        $query = (new Query())
            ->update()
            ->from('users')
            ->set([
                'verify_key' => 'NULL',
                'verify' => 'True'
            ])
            ->where('id = ?')
            ->params([$id]);
        $query->execute();
        return true;
    }
}
