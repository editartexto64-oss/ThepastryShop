<?php

use PHPMailer\PHPMailer\PHPMailer;

function mailer()
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAIL_USER'];
    $mail->Password   = $_ENV['MAIL_PASS'];
    $mail->SMTPSecure = $_ENV['MAIL_SECURE'];
    $mail->Port       = $_ENV['MAIL_PORT'];

    $mail->setFrom($_ENV['MAIL_FROM'], "ThePastryShop Perú");

    return $mail;
}
