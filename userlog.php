<?php
session_start();
include("database.php");
if (isset($_POST['submit'])) {
    $usd = $_POST['email'];
    $pwd = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username ='$usd'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck < 1) {
        header("Location: login.php?error=user");
        exit();
    }else {
        if ($rows = mysqli_fetch_assoc($result)) {
            $passCheck = password_verify($pwd, $rows['password']);
            // echo var_dump($rows);
            if ($passCheck == false) {
                header("Location: login.php?error=pass");
                exit();
            }elseif ($passCheck == true) {
                # code...
                $_SESSION['id']= $rows['id'];
                $_SESSION['user_type']= $rows['user_type'];
                if ($_SESSION['user_type']=="admin") {
                    # code...
                    header("Location: index.php?error=success");
                }else {
                    header("Location: index.php?error=success");
                    # code...
                }
            
            } 
        }
    }
}else {
    header("Location: login.php");
    exit();
}