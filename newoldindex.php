<?php
include("database.php");

use PHPMailer\PHPMailer\PHPMailer;

// Use SMTP
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer\src\Exception.php';
require 'PHPMailer\src\PHPMailer.php';
require 'PHPMailer\src\SMTP.php';

$sql = "SELECT * FROM vech_allocated";
$result = mysqli_query($dbh, $sql);
$resultCheck = mysqli_num_rows($result);
if ($resultCheck > 0) {
    while ($rows = mysqli_fetch_assoc($result)) {
       
            # code...
            $email = $rows['email'];
           $due_date = $rows['due_date'];
           $name = $rows['name'];
           $date = Date('Y-m-d');
           if ($date > $due_date) {
             // Sender and Email information (use mail_config.php)
            include_once __DIR__ . '/mail_config.php';
            $senderEmail = $MAIL_FROM;
            $senderName = $MAIL_FROM_NAME;
            $replyTo = $MAIL_FROM;
            $subJect = 'Vehicle Request Notification';
            $messageLetter = 'Hello ' . $name . ', your vehicle request is overdue; please return it.';

// Target Email address
$emailTarget=$email;

// Building the email object attributes
$mail = new PHPMailer;
apply_mail_config($mail);


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
// echo '[Error:]'.htmlspecialchars($mail->ErrorInfo);
header("Location: home.php?req=failed");
}
else {
// echo '[Status:] Sent';
header("Location: home.php?req=success");
}
           }    
        
    }
}else {
    header("Location: home.php");
}