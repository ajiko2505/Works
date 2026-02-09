<?php
/**
 * Simple test script to validate SMTP settings via mail_config.php
 * Usage: php test_mail.php
 */
require __DIR__ . '/mail_config.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

$to = getenv('TEST_MAIL_TO') ?: readline("Enter recipient email: ");
if (empty($to)) {
    echo "No recipient given, aborting.\n";
    exit(1);
}

$mail = new PHPMailer();
apply_mail_config($mail);
$mail->setFrom($MAIL_FROM, $MAIL_FROM_NAME);
$mail->addAddress($to);
$mail->Subject = 'AMVRS ARMED SMTP Test';
$mail->Body = "This is a test email from AMVRS ARMED (" . date('Y-m-d H:i:s') . ")";
$mail->AltBody = strip_tags($mail->Body);

echo "Sending test email to: $to\n";
if (!$mail->send()) {
    echo "Failed to send: " . $mail->ErrorInfo . "\n";
    exit(1);
}
echo "Email sent successfully.\n";
