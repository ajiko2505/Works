# Contributing to AMVRS ARMED

Thank you for your interest in contributing to AMVRS ARMED! This document provides guidelines and instructions for contributing to the project.

## Table of Contents
- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Making Changes](#making-changes)
- [Code Standards](#code-standards)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Enhancements](#suggesting-enhancements)

## Code of Conduct

### Our Pledge
We are committed to providing a welcoming and inclusive environment for all contributors. We pledge to:
- Be respectful and inclusive
- Welcome diverse perspectives
- Focus on what is best for the community
- Deal with conflicts gracefully

### Expected Behavior
- Use welcoming and inclusive language
- Be respectful of differing opinions
- Accept constructive criticism gracefully
- Focus on what is best for the community

### Unacceptable Behavior
- Harassment of any kind
- Discriminatory language or behavior
- Personal attacks
- Public or private harassment

## Getting Started

### Prerequisites
- Git knowledge (basic commands)
- PHP development experience
- MySQL/Database experience
- Text editor or IDE

### Fork the Repository
1. Go to [https://github.com/ajiko2505/Works](https://github.com/ajiko2505/Works)
2. Click "Fork" button
3. Clone your fork locally:
```bash
git clone https://github.com/YOUR_USERNAME/Works.git
cd Works
```

### Set Up Upstream
```bash
git remote add upstream https://github.com/ajiko2505/Works.git
git fetch upstream
```

## Development Setup

### Environment Setup
```bash
# Create a local branch for development
git checkout -b develop
git pull upstream develop

# Set up local database
mysql -u root < database/amvrss.sql

# Start your development server (XAMPP/WAMP)
```

### IDE/Editor Recommendations
- **VS Code** (Recommended)
  - Extensions: PHP IntelliSense, MySQL, GitLens
- **PhpStorm**
- **Sublime Text 3**
- **Atom** with PHP packages

### Local Testing
```bash
# Test database connection
php -r "include 'database.php'; echo 'Connected';"

# Run on local server
# Access: http://localhost/Works
```

## Making Changes

### Branch Naming Convention
```
feature/short-description
bugfix/issue-description
enhancement/improvement-description
docs/documentation-update
```

Examples:
```
feature/vehicle-status-update
bugfix/login-session-error
enhancement/email-notifications
docs/installation-guide-update
```

### Commit Message Format
```
[Type] Brief description

More detailed explanation if needed.
Fixes #123 (if fixing an issue)
```

Types:
- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation
- `style` - Formatting
- `refactor` - Code restructuring
- `test` - Adding tests
- `chore` - Maintenance

Examples:
```
[feat] Add email notifications for approvals

Implements PHPMailer integration for automatic
email notifications when requests are approved.
Fixes #45
```

## Code Standards

### PHP Code Style
Follow PSR-12 standards:

```php
<?php

namespace MyApp;

class VehicleRequest
{
    public function submitRequest($data)
    {
        // Implementation
    }
}
```

### Naming Conventions
- **Classes**: PascalCase (`VehicleRequest`)
- **Functions**: camelCase (`submitRequest()`)
- **Variables**: camelCase (`$userName`)
- **Constants**: UPPER_CASE (`DB_HOST`)
- **Files**: lowercase with hyphens (`vehicle-request.php`)

### Documentation
Always include PHPDoc comments:
```php
/**
 * Submit a vehicle request
 *
 * @param array $data Request data
 * @return bool True on success
 */
public function submitRequest($data)
{
    // Implementation
}
```

### Database Queries
Always use prepared statements:
```php
// ✅ CORRECT
$stmt = $dbh->prepare("INSERT INTO requests (user_id, vehicle_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $vehicle_id);
$stmt->execute();

// ❌ WRONG
$query = "INSERT INTO requests VALUES ($user_id, $vehicle_id)";
mysqli_query($dbh, $query);
```

### Input Validation
Always validate and sanitize user input:
```php
// ✅ CORRECT
$username = trim($_POST['username'] ?? '');
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    throw new InvalidArgumentException('Invalid username');
}

// ❌ WRONG
$username = $_POST['username'];
```

### Security
- Don't hardcode credentials
- Use environment variables for sensitive data
- Escape output with `htmlspecialchars()`
- Implement CSRF protection
- Use HTTPS headers

## Testing

### Manual Testing Checklist
Before submitting changes, test:
- [ ] Feature works as intended
- [ ] No PHP errors in error log
- [ ] Database operations work
- [ ] All user roles can access correctly
- [ ] Forms submit properly
- [ ] Validation works
- [ ] Email notifications work (if applicable)
- [ ] Session handling works

### User Role Testing
Test functionality for each role:
```
[ ] Driver/User
[ ] Approver
[ ] Administrator
```

### Edge Cases
- Empty inputs
- Null values
- Special characters
- Very long inputs
- Unicode characters
- Concurrent requests

## Submitting Changes

### Prepare Your Commit
```bash
# Stage changes
git add .

# Commit with good message
git commit -m "[feat] Description of changes"

# Push to your fork
git push origin branch-name
```

### Create Pull Request
1. Go to your fork on GitHub
2. Click "Compare & pull request"
3. Set base: `ajiko2505/Works:main`
4. Set compare: `YOUR_USERNAME/Works:branch-name`
5. Fill PR description

## Pull Request Process

### PR Description Template
```markdown
## Description
Brief description of changes

## Type
- [ ] Bug fix
- [ ] New feature
- [ ] Enhancement
- [ ] Documentation

## Changes
- Change 1
- Change 2
- Change 3

## Testing
Describe testing performed:
- Test 1
- Test 2

## Screenshots
Attach relevant screenshots (if applicable)

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] No new warnings generated
- [ ] Added/updated documentation
- [ ] Tested on multiple user roles
- [ ] No breaking changes
```

### Review Process
1. Maintainers will review your PR
2. Request changes if needed
3. Ensure all checks pass
4. Approval and merge

## Reporting Bugs

### Bug Report Template
```markdown
## Description
Brief description of the bug

## Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

## Expected Behavior
What should happen

## Actual Behavior
What actually happens

## Environment
- PHP Version:
- MySQL Version:
- Browser:
- OS:

## Screenshots
Attach screenshots (if applicable)

## Error Messages
```
Paste any error messages
```

## Additional Context
Any additional information
```

### Submitting Bug Reports
1. Check if bug already reported
2. Use bug report template
3. Include error messages/logs
4. Be as specific as possible
5. Include reproduction steps

## Suggesting Enhancements

### Feature Request Template
```markdown
## Description
Brief description of feature

## Problem Statement
What problem does this solve?

## Proposed Solution
How should this be implemented?

## Alternative Solutions
Any alternatives considered?

## Additional Context
Any additional information
```

### Submitting Feature Requests
1. Check if feature already requested
2. Describe problem clearly
3. Suggest implementation
4. Include use cases
5. Be open to discussion

## Recognition

### Contributors
All contributors will be:
- Added to CONTRIBUTORS.md
- Mentioned in release notes
- Given credit in appropriate files

### Levels
- **Bug Reporters**: Issue reports and feedback
- **Code Contributors**: Pull requests and fixes
- **Documentation**: Documentation improvements
- **Translators**: Localization support

## Questions?

- 📧 Email: (your contact link)
- 💬 GitHub Discussions: Available in Issues
- 📖 Documentation: See README.md
- 🐛 Bug Tracking: GitHub Issues

## Resources

### Learning Resources
- [PHP PSR-12 Standards](https://www.php-fig.org/psr/psr-12/)
- [Git Documentation](https://git-scm.com/doc)
- [MySQL Best Practices](https://dev.mysql.com/)
- [OWASP Security Guidelines](https://owasp.org/)

### Tools
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHPStan](https://phpstan.org/)
- [MySQL Workbench](https://www.mysql.com/products/workbench/)

## License

By contributing to AMVRS ARMED, you agree that your contributions will be licensed under the MIT License.

---

**Last Updated**: February 6, 2026  
**Version**: 1.0.0

Thank you for contributing! 🎉
