<?php
include("header.php");
include("database.php");
$id = $_SESSION['id'];
if (isset($_POST['submit'])) {
    $mail = $_POST['mail'];
    $fname = $_POST['fname'];
    $rank = $_POST['rank'];
    $uname = $_POST['uname'];
    $snumber = $_POST['snumber'];
    $sqlChk = "SELECT * FROM users WHERE username='$mail'";
                    $result = mysqli_query($dbh, $sqlChk);
                                $resultCheck = mysqli_num_rows($result);
                                if ($resultCheck > 0) {
                                    header("Location: profile.php?usser=exist");
                                }else {
                                    
                                        // $sql = "UPDATE users SET Full_name = '$fname', rank='$rank', snumber='$snumber', email='$mail', username='$uname WHERE id='$id'";
                                        $sql = "UPDATE users SET  email='$mail', rank='$rank WHERE id='$id'";
                                        mysqli_query($dbh, $sql);
                                        header("Location: profile.php?up=success");
                                }
                                }
