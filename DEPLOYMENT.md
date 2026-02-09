# Deployment Guide - AMVRS ARMED

This guide explains how to deploy AMVRS ARMED to production using GitHub Actions, Docker, or traditional hosting.

## Table of Contents
- [GitHub Secrets Setup](#github-secrets-setup)
- [GitHub Actions CI/CD](#github-actions-cicd)
- [Docker Deployment](#docker-deployment)
- [Traditional Server Deployment](#traditional-server-deployment)
- [Security Checklist](#security-checklist)

## GitHub Secrets Setup

Never commit `.env` files or hardcoded credentials. Instead, use GitHub repository secrets for CI/CD pipelines.

### Step 1: Navigate to Secrets
1. Go to your GitHub repository: `https://github.com/ajiko2505/Works`
2. Click **Settings** (top right)
3. On the left sidebar, click **Secrets and variables** → **Actions**

### Step 2: Add SMTP Secrets
Click **New repository secret** for each of the following:

| Secret Name | Value | Example |
|---|---|---|
| `MAIL_HOST` | Your SMTP server hostname | `smtp.gmail.com` |
| `MAIL_USER` | SMTP username/email | `your-email@gmail.com` |
| `MAIL_PASS` | SMTP password or app password | (Gmail app password, not regular password) |
| `MAIL_PORT` | SMTP port | `587` |
| `MAIL_ENCRYPTION` | Encryption type | `tls` or `ssl` |
| `MAIL_FROM` | Sender email | `your-email@gmail.com` |
| `MAIL_FROM_NAME` | Sender display name | `AMVRS Admin` |

### Step 3: Add Database Secrets (Optional, for CI only)

| Secret Name | Value | Example |
|---|---|---|
| `DB_HOST` | Database host | `localhost` or AWS RDS endpoint |
| `DB_USER` | Database username | `amvrs_user` |
| `DB_PASS` | Database password | (Strong password) |
| `DB_NAME` | Database name | `amvrss` |

### Step 4: Gmail Setup (if using Gmail)

If using Gmail SMTP:
1. Enable 2-factor authentication on your Google account
2. Go to [Google App Passwords](https://myaccount.google.com/apppasswords)
3. Select **Mail** and **Windows Computer** (or your setup)
4. Google generates a 16-character app password
5. Use that in `MAIL_PASS` secret (NOT your regular Gmail password)

### Step 5: Verify Secrets
- Go to **Settings** → **Secrets and variables** → **Actions**
- You should see your secrets listed (values shown as `●●●●●●●●`)
- Secrets are available only to workflows and are never logged

## GitHub Actions CI/CD

The workflow file `.github/workflows/ci.yml` automatically runs on every push and pull request.

### What the Pipeline Does
✅ **PHP Syntax Check** — Validates all PHP files for syntax errors  
✅ **Security Check** — Searches for hardcoded credentials  
✅ **Mail Config Test** — Validates mail_config.php loads env variables  
✅ **Database Schema** — Imports and validates the database schema  
✅ **Config Loading** — Tests database.php and env loading  
✅ **CSRF Check** — Verifies CSRF protection functions exist  
✅ **Docker Build** — Builds the Docker image (on main branch)  

### Viewing Workflow Results
1. Push code to GitHub
2. Go to **Actions** tab in your repo
3. Click the latest workflow run
4. View job results and logs

### Secrets are Safe
- GitHub automatically masks secrets in logs (displayed as `***`)
- Workflow uses secrets via `${{ secrets.MAIL_HOST }}` syntax
- Secrets are never printed, even if you try to echo them
- Logs are accessible only to contributors with repo access

## Docker Deployment

### Local Docker Testing
```bash
cd "C:\xampp\htdocs\AMVRS ARMED"
docker compose up --build
```

Open: http://localhost:8080

### Docker Secret Injection

Option 1: Pass secrets as environment variables
```bash
docker run \
  -e DB_HOST=mysql-server \
  -e MAIL_HOST=smtp.gmail.com \
  -e MAIL_USER=your-email@gmail.com \
  -e MAIL_PASS=your-app-password \
  -p 80:80 \
  amvrs-armed:latest
```

Option 2: Use docker-compose with .env (local only)
```yaml
version: '3.8'
services:
  web:
    environment:
      - MAIL_HOST=${MAIL_HOST}
      - MAIL_USER=${MAIL_USER}
      - MAIL_PASS=${MAIL_PASS}
      # ... other vars
```

Then create `.env` locally (not in git):
```
MAIL_HOST=smtp.gmail.com
MAIL_USER=your-email@gmail.com
MAIL_PASS=your-app-password
```

Run:
```bash
docker compose up
```

Option 3: Use Docker secrets (for Swarm/orchestration)
Create a `secrets.txt`:
```
mail_pass=your-app-password
db_pass=strong-db-password
```

## Traditional Server Deployment

### Step 1: Upload Files
1. Connect via SFTP/FTP to your hosting server
2. Upload all files from the repository (excluding `.git`, `.env`, `docker-compose.yml`)
3. Ensure directory structure is:
```
/public_html/
├── index.php
├── login.php
├── database.php
├── mail_config.php
├── csrf.php
├── database/
├── assets/
└── ... (other files)
```

### Step 2: Create `.env` on Server
1. SSH into server
2. Navigate to project root
3. Create `.env` file:
```bash
nano .env
```
4. Add your credentials:
```
DB_HOST=localhost
DB_USER=db_username
DB_PASS=db_password
DB_NAME=amvrss
MAIL_HOST=smtp.gmail.com
MAIL_USER=your-email@gmail.com
MAIL_PASS=your-app-password
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_FROM=your-email@gmail.com
MAIL_FROM_NAME="AMVRS Admin"
```
5. Save (Ctrl+O, Enter, Ctrl+X)

### Step 3: Set Permissions
```bash
# Restrict .env to owner only
chmod 600 .env

# Set directory ownership
chown -R www-data:www-data .
chmod 755 .
chmod 644 *.php
chmod 755 assets database
```

### Step 4: Import Database
```bash
mysql -h localhost -u db_username -p db_name < database/amvrss.sql
# Enter password when prompted
```

### Step 5: Access Application
- Visit: `https://yourdomain.com/amvrs/` (or your path)
- Check server error logs if issues:
```bash
tail -f /var/log/apache2/error.log
```

## Security Checklist

Before deploying to production:

### Code & Configuration
- [ ] `.env` file created and never committed
- [ ] `.gitignore` includes `.env` and sensitive files
- [ ] All hardcoded credentials removed
- [ ] `mail_config.php` properly loads from .env
- [ ] `database.php` uses env variables

### SMTP / Email
- [ ] SMTP credentials in `.env` or GitHub secrets (never in code)
- [ ] Gmail: Using app password (not regular password)
- [ ] SMTP port correct (587 for TLS, 465 for SSL)
- [ ] Tested email sending with `test_mail.php`

### Database
- [ ] Database user has LEAST privileges (not root)
- [ ] Database password is strong (16+ chars, mixed case/numbers)
- [ ] Backups enabled and tested
- [ ] Schema imported and validated

### HTTPS / SSL
- [ ] SSL certificate installed (Let's Encrypt free)
- [ ] Redirect HTTP to HTTPS
- [ ] Security headers set (HSTS, X-Frame-Options, etc.)

### File Permissions
- [ ] `.env` readable only by app user (chmod 600)
- [ ] Directories writable only by app user
- [ ] No world-readable sensitive files

### Monitoring
- [ ] Error logs regularly reviewed
- [ ] Database integrity checks scheduled
- [ ] Backups automated and tested
- [ ] Uptime monitoring enabled

## Troubleshooting

### Email not sending
- Check SMTP credentials in `.env`
- Run `php test_mail.php` to validate
- Check server firewall allows outbound port 587/465
- Enable MAIL_SMTPDEBUG=2 temporarily for verbose logging

### Database connection fails
- Verify DB_HOST, DB_USER, DB_PASS in `.env`
- Ensure database and user exist
- Test with: `php -r "echo getenv('DB_HOST');"`

### Secrets not available in workflow
- Verify secrets are added to repo (Settings → Secrets)
- Check secret names match workflow file exactly
- Ensure workflow can access secrets (public repos → "Read and write permissions")

### GitHub Actions fails
- Click the failed job to view logs
- Logs show which step failed
- Secrets are masked (`***`) in logs
- Re-run after fixing the issue

## Next Steps

1. **Set GitHub Secrets** (see above)
2. **Test locally** with Docker: `docker compose up`
3. **Push to GitHub** and monitor Actions tab
4. **Deploy to production** using traditional or Docker method
5. **Monitor logs** and error reports

---

**Last Updated**: February 9, 2026  
**Version**: 1.0.0
