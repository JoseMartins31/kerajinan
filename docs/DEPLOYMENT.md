# Deployment Guide

## Server Requirements

### Minimum Requirements

- **OS**: Ubuntu 20.04+ or CentOS 8+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 8.1+ with required extensions
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **Memory**: 2GB RAM minimum, 4GB recommended
- **Storage**: 10GB free space minimum

### PHP Extensions Required

```bash
php8.1-cli
php8.1-fpm
php8.1-mysql
php8.1-xml
php8.1-curl
php8.1-json
php8.1-mbstring
php8.1-zip
php8.1-gd
php8.1-intl
php8.1-bcmath
php8.1-opcache
```

## Production Server Setup

### 1. Server Preparation (Ubuntu 20.04)

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-cli php8.1-mysql \
    php8.1-xml php8.1-curl php8.1-json php8.1-mbstring php8.1-zip \
    php8.1-gd php8.1-intl php8.1-bcmath php8.1-opcache \
    git curl unzip supervisor certbot python3-certbot-nginx
```

### 2. MySQL Database Setup

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE kerajinan_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kerajinan_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON kerajinan_production.* TO 'kerajinan_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment

```bash
# Create application directory
sudo mkdir -p /var/www/kerajinan
sudo chown $USER:$USER /var/www/kerajinan

# Clone repository
cd /var/www
git clone <your-repository-url> kerajinan
cd kerajinan

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install dependencies
composer install --optimize-autoloader --no-dev
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file:

```env
APP_NAME="Kerajinan Store"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kerajinan_production
DB_USERNAME=kerajinan_user
DB_PASSWORD=secure_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Database Migration and Optimization

```bash
# Run migrations
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
sudo chown -R www-data:www-data /var/www/kerajinan
sudo chmod -R 755 /var/www/kerajinan
sudo chmod -R 775 /var/www/kerajinan/storage
sudo chmod -R 775 /var/www/kerajinan/bootstrap/cache
```

## Web Server Configuration

### Nginx Configuration

Create `/etc/nginx/sites-available/kerajinan`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/kerajinan/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Handle PHP files
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Security headers
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Asset caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # File upload limits
    client_max_body_size 10M;

    # Logging
    access_log /var/log/nginx/kerajinan.access.log;
    error_log /var/log/nginx/kerajinan.error.log;
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/kerajinan /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache Configuration (Alternative)

Create `/etc/apache2/sites-available/kerajinan.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/kerajinan/public

    <Directory /var/www/kerajinan/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Security headers
    Header always set X-Frame-Options SAMEORIGIN
    Header always set X-Content-Type-Options nosniff

    # Asset caching
    <LocationMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg)$">
        ExpiresActive On
        ExpiresDefault "access plus 1 year"
        Header set Cache-Control "public, immutable"
    </LocationMatch>

    # File upload limits
    LimitRequestBody 10485760

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/kerajinan_error.log
    CustomLog ${APACHE_LOG_DIR}/kerajinan_access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite kerajinan.conf
sudo a2enmod rewrite headers expires
sudo systemctl reload apache2
```

## SSL Certificate Setup

### Using Let's Encrypt (Recommended)

```bash
# For Nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# For Apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### Manual SSL Certificate

Update Nginx configuration:

```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # ... rest of configuration
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

## Process Management

### Using Supervisor for Queue Workers

Create `/etc/supervisor/conf.d/kerajinan.conf`:

```ini
[program:kerajinan-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/kerajinan/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/kerajinan/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start kerajinan-worker:*
```

## Performance Optimization

### PHP-FPM Configuration

Edit `/etc/php/8.1/fpm/pool.d/www.conf`:

```ini
[www]
user = www-data
group = www-data

; Process management
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.process_idle_timeout = 10s
pm.max_requests = 500

; Performance tuning
php_admin_value[memory_limit] = 256M
php_admin_value[max_execution_time] = 60
php_admin_value[upload_max_filesize] = 10M
php_admin_value[post_max_size] = 10M

; OPcache settings
php_admin_value[opcache.enable] = 1
php_admin_value[opcache.memory_consumption] = 128
php_admin_value[opcache.max_accelerated_files] = 4000
php_admin_value[opcache.revalidate_freq] = 60
```

### MySQL Optimization

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
# InnoDB settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT

# Query cache
query_cache_type = 1
query_cache_size = 128M
query_cache_limit = 2M

# Connection settings
max_connections = 200
max_connect_errors = 10000
wait_timeout = 300
interactive_timeout = 300

# Buffer settings
key_buffer_size = 256M
sort_buffer_size = 2M
read_buffer_size = 1M
read_rnd_buffer_size = 4M
```

### Application-Level Caching

```bash
# Install Redis for caching
sudo apt install redis-server

# Configure Laravel to use Redis
# In .env:
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Monitoring and Logging

### Log Rotation

Create `/etc/logrotate.d/kerajinan`:

```
/var/www/kerajinan/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
    postrotate
        php /var/www/kerajinan/artisan cache:clear
    endscript
}
```

### System Monitoring

Install monitoring tools:

```bash
# Install monitoring packages
sudo apt install htop iotop nethogs

# Setup log monitoring
sudo apt install fail2ban
```

Configure Fail2ban `/etc/fail2ban/jail.local`:

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5

[nginx-http-auth]
enabled = true

[nginx-limit-req]
enabled = true
```

## Backup Strategy

### Database Backup Script

Create `/usr/local/bin/backup-kerajinan.sh`:

```bash
#!/bin/bash

# Configuration
DB_NAME="kerajinan_production"
DB_USER="kerajinan_user"
DB_PASS="secure_password_here"
BACKUP_DIR="/var/backups/kerajinan"
APP_DIR="/var/www/kerajinan"
DATE=$(date +"%Y%m%d_%H%M%S")

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/database_$DATE.sql.gz

# Application files backup (exclude vendor and node_modules)
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $APP_DIR --exclude='vendor' --exclude='node_modules' --exclude='storage/logs' .

# Storage files backup
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C $APP_DIR/storage/app/public .

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and add to cron:

```bash
sudo chmod +x /usr/local/bin/backup-kerajinan.sh

# Add to crontab (daily backup at 2 AM)
sudo crontab -e
0 2 * * * /usr/local/bin/backup-kerajinan.sh >> /var/log/kerajinan-backup.log 2>&1
```

## Security Hardening

### Firewall Configuration

```bash
# UFW Firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### File Permissions Security

```bash
# Secure file permissions
sudo find /var/www/kerajinan -type f -exec chmod 644 {} \;
sudo find /var/www/kerajinan -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/kerajinan/storage
sudo chmod -R 775 /var/www/kerajinan/bootstrap/cache
sudo chmod 644 /var/www/kerajinan/.env
```

### Hide Server Information

Add to Nginx config:

```nginx
server_tokens off;
```

Add to PHP config `/etc/php/8.1/fpm/php.ini`:

```ini
expose_php = Off
```

## Troubleshooting

### Common Issues

#### Permission Denied Errors

```bash
sudo chown -R www-data:www-data /var/www/kerajinan
sudo chmod -R 775 /var/www/kerajinan/storage
sudo chmod -R 775 /var/www/kerajinan/bootstrap/cache
```

#### Database Connection Issues

```bash
# Test database connection
mysql -u kerajinan_user -p kerajinan_production

# Check MySQL service
sudo systemctl status mysql
sudo systemctl restart mysql
```

#### High Memory Usage

```bash
# Check PHP processes
ps aux | grep php-fpm

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

#### Slow Response Times

```bash
# Check system resources
htop
iotop
df -h

# Analyze slow queries
sudo mysql -u root -p
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;
```

## Maintenance Tasks

### Daily Tasks

- Check application logs
- Monitor system resources
- Verify backup completion

### Weekly Tasks

- Update system packages
- Review error logs
- Check SSL certificate expiration

### Monthly Tasks

- Security audit
- Performance review
- Database optimization
- Clean old log files

## Deployment Checklist

### Pre-deployment

- [ ] Test application locally
- [ ] Run all tests
- [ ] Update documentation
- [ ] Backup current production

### Deployment

- [ ] Pull latest code
- [ ] Install/update dependencies
- [ ] Run migrations
- [ ] Clear caches
- [ ] Update file permissions
- [ ] Restart services

### Post-deployment

- [ ] Verify application functionality
- [ ] Check error logs
- [ ] Monitor performance
- [ ] Notify stakeholders

This comprehensive deployment guide ensures a secure, performant, and maintainable production environment for the Kerajinan e-commerce application.
