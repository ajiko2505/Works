# AMVRS ARMED - Code Standards & Best Practices

This document defines the coding standards and best practices for the AMVRS ARMED project to ensure consistency, security, and maintainability across the codebase.

## Table of Contents
- [File Structure](#file-structure)
- [PHP Code Style](#php-code-style)
- [Naming Conventions](#naming-conventions)
- [Security Standards](#security-standards)
- [Database Operations](#database-operations)
- [Error Handling & Logging](#error-handling--logging)
- [Documentation](#documentation)
- [Testing](#testing)

## File Structure

### Directory Organization

```
AMVRS ARMED/
├── assets/              # Static assets (CSS, images, fonts)
│   └── css/            # Stylesheets
├── database/           # Database schema and utilities
├── tests/              # Unit tests and test utilities
├── PHPmailer/          # Email library
├── .github/workflows/  # CI/CD configuration
├── Core files
│   ├── header.php      # HTML header and navigation
│   ├── database.php    # Database connection
│   ├── helpers.php     # Utility functions
│   ├── security_config.php  # Security headers and configuration
│   └── csrf.php        # CSRF token management
├── Handler files
│   ├── usersig.php     # User registration
│   ├── userlog.php     # User login
│   ├── userreg.php     # Vehicle registration
│   └── userrequest.php # Vehicle request processing
├── View files
│   ├── index.php       # Dashboard
│   ├── signup.php      # Registration form
│   ├── login.php       # Login form
│   ├── profile.php     # Profile display
│   ├── profup.php      # Profile update
│   ├── myrequest.php   # User requests
│   └── request.php     # Admin requests
└── Configuration
    ├── .env.example    # Environment variables template
    └── docker-compose.yml
```

### File Naming Conventions

- **View/Handler files**: `lowercase_with_underscores.php`
- **CSS files**: `lowercase-with-hyphens.css`
- **Classes**: `PascalCase.php`
- **Configuration**: `lowercase_with_underscores.php`

## PHP Code Style

### General Format

Follow PSR-12 standards with these specifications:

```php
<?php
/**
 * File Header - Brief Description
 *
 * Longer description explaining what this file does,
 * its purpose, and any important details.
 *
 * @author AMVRS ARMED Development Team
 * @version 1.0.0
 * @package AMVRS ARMED
 */

namespace MyNamespace;

require_once("path/to/file.php");

class MyClass
{
    public $property = 'value';

    public function myMethod()
    {
        // Implementation
    }
}
```

### Braces and Indentation

```php
// ✅ CORRECT - Opening brace on same line
if ($condition) {
    // Code
} else {
    // Code
}

// ✅ CORRECT - 4-space indentation
function myFunction() {
    if ($condition) {
        $result = someFunction();
    }
    return $result;
}

// ❌ WRONG - Brace on next line
if ($condition)
{
    // Code
}
```

### Line Length

- Maximum 120 characters per line
- Break long lines appropriately

```php
// ✅ CORRECT
$result = query_row(
    "SELECT * FROM users WHERE id = ? AND status = ?",
    [$user_id, $status]
);

// ❌ WRONG - Too long
$result = query_row("SELECT * FROM users WHERE id = ? AND status = ?", [$user_id, $status]);
```

## Naming Conventions

### Constants
```php
define('DB_HOST', 'localhost');
define('MAX_UPLOAD_SIZE', 5242880);  // 5MB
const BCRYPT_COST = 12;
```

### Classes
```php
class VehicleRequest { }
class UserAuthentication { }
class RequestValidator { }
```

### Functions
```php
function submitVehicleRequest() { }
function validateUserInput() { }
function hashPassword() { }
```

### Variables
```php
$userId = 123;
$vehicleName = "Transit";
$isApproved = true;
$requestCount = 5;
```

### Constants/Enumerations
```php
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const USER_ROLE_ADMIN = 'admin';
const USER_ROLE_DRIVER = 'driver';
```

## Security Standards

### ✅ DO: Input Validation & Sanitization

Always validate and sanitize user input:

```php
// Email validation
$email = validate_email($_POST['email'] ?? '');
if (!$email) {
    log_error('Invalid email format');
    header("Location: form.php?error=invalid_email");
    exit();
}

// String validation
$name = validate_string($_POST['name'] ?? '', 100);
if (!$name) {
    header("Location: form.php?error=invalid_name");
    exit();
}

// Integer validation
$userId = validate_int($_POST['user_id'] ?? 0);
if ($userId <= 0) {
    header("Location: form.php?error=invalid_user");
    exit();
}
```

### ✅ DO: CSRF Protection

All POST requests must include CSRF token validation:

```php
<?php require_once("csrf.php"); ?>

<form method="POST" action="handler.php">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <!-- Form fields -->
</form>
```

Server-side validation:

```php
if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
    log_error('CSRF Attack Detected', 'Invalid CSRF token in form submission');
    header("HTTP/1.1 403 Forbidden");
    exit();
}
```

### ✅ DO: Output Escaping

Always escape output to prevent XSS:

```php
// Escape HTML
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// In attributes
<input value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>">

// In JavaScript context
<script>var data = <?php echo json_encode($data); ?>;</script>
```

### ✅ DO: Password Security

Use bcrypt for password hashing:

```php
// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Verify password
if (password_verify($input_password, $stored_hash)) {
    // Password matches
}
```

### ❌ DON'T: Hardcode Credentials

```php
// ❌ WRONG - Never hardcode credentials
$db_password = "mypassword123";

// ✅ CORRECT - Use environment variables
$db_password = getenv('DB_PASSWORD');
// or
$db_password = $_ENV['DB_PASSWORD'];
```

### ❌ DON'T: Use String Interpolation in SQL

```php
// ❌ WRONG - SQL Injection vulnerability
$query = "SELECT * FROM users WHERE id = '$user_id'";
mysqli_query($dbh, $query);

// ✅ CORRECT - Use prepared statements
$result = query_row("SELECT * FROM users WHERE id = ?", [$user_id]);
```

## Database Operations

### Prepared Statements (Required)

All database queries must use prepared statements through the helper functions:

```php
// SELECT single row
$user = query_row("SELECT * FROM users WHERE id = ?", [$user_id]);

// SELECT multiple rows
$requests = query_all("SELECT * FROM request WHERE status = ?", ['pending']);

// COUNT
$count = query_count("SELECT id FROM users WHERE email = ?", [$email]);

// INSERT/UPDATE/DELETE
$result = execute(
    "INSERT INTO request (user_id, vehicle_id, status) VALUES (?, ?, ?)",
    [$user_id, $vehicle_id, 'pending']
);
```

### Error Handling for Database

```php
if (!$result) {
    log_error('Database Error', 'Failed to insert request', "user: $user_id");
    header("Location: form.php?error=database");
    exit();
}
```

## Error Handling & Logging

### Use the Logging System

All errors and important actions must be logged:

```php
// Log errors
log_error('Error Type', 'Detailed message', 'Additional context');

// Log actions (audit trail)
log_action('action_name', 'Description', ['key' => 'value']);
```

### Examples

```php
// Registration error
log_error('Registration Failed', 'Username already exists', "username: $username");

// Successful action
log_action('vehicle_request_created', 'User requested vehicle', [
    'user_id' => $user_id,
    'vehicle_id' => $vehicle_id,
    'timestamp' => date('Y-m-d H:i:s')
]);

// Security incident
log_error('Security Incident', 'Potential SQL injection attempt', "input: $suspicious_input");
```

## Documentation

### File Headers

Every PHP file must start with a documentation block:

```php
<?php
/**
 * Brief Description
 *
 * Longer description explaining the file's purpose,
 * functionality, and any important details about
 * how it works.
 *
 * @author AMVRS ARMED Development Team
 * @version 1.0.0
 * @package AMVRS ARMED
 */
```

### Function Documentation

All functions must have PHPDoc comments:

```php
/**
 * Submit a vehicle request
 *
 * Validates user input, checks eligibility, creates request record,
 * and logs the action in audit trail.
 *
 * @param int $user_id User ID from session
 * @param int $vehicle_id Vehicle to request
 * @param string $mission Mission description
 * @return bool True on success, false on failure
 *
 * @throws InvalidArgumentException If parameters are invalid
 * @throws DatabaseException If database operation fails
 */
public function submitVehicleRequest($user_id, $vehicle_id, $mission)
{
    // Implementation
}
```

### Parameter Documentation

```php
/**
 * Process vehicle request
 *
 * @param array $data Array containing:
 *   - int 'user_id': User ID (required)
 *   - int 'vehicle_id': Vehicle ID (required)
 *   - string 'mission': Mission description (max 500 chars)
 *
 * @return int|false Request ID on success, false on failure
 */
```

## Testing

### Manual Testing Requirements

Before submitting a change, verify:

- [ ] Feature works as intended
- [ ] No PHP warnings/errors in logs
- [ ] Database operations function correctly
- [ ] All user roles can access appropriately
- [ ] Forms validate and submit correctly
- [ ] Input validation prevents malicious data
- [ ] CSRF tokens are validated
- [ ] Sessions persist correctly
- [ ] Error messages are appropriate

### Automated Testing

Run unit tests before commits:

```bash
php phpunit --configuration phpunit.xml
```

## Code Review Checklist

Before submitting a pull request, ensure:

- [ ] Follows PSR-12 code style
- [ ] Has proper file/function documentation
- [ ] Uses prepared statements for all queries
- [ ] Validates and sanitizes all user input
- [ ] Includes CSRF protection where needed
- [ ] Escapes all output
- [ ] Logs errors and important actions
- [ ] No hardcoded credentials
- [ ] No security vulnerabilities
- [ ] Tests pass successfully
- [ ] Changes are documented

## Security Best Practices Summary

| Practice | Status | Implementation |
|----------|--------|-----------------|
| Prepared Statements | ✅ Required | Via helpers.php functions |
| Input Validation | ✅ Required | Via validate_* functions |
| Output Escaping | ✅ Required | htmlspecialchars() |
| CSRF Protection | ✅ Required | csrf_token() & validate_csrf() |
| Password Hashing | ✅ Bcrypt | PASSWORD_BCRYPT with cost 12 |
| Security Headers | ✅ Enabled | In security_config.php |
| Error Logging | ✅ Required | Via log_error() |
| Audit Logging | ✅ Required | Via log_action() |
| Session Security | ✅ Hardened | HttpOnly, Secure, SameSite cookies |
| Environment Variables | ✅ Required | .env file, never hardcoded |

## Related Documents

- [CONTRIBUTING.md](CONTRIBUTING.md) - Contribution guidelines
- [SECURITY.md](SECURITY.md) - Security policy
- [README.md](README.md) - Project overview
- [INSTALLATION.md](INSTALLATION.md) - Setup instructions

## Version History

| Version | Date | Notes |
|---------|------|-------|
| 1.0.0 | 2026-02-09 | Initial code standards document |

---

**Last Updated:** February 9, 2026  
**Maintained By:** AMVRS ARMED Development Team  
**License:** Apache 2.0
