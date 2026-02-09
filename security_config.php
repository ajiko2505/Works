<?php
/**
 * AMVRS ARMED - Security Configuration
 * Set security headers and initialize logging
 * This file is safe to include multiple times
 */

// Guard: only initialize once
if (defined('AMVRS_SECURITY_CONFIG_LOADED')) {
    return;
}
define('AMVRS_SECURITY_CONFIG_LOADED', true);

// Ensure session is secure (only start if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(array(
        'lifetime' => 3600,      // 1 hour
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,      // Prevent JavaScript access
        'samesite' => 'Strict'   // CSRF protection
    ));
    session_start();
}

// Set mandatory security headers (safe to set multiple times)
header('X-Content-Type-Options: nosniff');                    // Prevent MIME sniffing
header('X-Frame-Options: SAMEORIGIN');                       // Prevent clickjacking
header('X-XSS-Protection: 1; mode=block');                   // Legacy XSS protection
header('Referrer-Policy: strict-origin-when-cross-origin');  // Control referrer info
header('Permissions-Policy: geolocation=(), microphone=(), camera=()'); // Disable dangerous APIs

// Strict Content Security Policy (CSP)
// Allows Bootstrap CDN, jQuery CDN, and local resources
$csp = "default-src 'self'; ";
$csp .= "script-src 'self' https://cdn.jsdelivr.net https://code.jquery.com; ";
$csp .= "style-src 'self' https://cdn.jsdelivr.net; ";
$csp .= "img-src 'self' data: https:; ";
$csp .= "font-src 'self' https://cdn.jsdelivr.net; ";
$csp .= "connect-src 'self'; ";
$csp .= "frame-ancestors 'none'; ";
$csp .= "base-uri 'self'; ";
$csp .= "form-action 'self'";
header('Content-Security-Policy: ' . $csp);

// HTTPS redirect (if not on localhost)
if (!in_array($_SERVER['HTTP_HOST'], array('localhost', '127.0.0.1', 'localhost:8080', '127.0.0.1:8080'))) {
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Initialize logging directories
$log_dir = __DIR__ . '/logs';
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0755, true);
}

// Set error reporting
if (getenv('APP_ENV') === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', $log_dir . '/php_errors.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Disable dangerous functions
ini_set('disable_functions', 'exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source');

?>
