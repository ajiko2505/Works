# Security Guidelines - AMVRS ARMED

Important security considerations for development and deployment.

## Table of Contents
- [Current Security Features](#current-security-features)
- [Known Vulnerabilities](#known-vulnerabilities)
- [Security Best Practices](#security-best-practices)
- [Deployment Security](#deployment-security)
- [Data Protection](#data-protection)
- [Security Checklist](#security-checklist)

## Current Security Features

### Implemented
✅ Password hashing (MD5 - see note below)  
✅ Session-based authentication  
✅ Role-based access control  
✅ Basic input validation  
✅ MySQL connection error handling  

## Known Vulnerabilities

⚠️ **CRITICAL ISSUES**

### 1. MD5 Password Hashing (Use bcrypt/password_hash instead)
**Current**: `MD5('password')`  
**Recommended**: `password_hash($password, PASSWORD_BCRYPT)`

```php
// ✅ CORRECT
$hashed = password_hash($password, PASSWORD_BCRYPT);
if(password_verify($input_password, $hashed)) {
    // Login successful
}

// ❌ WRONG
$hashed = MD5($password);
```

### 2. SQL Injection Risk
**Current**: Direct string concatenation in queries  
**Recommended**: Prepared statements

```php
// ❌ VULNERABLE
$query = "SELECT * FROM users WHERE username = '$username'";

// ✅ SECURE
$stmt = $dbh->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
```

### 3. Missing CSRF Protection
**Issue**: No CSRF tokens on forms  
**Fix**: Add token generation and validation

```php
// Session start
session_start();

// Generate token
if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// In form
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

// Validate on submission
if($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
```

### 4. No Input Validation
**Issue**: User inputs not sanitized  
**Fix**: Validate and escape

```php
// ✅ VALIDATE INPUT
$username = trim($_POST['username'] ?? '');
if(!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    die('Invalid username format');
}

// ✅ ESCAPE OUTPUT
echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
```

### 5. Weak Session Security
**Issue**: Sessions not secure from hijacking  
**Fix**: Implement security measures

```php
// ✅ SECURE SESSION CONFIG
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,  // HTTPS only
    'httponly' => true, // No JavaScript access
    'samesite' => 'Strict'
]);

session_start();

// Regenerate session ID after login
session_regenerate_id(true);
```

### 6. No Rate Limiting
**Issue**: Vulnerable to brute force attacks  
**Fix**: Implement rate limiting

```php
// Simple rate limiting
$max_attempts = 5;
$timeout = 900; // 15 minutes

if(!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['login_timeout'] = time();
}

if(time() - $_SESSION['login_timeout'] > $timeout) {
    $_SESSION['login_attempts'] = 0;
}

if($_SESSION['login_attempts'] >= $max_attempts) {
    die('Too many login attempts. Try again later.');
}
```

## Security Best Practices

### 1. Authentication
```php
// ✅ DO
- Use bcrypt for password hashing
- Force HTTPS only
- Implement session timeouts
- Regenerate session IDs after login
- Log failed login attempts

// ❌ DON'T
- Store plain text passwords
- Use MD5 for passwords
- Trust user input
- Reuse session IDs
```

### 2. Authorization
```php
// ✅ Verify user role on every protected page
function checkRole($required_role) {
    if($_SESSION['user_role'] !== $required_role) {
        header('Location: /login.php');
        exit;
    }
}

// ❌ Don't assume role from browser
```

### 3. Input Handling
```php
// ✅ Always validate and escape
$user_input = filter_var($_POST['data'], FILTER_SANITIZE_STRING);
$safe_output = htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// ❌ Never use raw input
echo $_POST['data'];
```

### 4. Database Queries
```php
// ✅ Use prepared statements
$stmt = $dbh->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

// ❌ Don't concatenate user input
$result = mysqli_query($dbh, "SELECT * FROM users WHERE id = $user_id");
```

### 5. Error Handling
```php
// ✅ Log errors, show generic message
error_log($error_details);
echo "An error occurred. Please try again.";

// ❌ Don't expose sensitive information
echo "Database Error: " . mysqli_error($dbh);
```

### 6. File Permissions
```bash
# ✅ Proper permissions
chmod 755 . (directories)
chmod 644 *.php (files)
chmod 700 config/ (sensitive files)

# ❌ Too permissive
chmod 777 (world writable)
```

## Deployment Security

### HTTPS/SSL
```php
// Force HTTPS
if($_SERVER['HTTPS'] !== 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}
```

### Security Headers
```php
// Add security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Security-Policy: default-src \'self\'');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

### Database User Privileges
```sql
-- Create limited database user
CREATE USER 'amvrs_user'@'localhost' IDENTIFIED BY 'strong_password_here';

-- Grant only necessary privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON amvrss.* TO 'amvrs_user'@'localhost';

-- Never use root for application
```

## Data Protection

### Sensitive Data
- ✅ Hash passwords with bcrypt
- ✅ Encrypt credit card data
- ✅ Use HTTPS for all data transmission
- ✅ Store sensitive data in environment variables

### GDPR Compliance
```php
// ✅ Data deletion on request
DELETE FROM users WHERE user_id = ?;
DELETE FROM requests WHERE user_id = ?;

// ✅ Data export functionality
$user_data = getUserData($user_id);
echo json_encode($user_data);
```

### Backup Security
- Store backups encrypted
- Keep backups off-server
- Test restoration regularly
- Document backup procedures

## Security Checklist

### Before Going Live
- [ ] Change all default passwords
- [ ] Update MD5 to bcrypt for password hashing
- [ ] Implement CSRF protection
- [ ] Add input validation to all forms
- [ ] Enable HTTPS/SSL
- [ ] Configure security headers
- [ ] Implement rate limiting
- [ ] Add logging and monitoring
- [ ] Remove debug/test files
- [ ] Update PHP to latest secure version
- [ ] Configure firewall rules
- [ ] Set up regular backups
- [ ] Create incident response plan
- [ ] Conduct security audit
- [ ] Document all security measures

### Regular Maintenance
- [ ] Update PHP and dependencies monthly
- [ ] Review access logs weekly
- [ ] Test backup restoration monthly
- [ ] Update security headers annually
- [ ] Audit user access and permissions quarterly
- [ ] Perform penetration testing annually

## Security Resources

### OWASP Top 10
1. Broken Access Control
2. Cryptographic Failures
3. Injection
4. Insecure Design
5. Security Misconfiguration
6. Vulnerable Components
7. Authentication Failures
8. Software and Data Integrity Failures
9. Logging and Monitoring Failures
10. Server-Side Request Forgery

### Tools
- **OWASP ZAP** - Security scanning
- **SQLMap** - SQL injection testing
- **Burp Suite** - Web app security testing
- **SSL Labs** - SSL configuration checker

## Reporting Security Issues

🔒 **Do not create public GitHub issues for security vulnerabilities**

Please email security concerns to: (add contact info)

---

**Last Updated**: February 6, 2026  
**Version**: 1.0.0  
**Status**: Review and implement recommended fixes before production
