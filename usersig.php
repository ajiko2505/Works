<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("csrf.php");
require_once("database.php");

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
    log_error('CSRF Validation Failed', 'Registration form CSRF token mismatch');
    header("Location: signup.php?usser=csrf");
    exit();
}

if (isset($_POST['submit'])) {
    // Validate and sanitize input
    $mail = validate_email($_POST['mail'] ?? '');
    $fname = validate_string($_POST['fname'] ?? '', 100);
    $rank = validate_string($_POST['rank'] ?? '', 50);
    $snumber = validate_string($_POST['snumber'] ?? '', 50);
    $uname = validate_username($_POST['uname'] ?? '');
    $pass = $_POST['password'] ?? '';
    $c_pass = $_POST['c_password'] ?? '';
    
    // Check if all required fields are present and valid
    if (empty($mail) || empty($fname) || empty($rank) || empty($snumber) || empty($uname) || empty($pass)) {
        log_error('Input Validation Failed', 'Registration form has missing/invalid fields');
        header("Location: signup.php?usser=invalid");
        exit();
    }
    
    // Validate password strength
    if (!validate_password($pass)) {
        log_error('Password Validation Failed', "Password does not meet security requirements for user: $uname");
        header("Location: signup.php?usser=pwdweak");
        exit();
    }
    
    // Check password match
    if ($pass !== $c_pass) {
        header("Location: signup.php?usser=pwdmatch");
        exit();
    }
    
    // Check if user already exists (using prepared statement)
    $check = query_count("SELECT id FROM users WHERE username = ? OR email = ? OR snumber = ?", 
                         array($uname, $mail, $snumber));
    
    if ($check > 0) {
        log_action('signup_attempt_duplicate', "User attempted to register with existing username/email: $uname");
        header("Location: signup.php?usser=exist");
        exit();
    }
    
    try {
        // Hash password
        $passhash = password_hash($pass, PASSWORD_DEFAULT);
        $status = "user";
        
        // Insert new user using prepared statement
        $result = execute(
            "INSERT INTO users(Full_name, rank, snumber, email, username, password, user_type) VALUES(?, ?, ?, ?, ?, ?, ?)",
            array($fname, $rank, $snumber, $mail, $uname, $passhash, $status)
        );
        
        if ($result !== false && $result > 0) {
            log_action('user_registered', "New user registered: $uname (email: $mail)");
            header("Location: login.php?reg=success");
            exit();
        } else {
            log_error('Database Error', 'Failed to insert new user', $uname);
            header("Location: signup.php?usser=dberror");
            exit();
        }
    } catch (Exception $e) {
        log_error('Registration Exception', $e->getMessage(), $uname);
        header("Location: signup.php?usser=error");
        exit();
    }
}
?>
