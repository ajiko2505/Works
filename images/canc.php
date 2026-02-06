<?php
include("header.php");
include("database.php");
$user_id = $_SESSION['id'];
 if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM request WHERE user_id ='$user_id'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        $rows = mysqli_fetch_assoc($result);
        $status = $rows['status'];
        if ($status =="approved") {
            header("Location: myrequest.php?req=app");
        }else{
            $sql = "DELETE FROM request where vech_id = '$id'";
            $result = mysqli_query($dbh, $sql);
            $sql = "UPDATE vechicle Set status = 'free' where vech_id = '$id'";
            $result = mysqli_query($dbh, $sql);
            header("Location: myrequest.php?req=cancel");
        }
        
    }
    
 }
                    ?>
                            
                
              
                
               
                
            