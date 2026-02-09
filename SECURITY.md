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
✅ Password hashing (bcrypt via `password_hash()`)  
✅ Session-based authentication with secure cookies  
✅ Role-based access control  
✅ **Input validation helpers** (email, username, string, password, phone)  
✅ **Prepared statements wrapper** functions  
✅ **CSRF protection** on signup, login, and handlers  
✅ **Comprehensive logging** (errors, audit trail)  
✅ **Security headers** (CSP, X-Frame-Options, etc)  
✅ MySQL connection error handling  
✅ Flash message system for user feedback  
✅ **PHPUnit test suite** for validation functions

## New Security Helpers (`helpers.php`)

### Input Validation Functions
```php
// Email validation
validate_email('user@example.com');  // Returns email or empty

// Username validation (alphanumeric, 3-20 chars)
validate_username('john_doe');  // Returns username or empty

// Password validation (8+ chars, upper, lower, digit)
validate_password('SecurePass123');  // Returns bool

// String sanitization
validate_string($_POST['name'], 100);  // Max 100 chars, HTML-escaped

// Integer validation
validate_int($_GET['id']);  // Returns int or 0

// Phone validation
validate_phone('+1 (555) 123-4567');  // Returns phone or empty
```

### Prepared Statement Helpers
```php
// SELECT single row
$user = query_row("SELECT * FROM users WHERE id = ?", array($id));

// SELECT all rows
$users = query_all("SELECT * FROM users WHERE role = ?", array('admin'));

// COUNT rows
$count = query_count("SELECT id FROM requests WHERE status = ?", array('pending'));

// INSERT/UPDATE/DELETE
$affected = execute(
    "INSERT INTO logs(user_id, action) VALUES(?, ?)",
    array($user_id, 'login')
);
```

### Logging Functions
```php
// Log error
log_error('Database Error', 'Connection failed', mysqli_error($dbh));

// Log action (audit trail)
log_action('user_login', "User $username logged in from $ip");

// Get flash messages
set_flash('success', 'Operation completed!');
$messages = get_flash();  // Returns array, clears session
```

### Security Helpers
```php
// Check if user is logged in
if (is_logged_in()) { ... }

// Check user role
if (check_role('admin')) { ... }

// Require authentication (exit if not logged in)
require_login();

// Require specific role (exit if unauthorized)
require_role('admin');

// Safe redirect (prevents open redirect)
safe_redirect('users.php');
```

## Known Vulnerabilities (Fixed)

### ✅ FIXED: SQL Injection Risk
**Before**: Direct string concatenation  
**After**: All critical handlers now use prepared statements
- `usersig.php` - User registration
- `userlog.php` - User login
- `userreg.php` - Vehicle registration
- `userrequest.php` - Request creation

### ✅ FIXED: Missing CSRF Protection
**Status**: CSRF tokens added to signup, login forms, and all POST handlers  
- `signup.php` - Registration form
- `login.php` - Login form
- All POST handlers validate tokens before processing

### ✅ NEW: Input Validation
**Status**: Comprehensive validation helpers in `helpers.php`
- Email, username, password, phone validation
- String sanitization with max length enforcement
- Integer validation for IDs and counts

### ✅ NEW: Security Logging
**Status**: Error and audit logging implemented
- All database errors logged to `logs/errors.log`
- User actions logged to `logs/audit.log`
- Login attempts (success/failure) logged
- Form validation failures logged

### ✅ NEW: Security Headers
**Status**: Security headers configured in `security_config.php`
- Content-Security-Policy (CSP) - prevent XSS
- X-Frame-Options: SAMEORIGIN - prevent clickjacking
- X-Content-Type-Options: nosniff - prevent MIME sniffing
- Strict-Transport-Security (HSTS) - enforce HTTPS
- Referrer-Policy - control referrer info

## Remaining Issues to Address

⚠️ **MEDIUM PRIORITY**

### SQL Injection in Read Queries
Some read-only queries in `index.php`, `users.php`, etc still use string concatenation:
```php
// Should be updated to use query_all() / query_row()
$sql = "SELECT * FROM request where user_id = '$a'";  // VULNERABLE
```
**Fix**: Update data retrieval queries to use the query helpers in `helpers.php`

### Missing CSRF on Form Pages
Some forms still lack CSRF tokens:
- `request.php` - Vehicle request form
- `register.php` - Vehicle registration form
- `profile.php` / `profup.php` - Profile update forms

**Fix**: Add `<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">` to all forms

### Session Fixation Risk
Sessions should be regenerated on login/logout:
```php
// After successful login
session_regenerate_id(true);

// On logout
session_destroy();
```

### No Rate Limiting
No protection against brute force attacks (login, API endpoints)
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
