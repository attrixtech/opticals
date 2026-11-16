# Optical Store Management ERP — User Operating Manual

## 1. Quick Start Guide
Access the application at: `http://localhost:8000` or `http://localhost/optical/public`

Default demo logins:
- **Admin**: `admin@opticalerp.test` / `Password@123`
- **Store Manager**: `manager@opticalerp.test` / `Password@123`
- **Optometrist**: `optom@opticalerp.test` / `Password@123`
- **Lab Technician**: `lab@opticalerp.test` / `Password@123`
- **Cashier**: `cashier@opticalerp.test` / `Password@123`

---

## 2. Daily Optical Store Workflows

### 2.1 Registering a Patient & Entering Eye Prescription
1. Navigate to **Customer CRM** -> Click **Register New Customer**.
2. Enter the customer's Name, Phone, and City.
3. Click **+ New Refraction / Rx** to record the eye examination.
4. Enter independent **Right Eye (OD)** and **Left Eye (OS)** measurements:
   - SPH, CYL, Axis, ADD, PRISM, Base, and Visual Acuity (e.g. 6/6).
   - Distance PD & Near PD in millimeters.
5. Click **Save & Digitally Sign Prescription**.

### 2.2 Point of Sale (POS) & Order Creation
1. Navigate to **Optical POS & Sales**.
2. Select the customer from the customer dropdown.
3. Choose the customer's prescription (or leave blank for non-prescription sunglasses/solutions).
4. Scan frame/lens barcodes or click on catalog items.
5. The POS automatically calculates:
   - Taxable subtotal.
   - Intra-state (CGST 6% + SGST 6%) or Inter-state (IGST 12%/18%).
6. Enter the **Advance Deposit** amount and select payment mode (UPI, Cash, Card).
7. Click **Confirm Order & Generate Lab Job**.
8. The order is created, inventory is reserved, an official GST Tax Invoice is generated, and a Lab Job Card is sent to the lens laboratory.

### 2.3 Lens Laboratory Surfacing & Edging
1. Navigate to **Lens Lab & QC**.
2. Technicians view job cards queued in **Job Created**, **Surfacing**, or **Edging**.
3. Click **Open Job Card** to view the optical prescription snapshot.
4. Update the stage as work progresses.
5. When mounting is complete, move the job to **QC Pending**.

### 2.4 8-Point Optical Quality Control Inspection
1. The Optometrist or Lab Manager opens the job card in **QC Pending**.
2. Verify the 8 checklist items:
   - SPH / CYL / Axis power verified on lensmeter.
   - Optical Center & PD aligned.
   - Fitting / Segment height verified.
   - Edging bevel fits frame groove snugly.
   - Lens surface clean with zero scratches or coating flaws.
   - Frame 4-point bench alignment verified.
   - Screws tightened securely.
   - Cleanliness and packaging.
3. Select **Passed** -> Click **Submit Quality Inspection Decision**.
4. The sales order automatically updates to **Ready for Fitting/Delivery**.

### 2.5 Order Delivery & Balance Settlement
1. When the customer arrives for collection, open **Sales Orders** -> View the order.
2. In the right panel, collect the due balance under **Collect Due Balance**.
3. Click **Mark Customer Delivered**.
4. Click **View Tax Invoice** to print the official GST Tax Invoice.

### 2.6 Frame Repairs
1. Navigate to **Repairs & Warranty** -> Click **Intake Frame Repair Job**.
2. Record customer, frame details, issue (e.g. broken hinge screw), and estimated cost.
3. Update repair status from `Received` -> `In Repair` -> `Ready` -> `Delivered`.

