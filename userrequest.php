<?php
// session_start(); 
include("database.php");
// $user_id = $_SESSION['id'];
 if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $a = $_POST['a'];
    $mission = $_POST['mission'];
    $sql = "SELECT * FROM request where user_id = '$a'";
    $result = mysqli_query($dbh, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        header("Location: index.php?user=request");
    }else {
        $sql = "SELECT * FROM users where id = '$a'";
        $result = mysqli_query($dbh, $sql);
        $resultCheck = mysqli_num_rows($result);
        $rows = mysqli_fetch_assoc($result);
        $user_type = $rows['user_type'];
        $username = $rows['Full_name'];
        if ($user_type == "admin") {
            header("Location: index.php?user=admin");
        }else {
            $sql = "SELECT * FROM vechicle where vech_id = '$id'";
        $result = mysqli_query($dbh, $sql);
        $resultCheck = mysqli_num_rows($result);
        $umm = mysqli_fetch_assoc($result);
        $vec_id  = $umm['vech_id'];
        $vech_name  = $umm['vech_name'];
        $vech_color  = $umm['vech_color'];
        $category  = $umm['category']; 
        $status = "Pending";
        $sql = "INSERT INTO request(user_id, username, vech_id, vech_name, vech_col, vech_cat, mission, status) VALUES('$a','$username','$vec_id','$vech_name','$vech_color','$category','$mission', '$status')";
                                        mysqli_query($dbh, $sql);
                                        header("Location: index.php?user=success");
        
        }
        
        
    }
}