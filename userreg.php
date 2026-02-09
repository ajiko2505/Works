<?php
/**
 * Vehicle Registration Handler
 *
 * Handles vehicle registration for users with input validation,
 * image upload processing, and database storage.
 *
 * @author AMVRS ARMED Development Team
 * @version 1.0.0
 * @package AMVRS ARMED
 */

if (session_status() === PHP_SESSION_NONE) session_start();
require_once("database.php");

// Require login
require_login();

if (isset($_POST['submit'])) {
    // Validate input
    $vech_no = validate_string($_POST['vech_no'] ?? '', 50);
    $vech_name = validate_string($_POST['vech_name'] ?? '', 100);
    $vech_col = validate_string($_POST['vech_col'] ?? '', 50);
    $vech_desc = validate_string($_POST['description'] ?? '', 500);
    $cat = validate_string($_POST['vec_cat'] ?? '', 50);
    
    // Check required fields
    if (empty($vech_no) || empty($vech_name) || empty($vech_col) || empty($cat)) {
        log_error('Input Validation Failed', 'Vehicle registration form missing required fields');
        header("Location: register.php?error=invalid");
        exit();
    }
    
    // Check if vehicle already exists
    $exists = query_count("SELECT id FROM vechicle WHERE vech_id = ?", array($vech_no));
    
    if ($exists > 0) {
        log_action('vehicle_registration_duplicate', "Attempted duplicate vehicle registration: $vech_no");
        header("Location: register.php?error=exist");
        exit();
    }
    
    // Handle file upload for vehicle image
    $image = '';
    if (isset($_FILES['vech_img']) && $_FILES['vech_img']['error'] === UPLOAD_ERR_OK) {
        $temp_file = $_FILES['vech_img']['tmp_name'];
        $filename = basename($_FILES['vech_img']['name']);
        
        // Validate file type (only images)
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $temp_file);
        finfo_close($finfo);
        
        if (in_array($mime_type, $allowed_types)) {
            // Store in images directory
            $image = 'images/' . uniqid() . '_' . hash_file('sha256', $temp_file) . '.jpg';
            move_uploaded_file($temp_file, $image);
        }
    }
    
    // Insert vehicle using prepared statement
    $status = "free";
    $result = execute(
        "INSERT INTO vechicle(vech_id, vech_name, vech_color, category, vech_img, vech_desc, status) VALUES(?, ?, ?, ?, ?, ?, ?)",
        array($vech_no, $vech_name, $vech_col, $cat, $image, $vech_desc, $status)
    );
    
    if ($result !== false && $result > 0) {
        log_action('vehicle_registered', "New vehicle registered: $vech_no ($vech_name)");
        set_flash('success', "Vehicle '$vech_name' registered successfully!");
        header("Location: register.php?success=1");
        exit();
    } else {
        log_error('Database Error', 'Failed to insert vehicle', $vech_no);
        header("Location: register.php?error=db");
        exit();
    }
}
?>
