<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Branch;
use App\Models\CashRegister;
use App\Models\Category;
use App\Models\ChartOfAccount;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\EyeMeasurement;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\Invoice;
use App\Models\LabJobCard;
use App\Models\LabJobLog;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\ProductContactLens;
use App\Models\ProductFrame;
use App\Models\ProductLens;
use App\Models\QualityControl;
use App\Models\Repair;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Warranty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Company Profile
        $company = Company::create([
            'name' => 'thrumble.opticals',
            'code' => 'TO-HQ',
            'email' => 'contact@thrumble.opticals',
            'phone' => '+91 98200 12345',
            'gstin' => '27AAACV1234F1Z5',
            'pan' => 'AAACV1234F',
            'currency' => 'INR',
            'currency_symbol' => '₹',
            'address' => 'Shop 12-14, Ground Floor, Phoenix Millennium Mall, Senapati Bapat Marg, Lower Parel',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'state_code' => '27',
            'pincode' => '400013',
            'bank_name' => 'HDFC Bank',
            'bank_account_no' => '50200012345678',
            'bank_ifsc' => 'HDFC0000123',
            'invoice_prefix' => 'TO-MUM-',
            'is_active' => true,
        ]);

        // 2. Branches
        $branchMumbai = Branch::create([
            'company_id' => $company->id,
            'name' => 'Mumbai Flagship & Surfacing Lab',
            'code' => 'MUM-01',
            'phone' => '+91 22 2490 8899',
            'email' => 'mumbai@thrumble.opticals',
            'gstin' => '27AAACV1234F1Z5',
            'state_code' => '27',
            'address' => 'Phoenix Millennium Mall, Lower Parel',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400013',
            'is_headquarters' => true,
            'has_lab' => true,
            'is_active' => true,
        ]);

        $branchPune = Branch::create([
            'company_id' => $company->id,
            'name' => 'Pune Koregaon Park Boutique',
            'code' => 'PUN-01',
            'phone' => '+91 20 2615 4433',
            'email' => 'pune@thrumble.opticals',
            'gstin' => '27AAACV1234F1Z5',
            'state_code' => '27',
            'address' => 'North Main Road, Koregaon Park',
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'pincode' => '411001',
            'is_headquarters' => false,
            'has_lab' => false,
            'is_active' => true,
        ]);

        // 3. Warehouses
        $mainWarehouse = Warehouse::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'name' => 'Mumbai Central Lens & Frame Depot',
            'code' => 'WH-MUM-01',
            'location' => 'Lower Parel Basement Vault',
            'is_default' => true,
            'is_active' => true,
        ]);

        $puneWarehouse = Warehouse::create([
            'company_id' => $company->id,
            'branch_id' => $branchPune->id,
            'name' => 'Pune Storefront Counter Stock',
            'code' => 'WH-PUN-01',
            'location' => 'Koregaon Display Backroom',
            'is_default' => false,
            'is_active' => true,
        ]);

        // 4. Users with Roles
        $admin = User::create([
            'name' => 'thrumble.opticals Admin (Super Admin)',
            'email' => 'admin@thrumble.opticals',
            'password' => Hash::make('Password@123'),
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'role' => 'admin',
            'phone' => '+91 98000 11111',
            'is_active' => true,
        ]);

        $manager = User::create([
            'name' => 'Rajesh Sharma (Store Manager)',
            'email' => 'manager@thrumble.opticals',
            'password' => Hash::make('Password@123'),
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'role' => 'manager',
            'phone' => '+91 98000 22222',
            'is_active' => true,
        ]);

        $optometrist = User::create([
            'name' => 'Dr. Ananya Deshmukh, M.Optom',
            'email' => 'optom@thrumble.opticals',
            'password' => Hash::make('Password@123'),
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'role' => 'optometrist',
            'phone' => '+91 98000 33333',
            'is_active' => true,
        ]);

        $labTech = User::create([
            'name' => 'Suresh Patil (Senior Edging Technician)',
            'email' => 'lab@thrumble.opticals',
            'password' => Hash::make('Password@123'),
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'role' => 'lab_technician',
            'phone' => '+91 98000 44444',
            'is_active' => true,
        ]);

        $cashier = User::create([
            'name' => 'Pooja Kulkarni (POS Cashier)',
            'email' => 'cashier@thrumble.opticals',
            'password' => Hash::make('Password@123'),
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'role' => 'cashier',
            'phone' => '+91 98000 55555',
            'is_active' => true,
        ]);

        // 5. Brands
        $brandsData = [
            ['name' => 'Ray-Ban', 'code' => 'RB'],
            ['name' => 'Essilor Crizal', 'code' => 'ESS'],
            ['name' => 'Carl Zeiss', 'code' => 'CZ'],
            ['name' => 'Oakley', 'code' => 'OK'],
            ['name' => 'Titan Eyeplus', 'code' => 'TEP'],
            ['name' => 'Bausch & Lomb', 'code' => 'BL'],
            ['name' => 'Hoya Lens', 'code' => 'HY'],
            ['name' => 'Silhouette', 'code' => 'SLH'],
        ];
        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['name']] = Brand::create(array_merge($b, ['company_id' => $company->id]));
        }

        // 6. Categories
        $categoriesData = [
            ['name' => 'Optical Frames', 'type' => 'frame', 'default_hsn' => '9003', 'default_tax_rate' => 12.00],
            ['name' => 'Ophthalmic Lenses', 'type' => 'lens', 'default_hsn' => '9001', 'default_tax_rate' => 12.00],
            ['name' => 'Contact Lenses', 'type' => 'contact_lens', 'default_hsn' => '9001', 'default_tax_rate' => 12.00],
            ['name' => 'Sunglasses', 'type' => 'sunglasses', 'default_hsn' => '9004', 'default_tax_rate' => 18.00],
            ['name' => 'Accessories & Solutions', 'type' => 'accessory', 'default_hsn' => '3307', 'default_tax_rate' => 18.00],
        ];
        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['name']] = Category::create(array_merge($c, ['company_id' => $company->id]));
        }

        // 7. Suppliers
        $supplierEssilor = Supplier::create([
            'company_id' => $company->id,
            'name' => 'Essilor India Pvt Ltd',
            'supplier_code' => 'SUP-ESS-01',
            'contact_person' => 'Ramesh Nair',
            'phone' => '+91 80 4123 4567',
            'email' => 'orders@essilorindia.com',
            'gstin' => '29AAACE1234M1Z2',
            'state_code' => '29',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'current_balance' => 0.00,
        ]);

        $supplierLuxottica = Supplier::create([
            'company_id' => $company->id,
            'name' => 'Luxottica India Eyewear Pvt Ltd',
            'supplier_code' => 'SUP-LUX-01',
            'contact_person' => 'Deepak Verma',
            'phone' => '+91 124 456 7890',
            'email' => 'sales@luxottica.in',
            'gstin' => '06AAACL9876P1Z3',
            'state_code' => '06',
            'city' => 'Gurugram',
            'state' => 'Haryana',
            'current_balance' => 0.00,
        ]);

        // 8. Products
        $p1 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Ray-Ban']->id,
            'category_id' => $categories['Optical Frames']->id,
            'name' => 'Ray-Ban RX5184 New Wayfarer Optical Frame',
            'sku' => 'RB-RX5184-2000',
            'barcode' => '805289123456',
            'hsn_code' => '9003',
            'product_type' => 'frame',
            'purchase_price' => 3200.00,
            'mrp' => 6490.00,
            'selling_price' => 5890.00,
            'tax_rate' => 12.00,
            'min_stock_alert' => 3,
            'model_no' => 'RX5184',
            'color' => 'Gloss Black',
            'description' => 'Premium Italian acetate full rim iconic optical frame.',
        ]);
        ProductFrame::create([
            'product_id' => $p1->id,
            'frame_shape' => 'wayfarer',
            'frame_material' => 'acetate',
            'rim_type' => 'full_rim',
            'gender' => 'unisex',
            'frame_size' => '52-18-145',
            'eye_size' => 52.0,
            'bridge_width' => 18.0,
            'temple_length' => 145.0,
        ]);

        $p2 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Silhouette']->id,
            'category_id' => $categories['Optical Frames']->id,
            'name' => 'Silhouette TMA Icon Featherlight Rimless Titanium Frame',
            'sku' => 'SLH-TMA-5515',
            'barcode' => '900823456789',
            'hsn_code' => '9003',
            'product_type' => 'frame',
            'purchase_price' => 9500.00,
            'mrp' => 18900.00,
            'selling_price' => 17200.00,
            'tax_rate' => 12.00,
            'min_stock_alert' => 2,
            'model_no' => 'TMA 5515',
            'color' => 'Titanium Silver',
            'description' => 'Ultra-lightweight screwless rimless frame crafted from high-tech Austrian titanium.',
        ]);
        ProductFrame::create([
            'product_id' => $p2->id,
            'frame_shape' => 'rectangle',
            'frame_material' => 'titanium',
            'rim_type' => 'rimless',
            'gender' => 'unisex',
            'frame_size' => '54-17-140',
            'eye_size' => 54.0,
            'bridge_width' => 17.0,
            'temple_length' => 140.0,
        ]);

        $p3 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Titan Eyeplus']->id,
            'category_id' => $categories['Optical Frames']->id,
            'name' => 'Titan Eyeplus Flex TR90 Lightweight Matte Frame',
            'sku' => 'TEP-TR-9042',
            'barcode' => '890123490421',
            'hsn_code' => '9003',
            'product_type' => 'frame',
            'purchase_price' => 850.00,
            'mrp' => 2499.00,
            'selling_price' => 2199.00,
            'tax_rate' => 12.00,
            'min_stock_alert' => 5,
            'model_no' => 'TR9042',
            'color' => 'Matte Navy Blue',
            'description' => 'Flexible memory plastic TR90 frame, ideal for everyday active wear.',
        ]);
        ProductFrame::create([
            'product_id' => $p3->id,
            'frame_shape' => 'rectangle',
            'frame_material' => 'tr90',
            'rim_type' => 'full_rim',
            'gender' => 'men',
            'frame_size' => '53-16-142',
            'eye_size' => 53.0,
            'bridge_width' => 16.0,
            'temple_length' => 142.0,
        ]);

        $p4 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Essilor Crizal']->id,
            'category_id' => $categories['Ophthalmic Lenses']->id,
            'name' => 'Essilor Crizal Prevencia 1.56 Blue-UV Filter Lenses (Pair)',
            'sku' => 'ESS-CRZ-PREV-156',
            'barcode' => '366224156001',
            'hsn_code' => '9001',
            'product_type' => 'lens',
            'purchase_price' => 1400.00,
            'mrp' => 3800.00,
            'selling_price' => 3400.00,
            'tax_rate' => 12.00,
            'min_stock_alert' => 10,
            'model_no' => 'Crizal Prevencia 1.56',
            'color' => 'Clear with Blue Reflection',
            'description' => 'Selective light filter blocking harmful blue-violet rays and UV.',
        ]);
        ProductLens::create([
            'product_id' => $p4->id,
            'lens_index' => '1.56',
            'lens_design' => 'single_vision',
            'lens_material' => 'cr39',
            'coating' => 'Crizal Prevencia Anti-Glare + Blue Filter',
            'is_blue_cut' => true,
        ]);

        $p5 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Carl Zeiss']->id,
            'category_id' => $categories['Ophthalmic Lenses']->id,
            'name' => 'Zeiss Light 3D 1.60 Freeform Digital Progressive Lenses (Pair)',
            'sku' => 'CZ-PROG-3D-160',
            'barcode' => '404280160002',
            'hsn_code' => '9001',
            'product_type' => 'lens',
            'purchase_price' => 4500.00,
            'mrp' => 12500.00,
            'selling_price' => 11000.00,
            'tax_rate' => 12.00,
            'min_stock_alert' => 4,
            'model_no' => 'Zeiss Light 3D 1.60',
            'color' => 'DuraVision Platinum AR',
            'description' => 'Precision digital freeform progressive lens with wide reading channel and seamless corridor transition.',
        ]);
        ProductLens::create([
            'product_id' => $p5->id,
            'lens_index' => '1.60',
            'lens_design' => 'progressive_digital_freeform',
            'lens_material' => 'polycarbonate',
            'coating' => 'DuraVision Platinum Multi-Coat',
            'is_blue_cut' => true,
        ]);

        $p6 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Bausch & Lomb']->id,
            'category_id' => $categories['Contact Lenses']->id,
            'name' => 'Bausch & Lomb SofLens 59 Monthly Disposable (Pack of 6)',
            'sku' => 'BL-SOFLENS-59',
            'barcode' => '078587123987',
            'hsn_code' => '9001',
            'product_type' => 'contact_lens',
            'purchase_price' => 620.00,
            'mrp' => 1350.00,
            'selling_price' => 1199.00,
            'tax_rate' => 12.00,
            'min_stock_alert' => 8,
            'model_no' => 'SofLens 59',
            'description' => 'Deposit-resistant monthly soft contact lenses with hilafilcon B material.',
        ]);
        ProductContactLens::create([
            'product_id' => $p6->id,
            'base_curve' => 8.60,
            'diameter' => 14.2,
            'wear_duration' => 'monthly',
            'water_content' => '59%',
            'pack_size' => 6,
        ]);

        $p7 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Ray-Ban']->id,
            'category_id' => $categories['Sunglasses']->id,
            'name' => 'Ray-Ban RB3025 Aviator Classic Polarized Sunglasses',
            'sku' => 'RB-3025-00158',
            'barcode' => '805289302588',
            'hsn_code' => '9004',
            'product_type' => 'sunglasses',
            'purchase_price' => 5200.00,
            'mrp' => 10990.00,
            'selling_price' => 9990.00,
            'tax_rate' => 18.00,
            'min_stock_alert' => 2,
            'model_no' => 'RB3025',
            'color' => 'Arista Gold / G-15 Green Polarized',
            'description' => 'Original 1937 pilot teardrop design featuring 100% UV400 polarized crystal lenses.',
        ]);

        $p8 = Product::create([
            'company_id' => $company->id,
            'brand_id' => $brands['Titan Eyeplus']->id,
            'category_id' => $categories['Accessories & Solutions']->id,
            'name' => 'OptiClean Anti-Static Lens Cleaner Spray (60ml) + Microfiber Cloth',
            'sku' => 'OPT-CLEAN-60ML',
            'barcode' => '890123412345',
            'hsn_code' => '3307',
            'product_type' => 'accessory',
            'purchase_price' => 65.00,
            'mrp' => 250.00,
            'selling_price' => 199.00,
            'tax_rate' => 18.00,
            'min_stock_alert' => 20,
            'description' => 'Alcohol-free anti-static formula safe for anti-reflective coatings and camera lenses.',
        ]);

        // 9. Inventory Stocks
        $allProducts = [$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8];
        $quantities = [15, 8, 25, 40, 20, 30, 10, 100];
        
        foreach ($allProducts as $idx => $prod) {
            $qty = $quantities[$idx];
            InventoryStock::create([
                'company_id' => $company->id,
                'branch_id' => $branchMumbai->id,
                'warehouse_id' => $mainWarehouse->id,
                'product_id' => $prod->id,
                'quantity' => $qty,
                'reserved_quantity' => 0,
            ]);

            InventoryMovement::create([
                'company_id' => $company->id,
                'branch_id' => $branchMumbai->id,
                'warehouse_id' => $mainWarehouse->id,
                'product_id' => $prod->id,
                'movement_type' => 'initial_stock',
                'quantity' => $qty,
                'balance_after' => $qty,
                'unit_cost' => $prod->purchase_price,
                'reference_type' => 'OpeningBalance',
                'notes' => 'Opening inventory balance',
                'created_by' => $admin->id,
            ]);
        }

        // 10. Chart of Accounts
        $coaData = [
            ['account_code' => '1001', 'account_name' => 'Store Cash in Hand', 'account_type' => 'asset'],
            ['account_code' => '1002', 'account_name' => 'HDFC Current Bank Account', 'account_type' => 'asset'],
            ['account_code' => '1003', 'account_name' => 'Trade Accounts Receivable', 'account_type' => 'asset'],
            ['account_code' => '1004', 'account_name' => 'Optical Merchandise Inventory', 'account_type' => 'asset'],
            ['account_code' => '2001', 'account_name' => 'Trade Accounts Payable (Suppliers)', 'account_type' => 'liability'],
            ['account_code' => '2002', 'account_name' => 'Output CGST Payable', 'account_type' => 'liability'],
            ['account_code' => '2003', 'account_name' => 'Output SGST Payable', 'account_type' => 'liability'],
            ['account_code' => '2004', 'account_name' => 'Output IGST Payable', 'account_type' => 'liability'],
            ['account_code' => '4001', 'account_name' => 'Optical Retail Sales Revenue', 'account_type' => 'revenue'],
            ['account_code' => '4002', 'account_name' => 'Clinical Consultation Fees', 'account_type' => 'revenue'],
            ['account_code' => '5001', 'account_name' => 'Cost of Goods Sold (Frames & Lenses)', 'account_type' => 'expense'],
            ['account_code' => '5002', 'account_name' => 'Lens Laboratory Surfacing & Edging Expense', 'account_type' => 'expense'],
            ['account_code' => '5003', 'account_name' => 'Showroom Rent & Utilities', 'account_type' => 'expense'],
        ];
        foreach ($coaData as $c) {
            ChartOfAccount::create(array_merge($c, ['company_id' => $company->id]));
        }

        // 11. Expense Categories & Sample Expenses
        $expCatRent = ExpenseCategory::create(['company_id' => $company->id, 'name' => 'Showroom Rent & Electricity', 'code' => 'EXP-RENT']);
        $expCatCons = ExpenseCategory::create(['company_id' => $company->id, 'name' => 'Lab Consumables & Pads', 'code' => 'EXP-LAB']);
        $expCatTea = ExpenseCategory::create(['company_id' => $company->id, 'name' => 'Staff Refreshments & Hospitality', 'code' => 'EXP-TEA']);

        Expense::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'expense_category_id' => $expCatCons->id,
            'expense_date' => now()->subDays(2)->toDateString(),
            'amount' => 1850.00,
            'payment_mode' => 'cash',
            'reference_no' => 'VOUCH-089',
            'description' => 'Diamond edging wheel dressing stick & blocking pads',
            'user_id' => $manager->id,
        ]);

        // 12. POS Cash Register
        CashRegister::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'user_id' => $cashier->id,
            'opened_at' => now()->startOfDay()->addHours(9),
            'opening_amount' => 5000.00,
            'cash_sales' => 8500.00,
            'cash_refunds' => 0.00,
            'expected_closing' => 13500.00,
            'status' => 'open',
            'notes' => 'Morning counter float verified',
        ]);

        // 13. Customers
        $c1 = Customer::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_code' => 'CUST-2026-001',
            'name' => 'Vikram Singhania',
            'phone' => '+91 98211 44556',
            'email' => 'vikram.singhania@email.com',
            'gender' => 'male',
            'date_of_birth' => '1982-05-14',
            'address' => 'Flat 802, Kalpataru Heights, Worli',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'state_code' => '27',
            'pincode' => '400018',
            'outstanding_balance' => 0.00,
            'loyalty_points' => 150,
            'medical_notes' => 'Mild astigmatism, high screen time computer programmer.',
        ]);

        $c2 = Customer::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_code' => 'CUST-2026-002',
            'name' => 'Meera Nair',
            'phone' => '+91 98199 77889',
            'email' => 'meera.nair@corporate.in',
            'gender' => 'female',
            'date_of_birth' => '1976-11-20',
            'address' => 'B-44, Hiranandani Gardens, Powai',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'state_code' => '27',
            'pincode' => '400076',
            'gstin' => '27AAACN5566K1Z9',
            'outstanding_balance' => 2500.00,
            'loyalty_points' => 320,
            'medical_notes' => 'Presbyopia onset. Requires wide progressive corridor.',
        ]);

        // Prescription for Customer 1
        $rx1 = Prescription::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_id' => $c1->id,
            'prescription_number' => 'RX-2026-00101',
            'prescription_date' => now()->subDays(5)->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'doctor_name' => 'Dr. Ananya Deshmukh',
            'clinic_hospital' => 'thrumble.opticals Optometry Clinic',
            'prescription_type' => 'single_vision',
            'od_sph' => -1.75,
            'od_cyl' => -0.50,
            'od_axis' => 85,
            'od_add' => 0.00,
            'od_va_distance' => '6/6',
            'od_va_near' => 'N6',
            'os_sph' => -2.25,
            'os_cyl' => -0.75,
            'os_axis' => 95,
            'os_add' => 0.00,
            'os_va_distance' => '6/6',
            'os_va_near' => 'N6',
            'pd_distance' => 64.0,
            'pd_near' => 61.0,
            'clinical_notes' => 'Blue-cut coating strongly recommended for 8+ hours digital display usage.',
            'is_verified' => true,
            'verified_by' => $optometrist->id,
            'created_by' => $optometrist->id,
        ]);

        // Prescription for Customer 2 (Progressive)
        $rx2 = Prescription::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_id' => $c2->id,
            'prescription_number' => 'RX-2026-00102',
            'prescription_date' => now()->subDays(1)->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'doctor_name' => 'Dr. Ananya Deshmukh',
            'clinic_hospital' => 'thrumble.opticals Optometry Clinic',
            'prescription_type' => 'progressive',
            'od_sph' => 1.00,
            'od_cyl' => -0.25,
            'od_axis' => 180,
            'od_add' => 1.75,
            'od_va_distance' => '6/6',
            'od_va_near' => 'N6',
            'os_sph' => 1.25,
            'os_cyl' => -0.50,
            'os_axis' => 175,
            'os_add' => 1.75,
            'os_va_distance' => '6/6',
            'os_va_near' => 'N6',
            'pd_distance' => 62.0,
            'pd_near' => 59.0,
            'clinical_notes' => 'First-time progressive wearer. Verified fitting height at pupil center.',
            'is_verified' => true,
            'verified_by' => $optometrist->id,
            'created_by' => $optometrist->id,
        ]);

        // Eye Measurements for Customer 2
        $em2 = EyeMeasurement::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_id' => $c2->id,
            'measurement_date' => now()->subDays(1)->toDateString(),
            'pd_binocular' => 62.0,
            'pd_right' => 31.0,
            'pd_left' => 31.0,
            'pd_near' => 59.0,
            'frame_a_width' => 52.0,
            'frame_b_height' => 38.0,
            'frame_dbl' => 18.0,
            'frame_ed' => 56.0,
            'frame_temple_length' => 140.0,
            'fitting_height_right' => 21.0,
            'fitting_height_left' => 21.0,
            'pantoscopic_tilt' => 8.5,
            'face_form_angle' => 5.0,
            'vertex_distance' => 12.0,
            'measured_by' => $optometrist->id,
            'notes' => 'Measurements taken with video centering pupilometer system.',
        ]);

        // 14. Sales Order 1 (Completed & Delivered)
        $order1 = SalesOrder::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_id' => $c1->id,
            'prescription_id' => $rx1->id,
            'salesperson_id' => $cashier->id,
            'order_number' => 'ORD-2026-1001',
            'order_date' => now()->subDays(4)->toDateString(),
            'delivery_due_date' => now()->subDays(1)->toDateString(),
            'delivered_at' => now()->subDays(1)->toDateTimeString(),
            'subtotal' => 9290.00,
            'discount_amount' => 500.00,
            'taxable_amount' => 8790.00,
            'cgst_amount' => 527.40,
            'sgst_amount' => 527.40,
            'igst_amount' => 0.00,
            'round_off' => 0.20,
            'total_amount' => 9845.00,
            'paid_amount' => 9845.00,
            'balance_amount' => 0.00,
            'order_status' => 'delivered',
            'payment_status' => 'paid',
            'notes' => 'Customer picked up and tested vision. Highly satisfied.',
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order1->id,
            'product_id' => $p1->id,
            'item_name' => $p1->name,
            'item_type' => 'frame',
            'hsn_code' => '9003',
            'quantity' => 1,
            'unit_price' => 5890.00,
            'discount_amount' => 500.00,
            'taxable_amount' => 5390.00,
            'tax_rate' => 12.00,
            'cgst_amount' => 323.40,
            'sgst_amount' => 323.40,
            'igst_amount' => 0.00,
            'total_amount' => 6036.80,
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order1->id,
            'product_id' => $p4->id,
            'item_name' => $p4->name,
            'item_type' => 'lens',
            'hsn_code' => '9001',
            'eye_target' => 'both',
            'quantity' => 1,
            'unit_price' => 3400.00,
            'discount_amount' => 0.00,
            'taxable_amount' => 3400.00,
            'tax_rate' => 12.00,
            'cgst_amount' => 204.00,
            'sgst_amount' => 204.00,
            'igst_amount' => 0.00,
            'total_amount' => 3808.00,
        ]);

        Payment::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'sales_order_id' => $order1->id,
            'customer_id' => $c1->id,
            'receipt_number' => 'RCT-2026-0001',
            'payment_date' => now()->subDays(4)->toDateString(),
            'amount' => 5000.00,
            'payment_mode' => 'upi',
            'transaction_reference' => 'UPI/98211/349281',
            'notes' => 'Advance booking token',
            'collected_by' => $cashier->id,
        ]);

        Payment::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'sales_order_id' => $order1->id,
            'customer_id' => $c1->id,
            'receipt_number' => 'RCT-2026-0002',
            'payment_date' => now()->subDays(1)->toDateString(),
            'amount' => 4845.00,
            'payment_mode' => 'card',
            'transaction_reference' => 'HDFC-POS-7821',
            'notes' => 'Final delivery balance payment',
            'collected_by' => $cashier->id,
        ]);

        Invoice::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'sales_order_id' => $order1->id,
            'customer_id' => $c1->id,
            'invoice_number' => 'INV-2026-00101',
            'invoice_date' => now()->subDays(1)->toDateString(),
            'taxable_amount' => 8790.00,
            'cgst_amount' => 527.40,
            'sgst_amount' => 527.40,
            'igst_amount' => 0.00,
            'total_amount' => 9845.00,
            'place_of_supply' => '27',
            'is_b2b' => false,
        ]);

        $ljc1 = LabJobCard::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'sales_order_id' => $order1->id,
            'job_number' => 'LJC-2026-0001',
            'lab_type' => 'in_house',
            'optical_specifications' => [
                'od' => ['sph' => -1.75, 'cyl' => -0.50, 'axis' => 85],
                'os' => ['sph' => -2.25, 'cyl' => -0.75, 'axis' => 95],
                'pd' => 64.0,
                'frame' => 'Ray-Ban RX5184',
                'lens' => 'Crizal Prevencia 1.56',
            ],
            'stage' => 'completed',
            'priority' => 'normal',
            'technician_id' => $labTech->id,
            'started_at' => now()->subDays(3),
            'completed_at' => now()->subDays(1)->subHours(3),
            'lab_cost' => 350.00,
        ]);

        LabJobLog::create([
            'lab_job_card_id' => $ljc1->id,
            'from_stage' => 'job_created',
            'to_stage' => 'edging',
            'remarks' => 'Patternless bevel edging completed to match acetate eyewire groove',
            'user_id' => $labTech->id,
        ]);

        LabJobLog::create([
            'lab_job_card_id' => $ljc1->id,
            'from_stage' => 'edging',
            'to_stage' => 'qc_pending',
            'remarks' => 'Mounted into frame and sent for 8-point QC check',
            'user_id' => $labTech->id,
        ]);

        QualityControl::create([
            'lab_job_card_id' => $ljc1->id,
            'sales_order_id' => $order1->id,
            'inspector_id' => $manager->id,
            'check_lens_power_sph_cyl_axis' => true,
            'check_optical_center_pd' => true,
            'check_fitting_height' => true,
            'check_lens_edging_bevel_fit' => true,
            'check_lens_surface_scratches_coating' => true,
            'check_frame_four_point_alignment' => true,
            'check_screws_and_lens_security' => true,
            'check_cleanliness_and_presentation' => true,
            'qc_status' => 'passed',
            'inspected_at' => now()->subDays(1)->subHours(4),
        ]);

        Warranty::create([
            'sales_order_id' => $order1->id,
            'customer_id' => $c1->id,
            'warranty_number' => 'WAR-2026-0001',
            'product_type' => 'Ray-Ban Frame & Crizal Anti-Reflective Coating',
            'warranty_months' => 12,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->subDays(1)->addYear()->toDateString(),
            'status' => 'active',
        ]);

        // 15. Sales Order 2 (In-Lab Processing - Progressive Lens)
        $order2 = SalesOrder::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_id' => $c2->id,
            'prescription_id' => $rx2->id,
            'eye_measurement_id' => $em2->id,
            'salesperson_id' => $cashier->id,
            'order_number' => 'ORD-2026-1002',
            'order_date' => now()->subDays(1)->toDateString(),
            'delivery_due_date' => now()->addDays(2)->toDateString(),
            'subtotal' => 28200.00,
            'discount_amount' => 1000.00,
            'taxable_amount' => 27200.00,
            'cgst_amount' => 1632.00,
            'sgst_amount' => 1632.00,
            'igst_amount' => 0.00,
            'round_off' => 0.00,
            'total_amount' => 30464.00,
            'paid_amount' => 15000.00,
            'balance_amount' => 15464.00,
            'order_status' => 'in_lab',
            'payment_status' => 'partially_paid',
            'notes' => 'Urgent VIP order. Premium Zeiss Light 3D progressive.',
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order2->id,
            'product_id' => $p2->id,
            'item_name' => $p2->name,
            'item_type' => 'frame',
            'hsn_code' => '9003',
            'quantity' => 1,
            'unit_price' => 17200.00,
            'discount_amount' => 1000.00,
            'taxable_amount' => 16200.00,
            'tax_rate' => 12.00,
            'cgst_amount' => 972.00,
            'sgst_amount' => 972.00,
            'igst_amount' => 0.00,
            'total_amount' => 18144.00,
        ]);

        SalesOrderItem::create([
            'sales_order_id' => $order2->id,
            'product_id' => $p5->id,
            'item_name' => $p5->name,
            'item_type' => 'lens',
            'hsn_code' => '9001',
            'eye_target' => 'both',
            'quantity' => 1,
            'unit_price' => 11000.00,
            'discount_amount' => 0.00,
            'taxable_amount' => 11000.00,
            'tax_rate' => 12.00,
            'cgst_amount' => 660.00,
            'sgst_amount' => 660.00,
            'igst_amount' => 0.00,
            'total_amount' => 12320.00,
        ]);

        Payment::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'sales_order_id' => $order2->id,
            'customer_id' => $c2->id,
            'receipt_number' => 'RCT-2026-0003',
            'payment_date' => now()->subDays(1)->toDateString(),
            'amount' => 15000.00,
            'payment_mode' => 'upi',
            'transaction_reference' => 'UPI/CORP/8899120',
            'notes' => '50% Advance booking deposit',
            'collected_by' => $cashier->id,
        ]);

        LabJobCard::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'sales_order_id' => $order2->id,
            'job_number' => 'LJC-2026-0002',
            'lab_type' => 'in_house',
            'optical_specifications' => [
                'od' => ['sph' => 1.00, 'cyl' => -0.25, 'axis' => 180, 'add' => 1.75],
                'os' => ['sph' => 1.25, 'cyl' => -0.50, 'axis' => 175, 'add' => 1.75],
                'pd_right' => 31.0,
                'pd_left' => 31.0,
                'fitting_height_right' => 21.0,
                'fitting_height_left' => 21.0,
                'frame' => 'Silhouette TMA Icon 5515 Rimless',
                'lens' => 'Zeiss Light 3D 1.60 Freeform Progressive',
            ],
            'stage' => 'edging',
            'priority' => 'urgent',
            'technician_id' => $labTech->id,
            'started_at' => now()->subHours(6),
            'estimated_completion_at' => now()->addHours(18),
            'lab_cost' => 650.00,
            'notes' => 'Requires rimless lens drilling with 1.4mm drill bit coordinates.',
        ]);

        // 16. Frame Repair Record
        Repair::create([
            'company_id' => $company->id,
            'branch_id' => $branchMumbai->id,
            'customer_id' => $c1->id,
            'repair_number' => 'REP-2026-001',
            'frame_description' => 'Oakley Holbrook Acetate Sunglasses',
            'issue_description' => 'Left temple hinge screw missing and nose pad cushion replacement required.',
            'estimated_cost' => 450.00,
            'advance_paid' => 450.00,
            'status' => 'in_repair',
            'received_date' => now()->toDateString(),
            'delivery_due_date' => now()->addDays(1)->toDateString(),
            'technician_id' => $labTech->id,
            'technician_notes' => 'Spare screw matched from precision repair kit.',
        ]);
    }
}
