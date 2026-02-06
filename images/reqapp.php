<?php
session_start();
 
// include("header.php");
include("database.php");
use PHPMailer\PHPMailer\PHPMailer;

// Use SMTP
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer\src\Exception.php';
require 'PHPMailer\src\PHPMailer.php';
require 'PHPMailer\src\SMTP.php';

 if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_GET['a'];
    $da =  Date('y-m-d', strtotime('+1 day')) ;
    $sql = "UPDATE request SET status = 'approved', return_date ='$da' WHERE vech_id = '$id'";
    $result = mysqli_query($dbh, $sql);
    $sql = "UPDATE vechicle SET status = 'allocated' WHERE vech_id = '$id'";
    $result = mysqli_query($dbh, $sql);
    $sql = "DELETE FROM request WHERE user_id != '$user_id' AND vech_id='$id'";
    $result = mysqli_query($dbh, $sql);
    $sql = "SELECT * FROM users WHERE id ='$user_id'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        if ($rows = mysqli_fetch_assoc($result)) {
         $mail = $rows['email'];
         $name = $rows['Full_name'];
         $sql = "INSERT INTO vech_allocated (user_id, name, email, due_date) VALUES('$user_id','$name','$mail', '$da')";
         mysqli_query($dbh, $sql);
         // Sender and Email information
$senderEmail='amvrs2023@gmail.com';
$senderName='Admin';
$replyTo='amvrs2023@gmail.com';
$subJect='Vechicle Request Notification';
$messageLetter='Hello '.$name. ', your vechicle request have been ';

// Target Email address
$emailTarget=$mail;

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
header("Location: request.php?req=msg");
}
else {
// echo '[Status:] Sent';
header("Location: request.php?req=msg");
}
         // header("Location: request.php?reg=success");
        }
      }
    
    
 }
                    ?>
                            
                
              
                
               
                
            