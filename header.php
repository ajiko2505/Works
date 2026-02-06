<?php
session_start(); 
?>
 <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>AMVRS</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="css/index_style.css" />
        <link rel="stylesheet" type="text/css" href="css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="css/form_styles.css" />
        <link rel="stylesheet" type="text/css" href="css/register_style.css" />
        <script src="jquery.min.js"></script>
    </head>
    <body>
<?php
    
//   echo  $_SESSION['user_type'];
  if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] =="user") {
        echo '
   
    <nav class="navbar navbar-fixed-top navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid px-5">
                <P class="navbar-brand d-flex align-items-center" href="#">
                    <img src="naf.png" alt="Logo" width="60px" height="60px" class="me-2" />
                    <span style="font-weight:700;font-size:1.1rem;letter-spacing:1px;">AUTOMATED MILITARY VEHICLE REQUEST SYSTEM</span>
                </P>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="myrequest.php">My Request</a></li>
                        <li class="nav-item"><a class="nav-link active" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link active" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    ';
    }elseif ($_SESSION['user_type'] =="admin") {
        echo '
        
   
    <nav class="navbar navbar-fixed-top navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid px-5">
                <P class="navbar-brand d-flex align-items-center" href="#!">
                    <img src="naf.png" alt="Logo" width="60px" height="60px" class="me-2" />
                    <span style="font-weight:700;font-size:1.1rem;letter-spacing:1px;">AUTOMATED MILITARY VEHICLE REQUEST SYSTEM</span>
                </P>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="register.php">Add vechicle</a></li>
                        <li class="nav-item"><a class="nav-link active" href="request.php">Request</a></li>
                        <li class="nav-item"><a class="nav-link active" href="approve.php">Approved</a></li>
                        <li class="nav-item"><a class="nav-link active" href="users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link active" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    ';
    }
  }else {
    echo '
   
    <nav class="navbar navbar-fixed-top navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid px-5">
                <P class="navbar-brand d-flex align-items-center" href="#!">
                    <img src="naf.png" alt="Logo" width="60px" height="60px" class="me-2" />
                    <span style="font-weight:700;font-size:1.1rem;letter-spacing:1px;">AUTOMATED MILITARY VEHICLE REQUEST SYSTEM</span>
                </P>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="signup.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    ';
  }