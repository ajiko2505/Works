# AMVRS ARMED - Project Architecture & Technology Guide

This document provides a comprehensive overview of the AMVRS ARMED project architecture, technology stack, and how components interact.

## Project Overview

**AMVRS ARMED** (Armed Forces Vehicle Management & Request System) is a web-based application for managing vehicle requests, allocations, and tracking in military/armed forces organizations.

**Key Features:**
- User registration and authentication
- Vehicle catalog management
- Request submission and approval workflow
- Admin dashboard for request management
- User profile management
- Audit logging and security tracking

## Technology Stack

### Backend
- **Language:** PHP 7.4+ / 8.x
- **Web Server:** Apache (XAMPP/WAMP)
- **Database:** MySQL 5.7+ / MariaDB
- **Mail:** PHPMailer 6.x

### Frontend
- **HTML5:** Semantic markup
- **CSS3:** Modern styling with CSS Grid and Flexbox
- **JavaScript:** jQuery + Bootstrap 5.3
- **Icons:** Bootstrap Icons (2000+ SVG icons)

### Build & DevOps
- **Containerization:** Docker & Docker Compose
- **CI/CD:** GitHub Actions
- **Version Control:** Git
- **Testing:** PHPUnit
- **Dependencies:** Composer (for PHPMailer and PHPUnit)

### Security
- **Password Hashing:** bcrypt (PHP's password_hash)
- **Token Generation:** OpenSSL based CSRF tokens
- **Session Security:** HttpOnly, Secure, SameSite cookies
- **Input Validation:** Custom validation functions
- **Output Escaping:** htmlspecialchars()

## Architecture Overview

### MVC-like Pattern

```
Request Flow:
    View (Form) 
        ↓ 
    Handler (Processor) 
        ↓ 
    Database 
        ↓ 
    Redirect to View
```

### Component Structure

```
┌─────────────────────────────────────────────┐
│           Web Browser / Client              │
└────────────────────┬────────────────────────┘
                     │ HTTP/HTTPS
┌─────────────────────▼────────────────────────┐
│         Web Server (Apache)                 │
├─────────────────────────────────────────────┤
│         Views Layer (UI/Forms)              │
│  ├─ index.php       (Dashboard)             │
│  ├─ signup.php      (Registration)          │
│  ├─ login.php       (Login)                 │
│  ├─ profile.php     (Profile Display)       │
│  └─ ... more views                          │
├─────────────────────────────────────────────┤
│         Handler Layer (Business Logic)      │
│  ├─ usersig.php     (Register)              │
│  ├─ userlog.php     (Authenticate)          │
│  ├─ userreg.php     (Vehicle Register)      │
│  ├─ userrequest.php (Request Vehicle)       │
│  └─ ... more handlers                       │
├─────────────────────────────────────────────┤
│         Utility Layer                       │
│  ├─ helpers.php     (Functions)             │
│  ├─ security_config.php (Security)          │
│  ├─ csrf.php        (CSRF Protection)       │
│  ├─ mail_config.php (Email)                 │
│  └─ header.php      (Navigation)            │
├─────────────────────────────────────────────┤
│         Data Access Layer                   │
│  └─ database.php    (PDO/MySQLi)            │
└────────────────┬────────────────────────────┘
                 │ Network Connection
┌────────────────▼───────────────────┐
│    MySQL/MariaDB Database          │
├────────────────────────────────────┤
│ Tables:                            │
│  - users        (User accounts)    │
│  - vechicle     (Vehicle catalog)  │
│  - request      (User requests)    │
│  - vech_allocated (Allocations)    │
└────────────────────────────────────┘
```

## Key Components

### 1. Database Layer (database.php)

```php
// Database connection and utility functions
mysqli_connect() or PDO
Query execution
Error handling
```

**Key Functions:**
- `mysqli_connect()` - Database connection
- `mysqli_query()` - Execute queries (wrapped in helpers)

### 2. Security Layer (security_config.php)

```php
// Session configuration
session_set_cookie_params() - Secure cookies
header() - Security headers
error_reporting() - Error display
```

**Security Headers:**
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- X-XSS-Protection: 1; mode=block
- Content-Security-Policy: Restrictive policy
- Referrer-Policy: strict-origin-when-cross-origin
- HSTS: Strict-Transport-Security

### 3. Helper Functions (helpers.php)

```php
// Input validation
validate_email()
validate_username()
validate_password()
validate_string()
validate_int()
validate_phone()

// Database queries (prepared statements)
query()      - SELECT query
query_row()  - Single row
query_all()  - Multiple rows
query_count() - Row count
execute()    - INSERT/UPDATE/DELETE

// Session management
session_get()
session_set()
is_logged_in()
check_role()
require_login()
require_role()

// Error handling
log_error()
log_action()

// Flash messages
set_flash()
get_flash()
display_flash()
```

### 4. CSRF Protection (csrf.php)

```php
// Token generation
csrf_token() - Generate/retrieve token

// Token validation
validate_csrf() - Verify token

// Session storage
$_SESSION['csrf_token']
```

### 5. Mail Configuration (mail_config.php)

```php
// PHPMailer setup
SMTP configuration
From address
Reply-to address
```

## Request Flow Examples

### User Registration Flow

```
1. User submits signup.php form
2. Form posts to usersig.php
3. usersig.php validates input:
   - Validate email format
   - Check username format
   - Check password strength
   - Check for duplicates
4. Hash password with bcrypt
5. Insert into database
6. Log action
7. Redirect to login
```

### Vehicle Request Flow

```
1. User submits vehicle request from index.php
2. Form posts to userrequest.php
3. userrequest.php validates:
   - User is logged in
   - Vehicle exists
   - Duplicate check
4. Insert request into request table
5. Log action
6. Send approval email to admin
7. Redirect with success message
```

### Admin Approval Flow

```
1. Admin views pending requests (request.php)
2. Admin clicks "View" for a request
3. Goes to reqpre.php for details
4. Admin clicks "Approve"
5. POST to reqapp.php
6. reqapp.php updates status
7. Sends email to user
8. Updates vehicle status
9. Redirect with success
```

## Database Schema

### users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Full_name VARCHAR(100),
    rank VARCHAR(50),
    snumber VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE,
    username VARCHAR(20) UNIQUE,
    password VARCHAR(255),
    user_type ENUM('driver', 'admin'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### vechicle Table
```sql
CREATE TABLE vechicle (
    vech_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    vech_name VARCHAR(100),
    vech_color VARCHAR(50),
    vech_desc TEXT,
    vech_cat VARCHAR(50),
    vech_image VARCHAR(255),
    status ENUM('free', 'allocated', 'maintenance'),
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### request Table
```sql
CREATE TABLE request (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    username VARCHAR(100),
    vech_id INT,
    vech_name VARCHAR(100),
    vech_col VARCHAR(50),
    mission TEXT,
    status ENUM('pending', 'approved', 'rejected', 'returned'),
    return_date DATE,
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vech_id) REFERENCES vechicle(vech_id)
);
```

### vech_allocated Table
```sql
CREATE TABLE vech_allocated (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    vech_id INT,
    allocation_date TIMESTAMP,
    return_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vech_id) REFERENCES vechicle(vech_id)
);
```

## Security Implementation

### Authentication

```
User Input
    ↓ validate_email() / validate_username()
Input Validation
    ↓ query_row() with prepared statement
Database Lookup
    ↓ password_verify()
Password Verification
    ↓ $_SESSION['id'] = $user_id
Session Creation
```

### Authorization

```
Check $_SESSION['user_type']
    ↓
Route to role-specific functionality
    ↓
Block unauthorized access
    ↓
Log unauthorized attempt
```

### CSRF Protection

```
Form Generated
    ↓ csrf_token() generates unique token
Token embedded in hidden field
    ↓
User submits form
    ↓ validate_csrf() verifies token
Token validation
    ↓
Process request or reject
```

## Configuration Files

### .env File (Git ignored)

```
DB_HOST=localhost
DB_USER=root
DB_PASS=password
DB_NAME=amvrss
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_password
SMTP_FROM=your_email@gmail.com
ADMIN_EMAIL=admin@amvrs.local
```

### .env.example (Git tracked)

```
# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=amvrss

# Email Configuration (Gmail SMTP)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_app_password
SMTP_FROM=noreply@amvrs.local
ADMIN_EMAIL=admin@amvrs.local
```

## Deployment Architecture

### Docker Setup

```yaml
Services:
  - PHP Apache (Port 80/443)
  - MySQL (Port 3306)
  - NGinx (Reverse Proxy - optional)
  - PHP-FPM (Backend - optional)

Volumes:
  - /var/www/html  (Application)
  - /var/lib/mysql (Database)
  - /logs (Application logs)
```

### GitHub Actions CI/CD

**Trigger:** Every push to main branch

**Stages:**
1. **Lint** - PHP syntax check
2. **Security** - Code analysis
3. **Database Test** - Schema validation
4. **Deploy** - rsync to production
5. **Publish Docker** - Push to GHCR

## Logging System

### Error Logs (logs/errors.log)

```
[2026-02-09 14:30:45] ERROR: Database Error
Details: Failed to insert vehicle request
Context: user_id=5, vehicle_id=12
Stack trace: ...
```

### Audit Logs (logs/audit.log)

```
[2026-02-09 14:30:45] ACTION: vehicle_request_created
Description: User requested vehicle
User: john_doe (ID: 5)
Details: vehicle_id=12, mission=Medical Emergency
IP: 192.168.1.100
```

## Performance Considerations

### Optimization Techniques

1. **Prepared Statements** - Prevents SQL injection, improves query plan caching
2. **Database Indexing** - Primary keys, unique constraints on ID columns
3. **CSS Grid/Flexbox** - Modern layout without JavaScript overhead
4. **Lazy Loading** - Bootstrap Icons loaded via CDN
5. **Caching** - PHP sessions, database query results

### Scalability

For production deployments:
1. Use connection pooling (ProxySQL)
2. Implement query caching (Redis)
3. Use CDN for static assets
4. Load balancing (Nginx)
5. Database replication for high availability

## Testing Strategy

### Unit Tests (PHPUnit)

```
tests/
├── HelpersTest.php    - Validation functions
├── SecurityTest.php   - CSRF, input validation
└── DatabaseTest.php   - Query functions
```

### Manual Testing Checklist

- [ ] Registration flow
- [ ] Login with correct/incorrect credentials
- [ ] Profile update
- [ ] Vehicle registration
- [ ] Request submission
- [ ] Admin approval
- [ ] Email notifications
- [ ] Session persistence
- [ ] CSRF protection
- [ ] Input validation

## Development Workflow

### Local Development

```bash
# Clone repository
git clone https://github.com/ajiko2505/Works.git

# Install dependencies
composer install

# Set up environment
cp .env.example .env

# Start local server
php -S localhost:8000

# Run tests
php phpunit --configuration phpunit.xml
```

### Feature Development

```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes
# Test locally
# Commit changes
git commit -m "[feat] Description"

# Push to fork
git push origin feature/new-feature

# Create Pull Request on GitHub
```

### Production Deployment

```bash
# Automated via GitHub Actions:
# 1. Lint check passes
# 2. Tests pass
# 3. Deploy via rsync
# 4. Docker image published to GHCR
```

## Related Documentation

- [CODE_STANDARDS.md](CODE_STANDARDS.md) - Coding standards
- [SECURITY.md](SECURITY.md) - Security policy
- [CONTRIBUTING.md](CONTRIBUTING.md) - Contribution guidelines
- [INSTALLATION.md](INSTALLATION.md) - Setup instructions
- [DEPLOYMENT.md](DEPLOYMENT.md) - Deployment guide

## Version History

| Version | Date | Notes |
|---------|------|-------|
| 1.0.0 | 2026-02-09 | Initial architecture documentation |

---

**Last Updated:** February 9, 2026  
**Maintained By:** AMVRS ARMED Development Team  
**License:** Apache 2.0
