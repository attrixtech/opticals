# Optical Store Management ERP — Database Architecture & Schema Specification

## 1. Schema Design Principles
1. **Multi-Tenancy Isolation**: Every domain table contains `company_id` and nullable `branch_id`.
2. **High Precision Optical Optics**: SPH and CYL are stored as `DECIMAL(4,2)` to handle standard steps (e.g., `-0.25`, `+12.50`). AXIS stored as `SMALLINT UNSIGNED` (0 to 180). ADD stored as `DECIMAL(4,2)` (`+0.75` to `+4.00`).
3. **Monetary Precision**: All amounts, unit prices, discounts, GST amounts, and ledger postings use `DECIMAL(14,2)`.
4. **Referential Integrity**: Cascading foreign keys on parent-child line items; `RESTRICT` on financial and inventory historical movements.

---

## 2. Table Groups & Entities

### 2.1 Core Multi-Branch & Authorization
- `companies`: Company profile, GSTIN, PAN, Bank Details, Currency, Fiscal Year.
- `branches`: Physical optical shops, showrooms, clinical facilities, address, state_code (for GST determination).
- `warehouses`: Central optical warehouses, store backrooms, laboratory holding zones.
- `roles`: System roles (Super Admin, Branch Manager, Optometrist, Lab Technician, Cashier, Sales Consultant, Accountant).
- `permissions`: Granular capabilities.
- `users`: Staff credentials, active branch assignment, commission rates.
- `audit_logs`: Detailed mutation audit trails.

### 2.2 Customer & Clinical Prescriptions
- `customers`: Customer personal info, DOB, anniversary, loyalty points, outstanding balance, marketing preferences.
- `prescriptions`: Right Eye (OD) & Left Eye (OS) records:
  - OD: `od_sph`, `od_cyl`, `od_axis`, `od_add`, `od_prism`, `od_base`, `od_va_distance`, `od_va_near`.
  - OS: `os_sph`, `os_cyl`, `os_axis`, `os_add`, `os_prism`, `os_base`, `os_va_distance`, `os_va_near`.
  - Metadata: `prescribed_by`, `doctor_clinic_name`, `prescription_date`, `expiry_date`, `prescription_type` (Single Vision, Bifocal, Progressive, Reading, Computer), `is_verified`, `verified_by`.
- `prescription_attachments`: Uploaded paper prescriptions, lensometer printouts, autorefractor scans.
- `eye_measurements`:
  - `pd_binocular`, `pd_monocular_right`, `pd_monocular_left`, `pd_near`.
  - Frame measurements: `frame_a_width`, `frame_b_height`, `frame_dbl`, `frame_ed`, `frame_temple_length`.
  - 3D Fitting: `fitting_height_right`, `fitting_height_left`, `pantoscopic_tilt`, `face_form_angle`, `vertex_distance`, `wrap_angle`.

### 2.3 Product Catalog & Optical Attributes
- `brands`: Ray-Ban, Oakley, Essilor, Zeiss, Crizal, Hoya, Bausch & Lomb, etc.
- `categories`: Frames, Ophthalmic Lenses, Contact Lenses, Sunglasses, Solutions & Accessories.
- `products`: Master product with SKU, Barcode, HSN Code, Tax Rate (e.g. 5%, 12%, 18%), Purchase Price, MRP, Selling Price, Minimum Stock Alert.
- `product_frames`: Specific frame details (Frame Shape: Aviator, Wayfarer, Round, Cat Eye; Material: Acetate, Titanium, Metal, TR90; Rim Type: Full Rim, Semi-Rimless, Rimless; Gender: Unisex, Men, Women, Kids; Size).
- `product_lenses`: Lens Index (1.50, 1.56, 1.60, 1.67, 1.74), Lens Design (Single Vision, Progressive, Digital Freeform, Bifocal, Anti-Fatigue), Lens Material (CR-39, Polycarbonate, Trivex, High Index Glass), Coatings (Hard Coat, Anti-Reflective, Blue Light Filter, Photochromic / Transitions, Polarized).
- `product_contact_lenses`: Base Curve (BC), Diameter (DIA), Wear Duration (Daily Disposable, Monthly, Bi-weekly, Annual), Water Content, Packaging.
- `inventory_stocks`: Real-time stock counts per `(branch_id, warehouse_id, product_id)`.
- `inventory_movements`: Immutable log of every stock inbound/outbound event with reference (Purchase, Sale, Transfer, Adjustment, Lab Breakage).

### 2.4 Sales Orders, POS & Billing
- `sales_orders`:
  - `order_number`, `order_date`, `customer_id`, `branch_id`, `salesperson_id`.
  - `prescription_id`, `eye_measurement_id`.
  - Financials: `subtotal`, `discount_amount`, `taxable_amount`, `cgst_amount`, `sgst_amount`, `igst_amount`, `round_off`, `total_amount`, `paid_amount`, `balance_amount`.
  - Statuses: `order_status` (Draft, Confirmed, In Processing, Ready for QC, Ready for Delivery, Delivered, Cancelled).
  - `delivery_due_date`, `delivered_at`.
- `sales_order_items`: Line items linking to `product_id`, quantity, unit price, discount, tax rate, and specific OD/OS mounting instruction.
- `payments`: Payment date, amount, mode (Cash, Card, UPI / QR, Net Banking, Store Credit), transaction reference, receipt number.
- `invoices`: Formal GST Tax Invoice generated upon order confirmation / delivery.

### 2.5 Laboratory & Quality Control
- `lab_job_cards`:
  - `job_number`, `sales_order_id`, `lab_id` (In-house or external lab name), `priority` (Normal, Urgent, Express).
  - Snapshot of optical parameters: Frame measurements, PD, Fitting heights, Lens type, SPH/CYL/AXIS/ADD.
  - Current stage: `stage` (Job Created, Lens Blank Picked, Surfacing, Coating, Edging, Frame Mounting, QC, Completed, Rework).
- `lab_job_logs`: Timestamped progress history, technician remarks, stage start/finish.
- `quality_controls`:
  - 8-Point Optical Verification:
    1. SPH / CYL / Axis power within ANSI / ISO optical tolerance.
    2. Optical Center / PD alignment check.
    3. Fitting height / Segment height verification.
    4. Lens bevel & edging fit in frame groove.
    5. Lens surface inspection (No scratches, pit marks, or coating haze).
    6. Frame alignment (Four-point touch, pantoscopic angle, temple alignment).
    7. Lens security (No loose lenses or screw tightening check).
    8. Cleanliness and aesthetic finish.
  - `qc_status`: Passed, Rejected.
  - `rejection_reason`, `inspected_by`, `inspected_at`.

### 2.6 After-Sales, Warranty & Repairs
- `repairs`: Repair job number, customer_id, frame description, issue (Broken temple, nose pad replacement, screw loose, hinge solder), estimated cost, technician, status (Received, In Repair, Ready, Returned).
- `warranties`: Product serial / order item link, warranty period (months), terms, claim history.
- `returns`: Return vouchers, credit note generation, restock authorization.

### 2.7 Purchases, Suppliers & Stock Transfers
- `suppliers`: Optical frame distributors, lens manufacturers (Essilor, Carl Zeiss, etc.), contact lens vendors.
- `purchase_orders`: Inbound PO with line items, tax breakdown, expected delivery date.
- `goods_receipt_notes` (GRN): Actual received items, batch numbers, serial / barcode assignments.
- `stock_transfers`: Inter-branch / warehouse transfers with dispatch, transit, and receipt confirmation.

### 2.8 General Ledger & Financial Accounting
- `chart_of_accounts`: Account Code, Name, Type (Asset, Liability, Equity, Revenue, Expense). Standard chart pre-populated (Cash, Bank, Accounts Receivable, Inventory, Accounts Payable, Output CGST, Output SGST, Output IGST, Input GST, Optical Sales, Lab Expenses).
- `journal_entries`: Reference number, date, narration, branch_id.
- `journal_entry_lines`: Account ID, Debit, Credit.
- `cash_registers`: Daily register session (Opening cash, cash sales, cash refunds, closing cash, cash discrepancy).

