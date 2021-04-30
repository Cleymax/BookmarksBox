<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailService
{

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function send(string $to, string $subject, string $body)
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = $_ENV['MODE'] == 'dev' ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'mail.clementperrin.fr';

        if ($_ENV['SMTP_AUTH']) {
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
        }
        $mail->Port = $_ENV['SMTP_PORT'] ?? 25;

        $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($to);
        $mail->addReplyTo($_ENV['SMTP_REPLY_TO']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    }
}
