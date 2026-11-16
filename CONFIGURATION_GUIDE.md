# Optical Store Management ERP — System Configuration Guide

## 1. Environment Configuration (.env)
```env
APP_NAME="VisionCraft Optical ERP"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://optical.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=optical_erp
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

## 2. Company & Branch Setup
- Navigate to Database or Company Settings to set company Legal Name, Registered Address, PAN, GSTIN, Bank Details (Account Number, IFSC), and Invoice Prefix.
- Configure branches with individual GSTINs if operating across state lines, and toggle `has_lab` flag for branches equipped with in-house surfacing and edging machines.

## 3. Optical Product Catalog Configuration
- Define Brands (e.g. Ray-Ban, Essilor, Zeiss, Silhouette, Oakley).
- Define Categories with default HSN codes and GST rates:
  - Frames: HSN 9003, 12%
  - Corrective Lenses: HSN 9001, 12%
  - Sunglasses: HSN 9004, 18%
  - Contact Lenses: HSN 9001, 12%
  - Contact Lens Solution: HSN 3307, 18%

## 4. Hardware Integrations (POS)
- **Barcode Scanners**: Compatible with standard USB/Bluetooth HID 1D/2D barcode scanners (emulates keyboard keystrokes terminating in Enter).
- **Receipt Printers**: Standard 80mm ESC/POS thermal printers.
- **A4 / Letter Invoices**: Laser or inkjet printers using the printable GST Tax Invoice template.

