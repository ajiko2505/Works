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
             // Sender and Email information
$senderEmail='amvrs2023@gmail.com';
$senderName='Admin';
$replyTo='amvrs2023@gmail.com';
$subJect='Vechicle Request Notification';
$messageLetter='Hello '.$name. ', your vechicle request have been overdue please return';

// Target Email address
$emailTarget=$email;

// Buiding the email object attributes
$mail = new PHPMailer;

// SMTP Configuration

$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
$mail->isSMTP(true); //Send using SMTP
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'amvrs2023@gmail.com'; // SMTP username
$mail->Password = 'jsohylfqgpazcggi'; // The Google Application password
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
// echo '[Error:]'.htmlspecialchars($mail->ErrorInfo);
header("Location: myrequest.php?req=msg");
}
else {
// echo '[Status:] Sent';
header("Location: myrequest.php?req=msg");
}
           }

            


            
            
            
          
        
        
    }
}