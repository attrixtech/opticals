# Optical Store Management ERP — Optical Business Processes Manual

## 1. Optometry & Clinical Consultation
- **Subjective & Objective Refraction**: Autorefractor capture followed by trial frame verification with Snellen / LogMAR visual acuity charts.
- **Astigmatic Power & Transposition**: Cylinder values can be represented in plus or minus cylinder format; the ERP automatically provides transpose equivalence.
- **Pupillary Distance (PD)**: Accurate measurement of Binocular Distance PD, Monocular Right/Left PD, and Near PD is required before progressive lens dispensing.

## 2. Spectacle Dispensing & Frame Fitting
- **Frame Geometrical Box System**:
  - `A measurement`: Horizontal width of the lens box.
  - `B measurement`: Vertical height of the lens box.
  - `DBL`: Distance between lenses (bridge width).
  - `ED`: Effective diameter (longest diagonal diameter).
- **Fitting Height for Progressives**: Measured from lowest point of eyewire to pupil center (minimum 14mm-18mm depending on lens corridor).
- **Pantoscopic Tilt**: The angle of the lens plane relative to the frontal plane (typically 7° to 12°).
- **Vertex Distance**: Distance between back surface of lens and apex of cornea (typically 12mm to 14mm).

## 3. Laboratory Surfacing & Edging
- **Job Card Snapshot**: The ERP freezes the exact prescription, frame parameters, and lens blanks at the moment of order confirmation.
- **Stage Progression**: Job Created -> Lens Picked -> CNC Surfacing -> AR & Hard Coating -> 3D Patternless Edging -> Frame Mounting -> 8-Point Optical QC -> Ready for Delivery.
- **Rework Handling**: QC rejection logs reasons (Axis off > 2°, power error, surface scratch, bevel mismatch) and routes lens back to edging or blank pick.

## 4. Indian GST Billing & Financial Workflows
- Automatic classification of frames (HSN 9003, 12%), lenses (HSN 9001, 12%), sunglasses (HSN 9004, 18%), solutions (HSN 3307, 18%).
- Split tax into CGST and SGST for intra-state sales, or single IGST for inter-state supply.
- Advance deposits tracked against order receipts; remaining balance due upon customer fitting.

