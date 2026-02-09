# AMVRS ARMED - Armed Forces Vehicle Request & Management System

> A comprehensive web-based vehicle request and approval management system designed for armed forces personnel.

[![GitHub](https://img.shields.io/badge/GitHub-ajiko2505-181717?logo=github)](https://github.com/ajiko2505/Works)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-005C87?logo=mysql)](https://www.mysql.com/)

##  Overview

AMVRS ARMED is a robust vehicle management system built for armed forces operations. It enables personnel to request vehicles, track request status, and provides administrators with management tools for approvals and vehicle inventory.

### Quick Stats
- **Total Pages**: 20+ PHP files
- **Database Tables**: 5+ tables
- **User Roles**: 3 levels (Admin, Approver, User)
- **Framework**: Bootstrap 5 + jQuery
- **Backend**: PHP 7.4+, MySQL 5.7+

##  Core Features

### For Users/Drivers
-  Secure login & registration
-  Submit vehicle requests with mission details
-  Track request status and history
-  Manage profile information
-  Receive email notifications
-  Browse available vehicles

### For Administrators
-  Full user management
-  Approve/reject requests
-  Vehicle inventory tracking
-  Analytics and reporting
-  System administration

### For Approvers
-  Review pending requests
-  Approve with detailed notes
-  Send automated notifications

##  Getting Started

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP or similar

### Installation

1. **Clone the repo**
   \\\ash
   git clone https://github.com/ajiko2505/Works.git
   \\\

2. **Setup Database**
   - Open phpMyAdmin
   - Create database: \mvrss\
   - Import: \database/amvrss.sql\

3. **Configure**
   - Edit \database.php\ with your MySQL credentials

4. **Run**
   - Start your web server
   - Visit: http://localhost/Works

##  Project Structure

\\\
Works/
 index.php              
 login.php              
 register.php           
 request.php            
 approve.php            
 myrequest.php          
 profile.php            
 database.php           
 header.php             
 assets/                
    css/               
    images/            
 database/              
    amvrss.sql         
 PHPmailer/             
\\\

##  Documentation

Comprehensive documentation is available:

- **[CODE_STANDARDS.md](CODE_STANDARDS.md)** - Coding standards, PSR-12 compliance, naming conventions, security practices
- **[ARCHITECTURE.md](ARCHITECTURE.md)** - System architecture, technology stack, component structure, database schema
- **[SECURITY.md](SECURITY.md)** - Security policy, vulnerability reporting, security measures
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Contribution guidelines, pull request process, code review
- **[INSTALLATION.md](INSTALLATION.md)** - Detailed setup instructions
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Production deployment and CI/CD
- **[IMPROVEMENTS.md](IMPROVEMENTS.md)** - Recent improvements and changes

##  Configuration

### Database
Edit \database.php\:
\\\php
\ = "localhost";
\ = "root";
\ = "";
\ = "amvrss";
\\\

##  User Roles & Permissions

| Role | View Requests | Approve | Manage Users |
|------|---|---|---|
| Admin |  All |  Yes |  Yes |
| Approver |  All |  Yes |  No |
| User/Driver |  Own |  No |  No |

##  Security Features

-  Password hashing
-  Session-based authentication
-  Role-based access control
-  SQL injection prevention
-  User input validation

##  License

MIT License - See [LICENSE](LICENSE)

##  Support

- **GitHub**: [ajiko2505/Works](https://github.com/ajiko2505/Works)
- **Report Issues**: GitHub Issues
- **Documentation**: Check docs/ folder

---

**Version**: 1.0.0  
**Status**:  Live & Maintained  
**Last Updated**: February 6, 2026

**Made for Armed Forces Operations**
