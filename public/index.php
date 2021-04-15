<?php

use App\Router\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/../vendor/autoload.php';
require_once ROOT_PATH . '/../app/Bootsrap.php';

$mail = new PHPMailer(true);

Route::get("/mail", function () use ($mail) {
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'mail.clementperrin.fr';
        $mail->SMTPAuth = true;
        $mail->Username = 'contact@clementperrin.fr';
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('contact@clementperrin.fr', 'NoReply - BookmarksBox');
        $mail->addAddress("clemperrin1@gmail.com");
        $mail->addReplyTo("contact@clementperrin.fr");
        $mail->XMailer = '';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Rénisialisation de mot de passe !';
        $mail->Body = '';
        $mail->AltBody = 'Votre mode de passe: 0Dzq59c92c';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
});

Route::get('/user/{id}/profiles', function (int $id) {
    return "Salut $id !";
});
Route::get('/user/{id}/settings', [UserController::class, 'settings']);
Route::init();
