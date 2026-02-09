<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("database.php");

// Require login
require_login();

if (isset($_POST['submit'])) {
    // Get session user_id
    $user_id = validate_int($_SESSION['user_id'] ?? 0);
    if ($user_id <= 0) {
        header("Location: login.php");
        exit();
    }
    
    // Validate input
    $vec_id = validate_int($_POST['vec_id'] ?? 0);
    $mission = validate_string($_POST['mission'] ?? '', 500);
    
    if ($vec_id <= 0 || empty($mission)) {
        log_error('Input Validation Failed', 'Vehicle request form missing required fields');
        header("Location: request.php?error=invalid");
        exit();
    }
    
    // Get user info
    $user = query_row("SELECT username, user_type FROM users WHERE id = ?", array($user_id));
    if (!$user) {
        header("Location: login.php");
        exit();
    }
    
    // Prevent admin from making requests
    if ($user['user_type'] === 'admin') {
        log_action('request_blocked_admin', "Admin user ({$user['username']}) attempted to create request");
        header("Location: index.php?error=admin");
        exit();
    }
    
    // Check for existing pending requests from this user
    $pending = query_count(
        "SELECT id FROM request WHERE user_id = ? AND status IN ('pending', 'processing')",
        array($user_id)
    );
    
    if ($pending > 0) {
        header("Location: index.php?user=request");
        exit();
    }
    
    // Get vehicle info
    $vehicle = query_row("SELECT vech_name, vech_color FROM vechicle WHERE vech_id = ?", array($vec_id));
    if (!$vehicle) {
        header("Location: request.php?error=novehicle");
        exit();
    }
    
    // Create request using prepared statement
    $status = "pending";
    $result = execute(
        "INSERT INTO request(user_id, username, vech_id, vech_name, vech_col, mission, status) VALUES(?, ?, ?, ?, ?, ?, ?)",
        array($user_id, $user['username'], $vec_id, $vehicle['vech_name'], $vehicle['vech_color'], $mission, $status)
    );
    
    if ($result !== false && $result > 0) {
        log_action('vehicle_request_created', "Request created for vehicle: $vec_id by user: {$user['username']}");
        set_flash('success', "Vehicle request submitted successfully!");
        header("Location: index.php?user=accepted");
        exit();
    } else {
        log_error('Database Error', 'Failed to create vehicle request', "user_id: $user_id, vehicle_id: $vec_id");
        header("Location: request.php?error=db");
        exit();
    }
}
?>
