<?php

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Maakt een PHPMailer aan. Als SMTP_HOST, SMTP_USER en SMTP_PASS in config.php gevuld zijn
 * wordt via SMTP verstuurd (bijv. Gmail), anders via sendmail. Afzender is SENDER_MAIL.
 */
function createMailer(): PHPMailer
{
    $mail          = new PHPMailer();
    $mail->From    = SENDER_MAIL;
    $mail->Sender  = SENDER_MAIL;
    $mail->CharSet = 'UTF-8';

    if (smtpConfigured()) {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->Port       = defined('SMTP_PORT') ? SMTP_PORT : 587;
        $mail->SMTPSecure = $mail->Port === 465 ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
    } else {
        $mail->isSendmail();
    }

    return $mail;
}

function smtpConfigured(): bool
{
    foreach (['SMTP_HOST', 'SMTP_USER', 'SMTP_PASS'] as $setting) {
        if (!defined($setting) || constant($setting) === '') {
            return false;
        }
    }

    return true;
}
