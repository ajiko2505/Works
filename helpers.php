<?php
/**
 * AMVRS ARMED - Helper Functions
 * Input validation, sanitization, prepared statements, and logging
 */

// ============================================================================
// INPUT VALIDATION & SANITIZATION
// ============================================================================

/**
 * Validate and sanitize string input
 * @param string $input Input to validate
 * @param int $maxlen Maximum length (0 = unlimited)
 * @return string Sanitized string or empty if invalid
 */
function validate_string($input, $maxlen = 255) {
    if (!is_string($input)) return '';
    $input = trim($input);
    if ($maxlen > 0 && strlen($input) > $maxlen) return '';
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 * @param string $email Email to validate
 * @return string Valid email or empty
 */
function validate_email($email) {
    $email = trim($email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }
    return '';
}

/**
 * Validate integer
 * @param mixed $input Value to validate
 * @return int Valid integer or 0
 */
function validate_int($input) {
    $val = filter_var($input, FILTER_VALIDATE_INT);
    return ($val !== false) ? (int)$val : 0;
}

/**
 * Validate username (alphanumeric, underscore, hyphen; 3-20 chars)
 * @param string $username Username to validate
 * @return string Valid username or empty
 */
function validate_username($username) {
    $username = trim($username);
    if (preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username)) {
        return $username;
    }
    return '';
}

/**
 * Validate password (min 8 chars, at least 1 upper, 1 lower, 1 digit)
 * @param string $password Password to validate
 * @return bool True if valid, false otherwise
 */
function validate_password($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    return true;
}

/**
 * Validate phone number (allows + - () spaces, 10-20 chars)
 * @param string $phone Phone number
 * @return string Valid phone or empty
 */
function validate_phone($phone) {
    $phone = trim($phone);
    if (preg_match('/^[+\-\(\)\s0-9]{10,20}$/', $phone)) {
        return $phone;
    }
    return '';
}

/**
 * Sanitize for SQL (use with prepared statements only!)
 * @deprecated Use prepared statements instead
 * @param string $input
 * @return string Escaped string
 */
function sanitize_sql($input) {
    global $dbh;
    if (!isset($dbh)) return addslashes($input);
    return mysqli_real_escape_string($dbh, $input);
}

// ============================================================================
// PREPARED STATEMENT WRAPPERS
// ============================================================================

/**
 * Execute prepared statement and return result
 * @param string $sql SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @return mysqli_result|false Result set or false on error
 */
function query($sql, $params = array()) {
    global $dbh;
    
    $stmt = $dbh->prepare($sql);
    if (!$stmt) {
        log_error('SQL Prepare Error', $sql, $dbh->error);
        return false;
    }
    
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        log_error('SQL Execute Error', $sql, $stmt->error);
        return false;
    }
    
    return $stmt->get_result();
}

/**
 * Execute statement and return first row
 * @param string $sql SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @return array|null Associative array or null
 */
function query_row($sql, $params = array()) {
    $result = query($sql, $params);
    if (!$result) return null;
    return $result->fetch_assoc();
}

/**
 * Execute statement and return all rows
 * @param string $sql SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @return array Array of associative arrays
 */
function query_all($sql, $params = array()) {
    $result = query($sql, $params);
    if (!$result) return array();
    
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

/**
 * Count rows matching query
 * @param string $sql SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @return int Row count
 */
function query_count($sql, $params = array()) {
    $result = query($sql, $params);
    if (!$result) return 0;
    return $result->num_rows;
}

/**
 * Execute INSERT/UPDATE/DELETE and return number of affected rows
 * @param string $sql SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @return int|false Affected rows or false on error
 */
function execute($sql, $params = array()) {
    global $dbh;
    
    $stmt = $dbh->prepare($sql);
    if (!$stmt) {
        log_error('SQL Prepare Error', $sql, $dbh->error);
        return false;
    }
    
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        log_error('SQL Execute Error', $sql, $stmt->error);
        return false;
    }
    
    return $stmt->affected_rows;
}

/**
 * Get last inserted ID
 * @return int|false Last insert ID or false
 */
function get_insert_id() {
    global $dbh;
    return $dbh->insert_id ?? false;
}

// ============================================================================
// LOGGING
// ============================================================================

/**
 * Log an error to file and optional display
 * @param string $type Error type (e.g., 'SQL Error')
 * @param string $message Error message
 * @param string $detail Optional detail (SQL, stack trace, etc.)
 */
function log_error($type = '', $message = '', $detail = '') {
    $logfile = __DIR__ . '/logs/errors.log';
    $dir = dirname($logfile);
    
    // Ensure logs directory exists
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'SYSTEM';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    
    $log_entry = "[{$timestamp}] [{$user_id}] [{$ip}] {$type}: {$message}";
    if (!empty($detail)) {
        $log_entry .= " | Detail: " . substr($detail, 0, 200);
    }
    $log_entry .= "\n";
    
    @file_put_contents($logfile, $log_entry, FILE_APPEND);
    
    // In development, optionally log to syslog
    if (getenv('APP_ENV') === 'development') {
        error_log($log_entry);
    }
}

/**
 * Log an action (audit trail)
 * @param string $action Action performed (e.g., 'user_login', 'request_created')
 * @param string $details Optional details
 */
function log_action($action = '', $details = '') {
    $logfile = __DIR__ . '/logs/audit.log';
    $dir = dirname($logfile);
    
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'SYSTEM';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    
    $log_entry = "[{$timestamp}] User:{$user_id} IP:{$ip} Action:{$action}";
    if (!empty($details)) {
        $log_entry .= " Details:" . substr($details, 0, 100);
    }
    $log_entry .= "\n";
    
    @file_put_contents($logfile, $log_entry, FILE_APPEND);
}

// ============================================================================
// SECURITY HELPERS
// ============================================================================

/**
 * Check if user is logged in
 * @return bool True if logged in, false otherwise
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check user role
 * @param string $role Role to check (e.g., 'admin', 'user')
 * @return bool True if user has role
 */
function check_role($role) {
    if (!is_logged_in()) return false;
    $user_role = $_SESSION['user_type'] ?? '';
    return $user_role === $role;
}

/**
 * Require login (redirect if not authenticated)
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Require specific role (redirect if not authorized)
 * @param string $role Required role
 */
function require_role($role) {
    require_login();
    if (!check_role($role)) {
        header('Location: index.php?error=unauthorized');
        exit;
    }
}

/**
 * Set flash message
 * @param string $type Type of message ('success', 'error', 'warning', 'info')
 * @param string $message Message text
 */
function set_flash($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = array();
    }
    $_SESSION['flash'][] = array('type' => $type, 'message' => $message);
}

/**
 * Get and clear flash messages
 * @return array Messages
 */
function get_flash() {
    $messages = $_SESSION['flash'] ?? array();
    unset($_SESSION['flash']);
    return $messages;
}

/**
 * Display flash messages as HTML
 * @return string HTML div elements
 */
function display_flash() {
    $html = '';
    foreach (get_flash() as $msg) {
        $type = validate_string($msg['type']);
        $text = validate_string($msg['message']);
        $class = 'alert-info';
        if ($type === 'success') $class = 'alert-success';
        elseif ($type === 'error') $class = 'alert-danger';
        elseif ($type === 'warning') $class = 'alert-warning';
        
        $html .= "<div class='alert {$class} alert-dismissible fade show' role='alert'>";
        $html .= "{$text}";
        $html .= "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
        $html .= "</div>";
    }
    return $html;
}

// ============================================================================
// OTHER UTILITIES
// ============================================================================

/**
 * Safe redirect
 * @param string $url URL to redirect to (must be relative or same domain)
 */
function safe_redirect($url = 'index.php') {
    // Only allow relative URLs or same domain
    if (!preg_match('|^https?://|', $url)) {
        // Relative URL - safe
        header('Location: ' . $url);
        exit;
    } else {
        // Absolute URL - check domain
        $host = parse_url($url, PHP_URL_HOST);
        if ($host === $_SERVER['HTTP_HOST']) {
            header('Location: ' . $url);
            exit;
        }
    }
    // Fallback to home page
    header('Location: index.php');
    exit;
}

/**
 * Get or default session value
 * @param string $key Session key
 * @param mixed $default Default value if not set
 * @return mixed Session value or default
 */
function session_get($key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

/**
 * Set session value
 * @param string $key Session key
 * @param mixed $value Value to set
 */
function session_set($key, $value) {
    $_SESSION[$key] = $value;
}

?>
