# Optical Store Management ERP — Production Deployment Guide

## 1. Server Environment Setup
- Recommended OS: Ubuntu 22.04 LTS / 24.04 LTS
- Web Server: Nginx or Apache 2.4+
- PHP: PHP 8.2 or 8.3 / 8.4 with FPM
- Database: MySQL 8.0+ or MariaDB 10.6+

## 2. Nginx Virtual Host Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name optical.yourdomain.com;
    root /var/www/optical/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 3. Production Deployment Commands
```bash
# Set file ownership
chown -R www-data:www-data /var/www/optical/storage /var/www/optical/bootstrap/cache

# Optimize caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Compile assets
npm run build
```

