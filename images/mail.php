
<?php

use PHPMailer\PHPMailer\PHPMailer;

// // Use SMTP
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
// require 'PHPMailer\src\Exception.php';
// require 'PHPMailer\src\PHPMailer.php';
// require 'PHPMailer\src\SMTP.php';
// use PHPMailer\PHPMailer\PHPMailer;

// Use SMTP
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once ('PHPMailer/src/Exception.php');
include_once ('PHPMailer/src/PHPMailer.php');
include_once ('PHPMailer/src/SMTP.php');

// Sender and Email information
$senderEmail='paulsaidu2011@gmail.com';
$senderName='Admin';
$replyTo='paulsaidu2011@gmail.com';
$subJect='Vechicle Request Notification';
$messageLetter='Hello, on 2fa';

// Target Email address
$emailTarget='paulsaidu46@gmail.com';

// Buiding the email object attributes
$mail = new PHPMailer;

// SMTP Configuration

$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
$mail->isSMTP(true); //Send using SMTP
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'paulsaidu2011@gmail.com'; // SMTP username
$mail->Password = 'partnfodgbwttlqi'; // The Google Application password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
$mail->Port = 587; //TCP port to connect to; 
$mail->SMTPKeepAlive = true;

// End Of SMTP Configuration


$mail->setFrom($senderEmail,$senderName);
$mail->addReplyTo($replyTo);
$mail->addAddress($emailTarget);
$mail->Subject = trim($subJect);
$mail->Body = $messageLetter;

// The Html Message Type, so message body can contain html tags.
$mail->IsHTML(true);

// Clean Body
$mail->AltBody =strip_tags($messageLetter);
$mail->CharSet = 'UTF-8';
$mail->Encoding = '8bit';

// Fire your Email.
if (!$mail->send()) {
echo '[Error:]'.htmlspecialchars($mail->ErrorInfo);
}
else {
echo '[Status:] Sent';
// header("Location: myrequest.php?req=msg");
}

?>

