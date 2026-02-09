<?php
// Simple CSRF token helper
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf($token) {
    if (empty($token)) return false;
    if (empty($_SESSION['csrf_token'])) return false;
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    // Regenerate token after validation to mitigate replay
    unset($_SESSION['csrf_token']);
    return $valid;
}
