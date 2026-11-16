# Optical Store Management ERP — REST API & Integration Specification

## 1. Authentication
API endpoints use Bearer Token authorization. Add header:
```
Authorization: Bearer <API_TOKEN>
Accept: application/json
```

## 2. Core Endpoints

### 2.1 Customer CRM
- `GET /api/v1/customers`: List customers with balance and loyalty points.
- `POST /api/v1/customers`: Register walk-in customer.
- `GET /api/v1/customers/{id}`: Detailed customer profile with past prescriptions and purchase history.

### 2.2 Optical Prescriptions
- `GET /api/v1/prescriptions?customer_id={id}`: List customer refractions.
- `POST /api/v1/prescriptions`: Submit signed prescription (OD/OS: SPH, CYL, AXIS, ADD, PRISM, PD).

### 2.3 Catalog & Stock
- `GET /api/v1/products`: Product catalog with available stock levels and optical attributes.
- `GET /api/v1/products/barcode/{code}`: Instant barcode lookup for POS scanning.

### 2.4 Sales Orders & Lab Jobs
- `POST /api/v1/orders`: Submit confirmed sales order with advance payment deposit.
- `GET /api/v1/orders/{id}/status`: Public tracking endpoint for customer tracking via SMS/WhatsApp links.
- `GET /api/v1/laboratory/jobs`: List lab job cards filtered by stage.
- `POST /api/v1/laboratory/jobs/{id}/stage`: Update lab pipeline stage.

