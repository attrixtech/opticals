# Optical Store Management ERP — Installation & Setup Guide

## System Prerequisites
- PHP 8.2 or 8.3+ (Installed: PHP 8.4)
- Extensions: `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`, `gd`, `zip`
- MySQL 8.0+ or MariaDB 10.4+ (Installed on XAMPP)
- Composer 2.x
- Node.js 18+ and NPM (Installed: Node v22.23)

## Quick Start on Local / XAMPP Server

1. **Clone / Navigate to application directory**:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/optical
   ```

2. **Configure Environment**:
   ```bash
   cp .env.example .env
   # Ensure DB credentials match MySQL:
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=optical_erp
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

3. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Frontend Assets**:
   ```bash
   npm run build
   ```

7. **Start Development Server**:
   ```bash
   php artisan serve --port=8000
   ```
   Or access directly via XAMPP Apache: `http://localhost/optical/public`

## Default Administrative Credentials
- **Super Administrator**: `admin@opticalerp.test` / `Password@123`
- **Store Manager**: `manager@opticalerp.test` / `Password@123`
- **Optometrist**: `optom@opticalerp.test` / `Password@123`
- **Lab Technician**: `lab@opticalerp.test` / `Password@123`
- **Cashier**: `cashier@opticalerp.test` / `Password@123`

