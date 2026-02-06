# Installation Guide - AMVRS ARMED

Complete step-by-step guide to install and run AMVRS ARMED locally or on a server.

## Table of Contents
- [System Requirements](#system-requirements)
- [Local Installation (XAMPP)](#local-installation-xampp)
- [Server Installation](#server-installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Verification](#verification)
- [Troubleshooting](#troubleshooting)

## System Requirements

### Minimum Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Web Server**: Apache, Nginx, or IIS
- **Browser**: Chrome, Firefox, Safari, or Edge
- **Storage**: 50MB free space

### Recommended
- **PHP**: 8.0+
- **MySQL**: 8.0+
- **RAM**: 2GB
- **Bandwidth**: 10 Mbps

## Local Installation (XAMPP)

### Step 1: Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install with default settings
3. Start Control Panel
4. Click "Start" for Apache and MySQL

### Step 2: Clone Repository
```bash
cd C:\xampp\htdocs
git clone https://github.com/ajiko2505/Works.git
cd Works
```

### Step 3: Create Database
1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click "New" in left sidebar
3. Enter Database name: `amvrss`
4. Click "Create"

### Step 4: Import Database Schema
1. Select `amvrss` database
2. Click "Import" tab
3. Choose file: `database/amvrss.sql`
4. Click "Go"

### Step 5: Configure Database Connection
1. Open `database.php` in text editor
2. Verify settings match your installation:
```php
<?php
$serverName = "localhost";
$username = "root";
$password = "";  // Leave empty for XAMPP default
$dbname = "amvrss";
$dbh = mysqli_connect($serverName, $username, $password, $dbname);

if(!$dbh) {
    echo "Connection failed: " . mysqli_connect_error();
    exit;
}
?>
```
3. Save file

### Step 6: Access Application
1. Open browser
2. Navigate to: `http://localhost/Works`
3. You should see the home page

### Step 7: Create Admin User (Optional)
```sql
INSERT INTO users (username, password, email, role) 
VALUES ('admin', MD5('admin123'), 'admin@amvrs.local', 'admin');
```

## Server Installation

### Step 1: Upload Files
1. Connect via FTP/SFTP to your server
2. Upload project files to public_html or www folder
3. File structure should look like:
```
/public_html/
├── index.php
├── login.php
├── database.php
├── assets/
├── database/
└── ... (other files)
```

### Step 2: Database Setup on Server
1. Access cPanel → MySQL Databases
2. Create new database: `amvrss`
3. Create MySQL user with full privileges
4. Access phpMyAdmin
5. Import `database/amvrss.sql`

### Step 3: Update Configuration
```php
// database.php
$serverName = "localhost";  // Usually localhost
$username = "db_username";  // From cPanel
$password = "db_password";  // From cPanel
$dbname = "amvrss";
```

### Step 4: Set File Permissions
```bash
chmod 755 .
chmod 644 *.php
chmod 755 assets/
chmod 755 database/
```

### Step 5: Test Installation
- Visit: `https://yourdomain.com/Works`
- Check if homepage loads
- Try login functionality

## Database Setup

### Default Tables
The SQL file creates:
- `users` - User accounts and profiles
- `request` - Vehicle requests
- `vehicles` - Available vehicles
- `approvals` - Request approvals
- `logs` - System logs

### Create Default Admin User
```sql
-- After importing amvrss.sql, add:
INSERT INTO users (username, password, email, user_type, status) 
VALUES ('admin', MD5('admin123'), 'admin@amvrs.local', 'admin', 'active');

INSERT INTO users (username, password, email, user_type, status) 
VALUES ('approver', MD5('approver123'), 'approver@amvrs.local', 'approver', 'active');
```

### Verify Database
1. Open phpMyAdmin
2. Select `amvrss` database
3. Check all tables are present
4. Verify no errors in import log

## Configuration

### Email Setup (PHPMailer)
For email notifications to work:

1. Locate PHPMailer configuration in pages like `reqapp.php`
2. Update SMTP settings:
```php
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
```

### Session Configuration
Sessions store in default PHP temp folder. For production, create:
```bash
mkdir sessions
chmod 700 sessions
```

Update PHP.ini:
```ini
session.save_path = "/path/to/sessions"
```

## Verification

### Home Page Test
1. Navigate to application URL
2. Should see vehicle listing
3. "Login" and "Register" links visible

### Database Connection Test
Create `test.php`:
```php
<?php
include "database.php";
if($dbh) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed";
}
?>
```
Visit `/test.php` and delete after testing.

### Login Test
1. Navigate to login page
2. Default credentials:
   - Username: `admin`
   - Password: `admin123`
3. Should login successfully

## Troubleshooting

### Common Issues

#### "Connection failed: Access denied"
- Check MySQL service is running
- Verify credentials in `database.php`
- Reset MySQL password if necessary

#### "No such table: amvrss.users"
- SQL import failed
- Re-import `database/amvrss.sql`
- Check for errors in import log

#### "Page not found"
- Wrong URL format
- Files not in correct directory
- Check web server configuration

#### "Permission denied"
- File permissions too restrictive
- Run: `chmod 755` on all folders
- Web server user needs read access

#### Blank Page
- Check PHP error log
- Enable error reporting:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
- Look for database connection errors

### Getting Help
1. Check error logs in browser console (F12)
2. Check server error logs
3. Review database structure
4. Open GitHub issue with error details

## Post-Installation

### Security Steps
1. Change default passwords immediately
2. Update SMTP credentials
3. Set proper file permissions
4. Enable HTTPS on production
5. Remove test files

### Optimization
1. Enable PHP caching (OPcache)
2. Optimize database indexes
3. Enable gzip compression
4. Implement rate limiting

### Backup Strategy
```bash
# Backup database
mysqldump -u root -p amvrss > backup.sql

# Backup files
tar -czf amvrs-backup.tar.gz /path/to/application
```

## Next Steps
- Refer to [README.md](README.md) for features overview
- Check [SECURITY.md](SECURITY.md) for security guidelines
- Review [FEATURES.md](FEATURES.md) for usage details

---

**Last Updated**: February 6, 2026  
**Version**: 1.0.0
