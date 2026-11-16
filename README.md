# WebotApp OpticalERP — Enterprise Optical Store Management ERP

> **Software Name:** WebotApp OpticalERP (VisionCraft Edition)  
> **Repository:** [https://github.com/webotapp/optical-erp](https://github.com/webotapp/optical-erp)  
> **Stack:** Laravel 13 (PHP 8.4) • Livewire 4 • Tailwind CSS • Alpine.js • MySQL 8 / MariaDB

---

## 📖 Software Description

**WebotApp OpticalERP** is an enterprise-grade, multi-branch, multi-warehouse Enterprise Resource Planning (ERP) platform designed specifically for the optical retail, spectacle manufacturing, and optometry ecosystem in India and global markets.

Unlike generic POS systems, **WebotApp OpticalERP** manages the complete optical lifecycle:
$$\text{Customer CRM} \longrightarrow \text{Clinical Refraction (OD/OS)} \longrightarrow \text{3D Frame Fitting} \longrightarrow \text{Optical POS} \longrightarrow \text{Lens Surfacing \& Edging Lab} \longrightarrow \text{8-Point QC} \longrightarrow \text{GST Billing} \longrightarrow \text{Fitting \& Delivery} \longrightarrow \text{Warranty}$$

---

## 🌟 Key Features

### 1. Role-Tailored Dashboards & Strict Permission-Based Navigation
- 👑 **Super Admin / Owner**: Full business metrics (daily sales, gross/net profit, branch performance, inventory valuation, active lab pipeline, and stock reorder warnings).
- 🏬 **Store Manager**: Branch daily targets, order flow, pending fitting deliveries, customer follow-ups, and staff activity.
- 🩺 **Optometrist**: Clinical station dashboard focusing on today's refractions, subjective/objective examinations, Snellen/LogMAR visual acuity, distance/near PD measurements, and digital prescription signing.
- 🔬 **Laboratory Technician**: Workbench dashboard with real-time job cards across 7 stages (Lens Picked $\rightarrow$ Surfacing $\rightarrow$ Coating $\rightarrow$ Patternless Edging $\rightarrow$ Mounting $\rightarrow$ 8-Point QC $\rightarrow$ Dispatched), rework alerts, and frame repair bench.
- 💳 **POS Cashier**: Fast counter dashboard with today's cash drawer balance, UPI/QR and card breakdowns, pending deliveries awaiting customer balance settlement, and 1-click POS terminal.
- ⚡ **1-Click Role Switcher**: Interactive switcher in the top navigation allowing instant toggle between role views.

### 2. Clinical Optical Prescriptions (OD / OS)
- Independent Right Eye (**OD**) & Left Eye (**OS**) parameters: Sphere (SPH), Cylinder (CYL), Axis ($0^\circ-180^\circ$), Addition (ADD), Prism ($\Delta$), Base, Distance VA, and Near VA.
- Monocular & Binocular Pupillary Distance (**PD**).
- Frame geometrical measurements ($A$, $B$, $\text{DBL}$, $\text{ED}$, Temple Length) and 3D fitting parameters (pantoscopic tilt, vertex distance, fitting height).
- Automated optical transposition (plus-to-minus cylinder conversion) and spherical equivalent calculator.
- Immutable prescription audit history.

### 3. High-Speed Optical POS & GST Tax Invoicing
- Barcode and SKU scanner search.
- Link patient prescription directly into the order.
- Indian GST compliant engine with automatic HSN classification:
  - Frames & Sunglasses: HSN `9003` / `9004` ($12\%$ / $18\%$)
  - Corrective Ophthalmic Lenses: HSN `9001` ($12\%$)
  - Contact Lens Solutions: HSN `3307` ($18\%$)
- Dynamic Intra-state (**CGST + SGST**) vs Inter-state (**IGST**) tax splitting based on place of supply.
- Advance deposit handling and balance collection upon delivery.
- Printable GST Tax Invoices formatted with HSN tax summaries and bank remittance details.

### 4. Lens Laboratory & 8-Point Optical QC
- Automated Lab Job Card generation snapshotting exact optical parameters.
- Stage-by-stage pipeline tracking with technician logs.
- Mandatory **8-Point Optical QC Verification**:
  1. Lens power (SPH, CYL, Axis) within optical tolerance.
  2. Optical center and pupillary distance alignment.
  3. Fitting height and segment height verification.
  4. Lens edging bevel and frame groove fit.
  5. Surface inspection (no scratches or coating crazing).
  6. Frame 4-point bench alignment.
  7. Screws tight and lens security.
  8. Cleanliness and case packaging.

### 5. Spectacle Repairs & Warranty Service
- Intake bench for frame solders, screw replacements, nose pad cushion changes, and ultrasonic cleaning.
- 12-month manufacturer frame and lens coating warranty tracking.

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.2+ (PHP 8.4 recommended) with `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`, `gd`, `zip`
- MySQL 8.0+ or MariaDB 10.4+
- Composer 2.x & Node.js 18+

### Installation
```bash
# 1. Clone repository
git clone https://github.com/webotapp/optical-erp.git
cd optical-erp

# 2. Install dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Run database migrations & rich demo seeder
php artisan migrate --seed

# 5. Build assets & serve
npm run build
php artisan serve
```

---

## 👥 Default Demo Credentials

| Role | Email | Password |
|---|---|---|
| **Super Admin / Owner** | `admin@opticalerp.test` | `Password@123` |
| **Store Manager** | `manager@opticalerp.test` | `Password@123` |
| **Optometrist** | `optom@opticalerp.test` | `Password@123` |
| **Lab Technician** | `lab@opticalerp.test` | `Password@123` |
| **POS Cashier** | `cashier@opticalerp.test` | `Password@123` |

---

## 🧪 Automated Testing

Execute the test suites:
```bash
php artisan test
```
All 16 test suites covering clinical calculations, Indian GST splitting, POS ordering, lab workflows, QC sign-off, and role-based dashboards pass with 60 assertions.
