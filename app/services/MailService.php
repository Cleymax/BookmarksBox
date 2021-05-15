<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class MailService
 * @package App\Services
 * @sse PHPMailer
 * @author Clément PERRIN <clement.perrin@etu.univ-smb.fr>
 */
class MailService
{

    /**
     * Send a mail with a template.
     * The templates are saved in "resources/mail" folder.
     *
     * @param string $template The name of file (template)
     * @param string $mail The recipient's email
     * @param string $subject The subject
     * @param array $data The data
     * @return bool if is the success
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function send_template(string $template, string $mail, string $subject, array $data = []): bool
    {
        // extract the data in several variables.
        extract($data);
        // start the buffer for the content of the mail.
        ob_start();
        include ROOT_PATH . '/../resources/mail/' . $template . '.php';
        // clean and get the buffer.
        $content = ob_get_clean();
        // start a new buffer for the template.
        ob_start();
        include ROOT_PATH . '/../resources/mail/default.template.php';
        // clean and get the body of the mail
        $body = ob_get_clean();
        // send the mail.
        return self::send($mail, $subject, $body);
    }

    /**
     * Send a email with PHPMailer or mail function.
     * See $_ENV['USE_MAIL_FUNCTION'].
     *
     * @param string $to The recipient's email
     * @param string $subject The subject
     * @param string $body The body
     * @return bool if is the success
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function send(string $to, string $subject, string $body): bool
    {
        if (boolval(getenv('USE_MAIL_FUNCTION'))) {
            // send a mail with mail function.
            return mail($to, $subject, $body, implode('\r\n', [
                'From: ' . $_ENV['SMTP_FROM'],
                'Reply-To: ' . $_ENV['SMTP_REPLY_TO'],
                'Content-type: text/html; charset=UTF-8',
                'MIME-Version: 1.0'
            ]));
        } else {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = $_ENV['MODE'] == 'dev' ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];

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
            return $mail->send();
        }
    }
}
