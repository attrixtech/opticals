# Optical Store Management ERP — Testing & Quality Checklist

## Automated Test Suites
Run all automated test suites via:
```bash
php artisan test
```

| Test Case | Method | Expected Result | Status |
|---|---|---|---|
| Dashboard Render | `OpticalErpWorkflowTest::test_dashboard_renders_successfully` | HTTP 200, displays sales & lab metrics | PASSED |
| Customer Registration | `OpticalErpWorkflowTest::test_customer_creation` | HTTP 302, stored in `customers` table | PASSED |
| Prescription OD/OS Entry | `OpticalErpWorkflowTest::test_prescription_creation_and_refraction` | Preserves independent OD & OS powers | PASSED |
| POS Order & Lab Job Trigger | `OpticalErpWorkflowTest::test_pos_sales_order_creation_triggers_lab_and_invoice` | Generates Order, Lab Job Card, GST Invoice | PASSED |
| 8-Point Optical QC | `OpticalErpWorkflowTest::test_quality_control_signoff` | Verifies tolerances, marks ready for delivery | PASSED |
| Spherical Equivalent | `PrescriptionServiceTest::test_spherical_equivalent_calculation` | $\text{SphEq} = \text{SPH} + \frac{\text{CYL}}{2}$ | PASSED |
| Optical Transposition | `PrescriptionServiceTest::test_optical_transposition` | Plus to minus cylinder conversion | PASSED |
| Astigmatism Axis Validation | `PrescriptionServiceTest::test_optical_validation_catches_invalid_axis` | Flags missing axis when cylinder is present | PASSED |
| Intra-State GST Calculation | `GstTaxServiceTest::test_intra_state_gst_splits_into_cgst_and_sgst` | Splits 12% into 6% CGST + 6% SGST | PASSED |
| Inter-State GST Calculation | `GstTaxServiceTest::test_inter_state_gst_applies_single_igst` | Applies single IGST rate | PASSED |

## Manual UAT Acceptance Verification
- [x] Barcode scanning in POS retrieves correct frame model and selling price.
- [x] Customer prescription links to POS cart without modifying historical values.
- [x] Advance payment creates payment receipt and computes balance due.
- [x] Lab job card advances from Job Created -> Surfacing -> Coating -> Edging -> QC.
- [x] 8-Point Optical QC sign-off marks order Ready for Delivery.
- [x] Balance payment collection updates payment status to Paid.
- [x] Official GST Tax Invoice printout renders with HSN summary and bank details.
- [x] Frame repair job intake logs issue and technician assignment.

