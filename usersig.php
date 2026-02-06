<?php
include("database.php");
if (isset($_POST['submit'])) {
    $mail = $_POST['mail'];
    $fname = $_POST['fname'];
    $rank = $_POST['rank'];
    $snumber = $_POST['snumber'];
    $uname = $_POST['uname'];
    $pass = $_POST['password'];
    $c_pass = $_POST['c_password'];
    $sqlChk = "SELECT * FROM users WHERE username='$uname' || email ='$mail' || snumber='$snumber'";
                    $result = mysqli_query($dbh, $sqlChk);
                                $resultCheck = mysqli_num_rows($result);
                                if ($resultCheck > 0) {
                                    header("Location: signup.php?usser=exist");
                                }else {
                                    if ($pass !== $c_pass) {
                                        header("Location: signup.php?usser=pwdmatch");
                                        # code...
                                    }else{
                                        $passhash = password_hash($pass, PASSWORD_DEFAULT);
                                        $status="user";
                                        $sql = "INSERT INTO users(Full_name, rank, snumber, email, username, password, user_type) VALUES('$fname','$rank','$snumber','$mail','$uname','$passhash', '$status')";
                                        mysqli_query($dbh, $sql);
                                        header("Location: login.php?reg=success");
                                }
                                }
}