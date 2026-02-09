<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("csrf.php");
require_once("database.php");

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
    log_error('CSRF Validation Failed', 'Login form CSRF token mismatch');
    header("Location: login.php?error=csrf");
    exit();
}

if (isset($_POST['submit'])) {
    // Validate input
    $username = validate_string($_POST['email'] ?? '', 100);
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        log_error('Input Validation Failed', 'Login form missing credentials');
        header("Location: login.php?error=invalid");
        exit();
    }
    
    // Query user with prepared statement
    $user = query_row(
        "SELECT id, password, user_type FROM users WHERE username = ? OR email = ?",
        array($username, $username)
    );
    
    if (!$user) {
        log_action('login_attempt_failed', "Failed login attempt for: $username (user not found)");
        header("Location: login.php?error=notfound");
        exit();
    }
    
    // Verify password using bcrypt
    if (!password_verify($password, $user['password'])) {
        log_action('login_attempt_failed', "Failed login attempt for: $username (wrong password)");
        header("Location: login.php?error=wrongpass");
        exit();
    }
    
    // Successful login
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['user_type'];
    
    log_action('user_login', "User login successful: $username (ID: {$user['id']})");
    
    // Redirect based on user type
    if ($user['user_type'] === 'admin') {
        header("Location: users.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>
