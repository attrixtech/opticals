# Optical Store Management ERP — Comprehensive Workflow Guide

## 1. Complete End-to-End Optical Retail Lifecycle

```
[1. Walk-In / Lead]
       │
       ▼
[2. Customer CRM Registration]
       │
       ├─────────────────────────────────┐
       ▼                                 ▼
[3. Refraction / Prescription]    [3b. Frame Selection]
  - SPH, CYL, Axis, Add             - Acetate / Titanium / Metal
  - OD & OS Parameters              - A, B, DBL, ED Measurements
  - Visual Acuity (Unaided/Aided)   - Try-on & Fitting Height
       │                                 │
       └────────────────┬────────────────┘
                        ▼
             [4. Lens Customization]
               - Index (1.50 -> 1.74)
               - Progressive / Single Vision
               - Coatings: AR, Blue-Cut, Transitions
                        │
                        ▼
             [5. POS / Quotation / Order]
               - Frame + Lens + Accessory bundling
               - GST calculation (5% Frames, 12% Lenses)
               - Advance payment receipt (Cash/UPI/Card)
                        │
                        ▼
             [6. Stock Reservation & Lab Job]
               - Stock decremented or reserved
               - Lab Job Card generated with optical specs
                        │
                        ▼
             [7. Laboratory Processing]
               - Surfacing & Polishing
               - Hard Coat & Anti-Reflective Bath
               - Edging (Bevel/Groove) & Frame Mounting
                        │
                        ▼
             [8. 8-Point Optical QC Inspection]
               - Lensmeter power verification
               - Optical Center / PD alignment
               - Free of scratches / distortion
                        │
                        ├──────────────────────────┐
                   (QC Pass)                  (QC Fail)
                        ▼                          ▼
             [9. Ready For Delivery]          [Lab Rework]
               - Auto SMS / WhatsApp alert         │
                        │                          │
                        ▼                          ▼
             [10. Delivery & Final Settlement] ◄───┘
               - Final fitting adjustment on face
               - Balance payment collection
               - GST Tax Invoice printout
                        │
                        ▼
             [11. After-Sales, Warranty & Recalls]
               - 1-Year coating warranty tracking
               - Routine frame realignment & ultrasonic clean
               - Annual eye test reminder automation
```

---

## 2. Lens Laboratory Workflow Detailed Steps
1. **Job Queuing**: Sales order generates `LJC-YYYYMMDD-XXXX`. Order specifications, power values, and PD are snapshot into the job card.
2. **Blank Allocation**: Correct base curve and lens blank diameter (e.g. 65mm, 70mm, 75mm) are selected from warehouse stock.
3. **Blocking & Surfacing**: Lens blank is mounted on an alloy/wax blocker and CNC generator surfaces the prescription curve.
4. **Coating**: Lens undergoes ultrasonic cleaning, dip-lacquering (hard coat), and thermal/vacuum anti-reflective coating.
5. **Patternless Edging**: Frame tracer scans eyewire shape; 3D edger bevels lens circumference matching frame curve.
6. **Mounting & Alignment**: Lens is set into the frame; screws or nylon cord tension adjusted.
7. **Quality Control (QC)**: Automated and manual verification against optical tolerances (ANSI Z80.1 / ISO 21987).

---

## 3. Indian GST Tax Rules in Optical ERP
- **Frames & Sunglasses**: HSN 9003 / 9004 -> Standard 12% GST (or 18% for luxury sunglasses).
- **Corrective Ophthalmic Lenses**: HSN 9001 -> Standard 12% GST (or 5% where applicable under concessional notifications).
- **Contact Lenses**: HSN 9001 -> Standard 12% GST.
- **Contact Lens Solutions**: HSN 3307 -> Standard 18% GST.
- **Optometry & Clinical Consultation Services**: SAC 9993 -> Healthcare exempt from GST.
- **Intra-State vs Inter-State Rule**:
  - If Customer State == Store State: Split tax equally into **CGST** and **SGST** (e.g. 6% + 6% = 12%).
  - If Customer State != Store State: Apply single **IGST** (e.g. 12%).

