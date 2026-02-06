<?php
session_start();
 
// include("header.php");
include("database.php");
 if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_GET['a'];
    // echo $user_id;
    // echo $id;
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

// echo '[Status:] Sent';
header("Location: request.php?req=msgp");
}
         // header("Location: request.php?reg=success");
        }
      }
    
    
                 ?>
                            
                
              
                
               
                
            