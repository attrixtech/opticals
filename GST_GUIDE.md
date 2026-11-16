# Optical Store Management ERP — Indian GST & Tax Compliance Guide

## 1. GST Identification & Master Configurations
Every company and branch must maintain:
- **GSTIN** (15-character alphanumeric, e.g., `27AABCU9603R1ZM`).
- **State Code** (e.g., `27` for Maharashtra, `07` for Delhi, `33` for Tamil Nadu, `29` for Karnataka).
- **PAN Number** (10-character alphanumeric embedded within GSTIN).

## 2. HSN / SAC Codes in Optical Retail
| Item Category | Description | HSN / SAC | Default GST % | CGST % | SGST % | IGST % |
|---|---|---|---|---|---|---|
| Optical Frames | Metal, Plastic, Titanium spectacle frames | 9003 | 12% | 6% | 6% | 12% |
| Spectacle Lenses | Corrective single vision, bifocal, progressive lenses | 9001 | 12% | 6% | 6% | 12% |
| Sunglasses | Non-corrective sunglasses | 9004 | 18% | 9% | 9% | 18% |
| Contact Lenses | Soft, RGP, Toric, Colored contact lenses | 9001 | 12% | 6% | 6% | 12% |
| Contact Lens Solution | Disinfecting, multi-purpose saline solutions | 3307 | 18% | 9% | 9% | 18% |
| Optical Accessories | Cords, Cases, Microfiber wipes, Screwdrivers | 9003 / 3926 | 12% or 18% | 6% or 9% | 6% or 9% | 12% or 18% |
| Clinical Eye Examination | Professional Optometry services | 9993 | Exempt (0%) | 0% | 0% | 0% |

## 3. Computation Engine Formula
For a line item with selling price $P$, quantity $Q$, discount amount $D$, and tax percentage $T$:
1. $\text{Taxable Amount} = (P \times Q) - D$
2. If $\text{Place of Supply (Customer State)} == \text{Branch State}$:
   - $\text{CGST Amount} = \text{Taxable Amount} \times (T / 2) / 100$
   - $\text{SGST Amount} = \text{Taxable Amount} \times (T / 2) / 100$
   - $\text{IGST Amount} = 0$
3. Else:
   - $\text{CGST Amount} = 0$
   - $\text{SGST Amount} = 0$
   - $\text{IGST Amount} = \text{Taxable Amount} \times T / 100$
4. $\text{Line Total} = \text{Taxable Amount} + \text{CGST} + \text{SGST} + \text{IGST}$

## 4. B2B Invoicing & E-Invoicing Ready
- Customer GSTIN validation via regex format `^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$`.
- Reverse Charge Mechanism (RCM) support for unregistered vendor purchases.
- Standard Tax Invoice format including B2B details, HSN Summary table, and reverse charge flags.

