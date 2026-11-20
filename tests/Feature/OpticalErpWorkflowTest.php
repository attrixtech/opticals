<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\LabJobCard;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpticalErpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;
    protected Branch $branch;
    protected Warehouse $warehouse;
    protected User $user;
    protected Customer $customer;
    protected Product $frameProduct;
    protected Product $lensProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'thrumble.opticals Test HQ',
            'code' => 'TO-TEST',
            'state_code' => '27',
            'gstin' => '27AAACV1234F1Z5',
        ]);

        $this->branch = Branch::create([
            'company_id' => $this->company->id,
            'name' => 'Mumbai Test Branch',
            'code' => 'MUM-TEST',
            'state_code' => '27',
            'has_lab' => true,
        ]);

        $this->warehouse = Warehouse::create([
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'name' => 'Central Depot',
            'code' => 'WH-TEST',
        ]);

        $this->user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.test',
            'password' => bcrypt('secret'),
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'role' => 'admin',
        ]);

        $this->customer = Customer::create([
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_code' => 'CUST-TEST-01',
            'name' => 'Test Customer',
            'phone' => '+91 99999 88888',
            'state_code' => '27',
        ]);

        $brand = Brand::create(['company_id' => $this->company->id, 'name' => 'Ray-Ban']);
        $catFrame = Category::create(['company_id' => $this->company->id, 'name' => 'Frames', 'type' => 'frame']);
        $catLens = Category::create(['company_id' => $this->company->id, 'name' => 'Lenses', 'type' => 'lens']);

        $this->frameProduct = Product::create([
            'company_id' => $this->company->id,
            'brand_id' => $brand->id,
            'category_id' => $catFrame->id,
            'name' => 'Test Aviator Frame',
            'sku' => 'TEST-FR-01',
            'barcode' => '111122223333',
            'product_type' => 'frame',
            'purchase_price' => 1000.00,
            'selling_price' => 2000.00,
            'mrp' => 2500.00,
            'tax_rate' => 12.00,
        ]);

        $this->lensProduct = Product::create([
            'company_id' => $this->company->id,
            'brand_id' => $brand->id,
            'category_id' => $catLens->id,
            'name' => 'Test Crizal 1.56 Lenses',
            'sku' => 'TEST-LN-01',
            'barcode' => '444455556666',
            'product_type' => 'lens',
            'purchase_price' => 500.00,
            'selling_price' => 1200.00,
            'mrp' => 1500.00,
            'tax_rate' => 12.00,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_renders_with_quick_fill_links(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('thrumble.opticals');
        $response->assertSee('admin@thrumble.opticals');
        $response->assertSee('fillCredentials');
    }

    public function test_user_can_login_and_logout(): void
    {
        $response = $this->post(route('login.attempt'), [
            'email' => 'admin@test.test',
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->user);

        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_login_fails(): void
    {
        $response = $this->post(route('login.attempt'), [
            'email' => 'admin@test.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('thrumble.opticals');
        $response->assertSee('Sales');
    }

    public function test_customer_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'Sunita Sharma',
            'phone' => '+91 98333 44556',
            'gender' => 'female',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'state_code' => '27',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('customers', [
            'name' => 'Sunita Sharma',
            'phone' => '+91 98333 44556',
        ]);
    }

    public function test_prescription_creation_and_refraction(): void
    {
        $response = $this->actingAs($this->user)->post(route('prescriptions.store'), [
            'customer_id' => $this->customer->id,
            'prescription_date' => now()->toDateString(),
            'prescription_type' => 'single_vision',
            'od_sph' => -1.50,
            'od_cyl' => -0.50,
            'od_axis' => 90,
            'os_sph' => -2.00,
            'os_cyl' => 0.00,
            'pd_distance' => 64.0,
            'clinical_notes' => 'Anti-reflective coating recommended',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('prescriptions', [
            'customer_id' => $this->customer->id,
            'od_sph' => -1.50,
            'od_axis' => 90,
        ]);
    }

    public function test_pos_sales_order_creation_triggers_lab_and_invoice(): void
    {
        // 1. Create a prescription first
        $rx = Prescription::create([
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'prescription_number' => 'RX-TEST-001',
            'prescription_date' => now()->toDateString(),
            'od_sph' => -1.50,
            'os_sph' => -1.75,
        ]);

        // 2. Submit POS Order with Frame and Lenses
        $response = $this->actingAs($this->user)->post(route('pos.store'), [
            'customer_id' => $this->customer->id,
            'prescription_id' => $rx->id,
            'items' => [
                [
                    'product_id' => $this->frameProduct->id,
                    'item_name' => $this->frameProduct->name,
                    'item_type' => 'frame',
                    'hsn_code' => '9003',
                    'quantity' => 1,
                    'unit_price' => 2000.00,
                    'tax_rate' => 12.00,
                ],
                [
                    'product_id' => $this->lensProduct->id,
                    'item_name' => $this->lensProduct->name,
                    'item_type' => 'lens',
                    'hsn_code' => '9001',
                    'quantity' => 1,
                    'unit_price' => 1200.00,
                    'tax_rate' => 12.00,
                ],
            ],
            'advance_amount' => 1500.00,
            'payment_mode' => 'upi',
        ]);

        $response->assertStatus(302);

        // Verify Order Exists
        $this->assertDatabaseHas('sales_orders', [
            'customer_id' => $this->customer->id,
            'prescription_id' => $rx->id,
            'order_status' => 'in_lab',
        ]);

        $order = SalesOrder::where('customer_id', $this->customer->id)->first();
        $this->assertNotNull($order);

        // Verify Automated Lab Job Card Created
        $this->assertDatabaseHas('lab_job_cards', [
            'sales_order_id' => $order->id,
            'stage' => 'job_created',
        ]);

        // Verify Tax Invoice Generated
        $this->assertDatabaseHas('invoices', [
            'sales_order_id' => $order->id,
        ]);

        // Verify Advance Payment Captured
        $this->assertDatabaseHas('payments', [
            'sales_order_id' => $order->id,
            'amount' => 1500.00,
        ]);
    }

    public function test_quality_control_signoff(): void
    {
        $order = SalesOrder::create([
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'customer_id' => $this->customer->id,
            'order_number' => 'ORD-QC-TEST',
            'order_date' => now()->toDateString(),
            'total_amount' => 3000.00,
            'order_status' => 'in_lab',
        ]);

        $job = LabJobCard::create([
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'sales_order_id' => $order->id,
            'job_number' => 'LJC-QC-TEST',
            'optical_specifications' => ['od' => ['sph' => -1.0]],
            'stage' => 'qc_pending',
        ]);

        $response = $this->actingAs($this->user)->post(route('laboratory.qc', $job->id), [
            'qc_status' => 'passed',
            'check_lens_power_sph_cyl_axis' => 1,
            'check_optical_center_pd' => 1,
            'check_fitting_height' => 1,
            'check_lens_edging_bevel_fit' => 1,
            'check_lens_surface_scratches_coating' => 1,
            'check_frame_four_point_alignment' => 1,
            'check_screws_and_lens_security' => 1,
            'check_cleanliness_and_presentation' => 1,
        ]);

        $response->assertStatus(302);

        // Assert job completed and order ready for delivery
        $this->assertDatabaseHas('lab_job_cards', [
            'id' => $job->id,
            'stage' => 'completed',
        ]);

        $this->assertDatabaseHas('sales_orders', [
            'id' => $order->id,
            'order_status' => 'ready_for_delivery',
        ]);
    }

    public function test_optometrist_sees_clinical_dashboard(): void
    {
        $optometrist = User::create([
            'name' => 'Dr. Sharma',
            'email' => 'optom_test@test.test',
            'password' => bcrypt('secret'),
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'role' => 'optometrist',
        ]);

        $response = $this->actingAs($optometrist)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Clinical');
        $response->assertSee('Refraction');
        // Should not see POS or Finance in main navigation
        $response->assertDontSee('GST Invoices & Cash');
    }

    public function test_lab_technician_sees_laboratory_workbench(): void
    {
        $labTech = User::create([
            'name' => 'Lab Technician Ramesh',
            'email' => 'lab_test@test.test',
            'password' => bcrypt('secret'),
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'role' => 'lab_technician',
        ]);

        $response = $this->actingAs($labTech)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Technician Workbench');
        $response->assertSee('Surfacing');
        $response->assertSee('Edging');
        // Should not see POS or Finance
        $response->assertDontSee('GST Invoices & Cash');
        $response->assertDontSee('Optical POS & Sales');
    }

    public function test_cashier_sees_pos_counter_dashboard(): void
    {
        $cashier = User::create([
            'name' => 'Cashier Pooja',
            'email' => 'cashier_test@test.test',
            'password' => bcrypt('secret'),
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'role' => 'cashier',
        ]);

        $response = $this->actingAs($cashier)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Cashier Station');
        $response->assertSee('Cash in Drawer');
        $response->assertSee('UPI / QR Collections');
        // Should not see Lens Lab management
        $response->assertDontSee('Lens Lab & 8-Pt QC');
    }

    public function test_role_switcher_redirects_and_switches_user(): void
    {
        User::create([
            'name' => 'Lab Specialist',
            'email' => 'lab_specialist@test.test',
            'password' => bcrypt('secret'),
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'role' => 'lab_technician',
        ]);

        $response = $this->actingAs($this->user)->get(route('auth.switch', 'lab_technician'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertEquals('lab_technician', auth()->user()->role);
    }
}
