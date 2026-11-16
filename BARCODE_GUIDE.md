# Optical Store Management ERP — Barcode & Labeling Guide

## 1. Supported Barcode Formats
The ERP supports:
- **Code 128**: High-density alphanumeric barcode ideal for spectacle frame bridge labels (SKU + Serial).
- **EAN-13**: Standard 13-digit retail barcode for branded sunglass and contact lens packages.
- **QR Codes**: 2D QR codes on job cards and invoices containing direct URLs to customer order status and digital prescriptions.

## 2. Spectacle Frame Barcode Label Specifications
- **Recommended Label Dimensions**: 70mm x 15mm or "Dumbbell" butterfly labels that loop around the temple/arm of the spectacle frame without leaving adhesive residue on acetate or titanium.
- **Label Contents**:
  - Top Line: Store / Brand Name (e.g., `VisionCraft`)
  - Center: 1D Barcode (Code 128)
  - Bottom Line Left: Model / Color Code (e.g., `RX5184 BLK`)
  - Bottom Line Right: MRP / Selling Price (e.g., `₹5,890.00`)

## 3. Barcode Scanner Configuration
- Set the barcode scanner to standard USB HID keyboard wedge mode.
- Ensure the scanner suffix is configured to send a carriage return / enter (`\n`) key after each barcode read.
- On the Optical POS screen, scanning automatically adds the matched frame, lens, or accessory to the cart.

