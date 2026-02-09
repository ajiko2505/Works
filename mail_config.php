<?php
// Centralized mail configuration using environment variables
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($k !== '') {
            putenv("$k=$v");
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
        }
    }
}

$MAIL_HOST = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
$MAIL_USER = getenv('MAIL_USER') ?: '';
$MAIL_PASS = getenv('MAIL_PASS') ?: '';
$MAIL_PORT = getenv('MAIL_PORT') ?: 587;
$MAIL_ENCRYPTION = getenv('MAIL_ENCRYPTION') ?: 'tls';
$MAIL_FROM = getenv('MAIL_FROM') ?: $MAIL_USER;
$MAIL_FROM_NAME = getenv('MAIL_FROM_NAME') ?: 'AMVRS Admin';
$MAIL_SMTPDEBUG = getenv('MAIL_SMTPDEBUG') ?: 0;

// Helper to apply config to a PHPMailer instance
function apply_mail_config($mail) {
    global $MAIL_HOST, $MAIL_USER, $MAIL_PASS, $MAIL_PORT, $MAIL_ENCRYPTION, $MAIL_SMTPDEBUG;
    $mail->SMTPDebug = (int) $MAIL_SMTPDEBUG;
    $mail->isSMTP(true);
    $mail->Host = $MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = $MAIL_USER;
    $mail->Password = $MAIL_PASS;
    if (strtolower($MAIL_ENCRYPTION) === 'ssl') {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    }
    $mail->Port = (int) $MAIL_PORT;
    $mail->SMTPKeepAlive = true;
}
