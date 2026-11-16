<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->enum('type', ['frame', 'lens', 'contact_lens', 'sunglasses', 'accessory', 'service'])->default('frame');
            $table->string('default_hsn', 20)->default('9003');
            $table->decimal('default_tax_rate', 5, 2)->default(12.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('supplier_code', 50)->unique();
            $table->string('contact_person')->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('email')->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('state_code', 5)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('current_balance', 12, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->unique()->index();
            $table->string('hsn_code', 20)->default('9003');
            $table->enum('product_type', ['frame', 'lens', 'contact_lens', 'sunglasses', 'accessory', 'service'])->default('frame');
            $table->decimal('purchase_price', 12, 2)->default(0.00);
            $table->decimal('mrp', 12, 2)->default(0.00);
            $table->decimal('selling_price', 12, 2)->default(0.00);
            $table->decimal('tax_rate', 5, 2)->default(12.00);
            $table->integer('min_stock_alert')->default(2);
            $table->string('model_no')->nullable();
            $table->string('color')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Specialized Optical Details
        Schema::create('product_frames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('frame_shape', ['aviator', 'wayfarer', 'round', 'cat_eye', 'rectangle', 'square', 'oval', 'geometric', 'browline'])->default('rectangle');
            $table->enum('frame_material', ['acetate', 'titanium', 'metal', 'tr90', 'plastic', 'wood', 'carbon_fiber'])->default('acetate');
            $table->enum('rim_type', ['full_rim', 'semi_rimless', 'rimless'])->default('full_rim');
            $table->enum('gender', ['unisex', 'men', 'women', 'kids'])->default('unisex');
            $table->string('frame_size', 20)->nullable(); // Small, Medium, Large, 52-18-140
            $table->decimal('eye_size', 4, 1)->nullable();
            $table->decimal('bridge_width', 4, 1)->nullable();
            $table->decimal('temple_length', 4, 1)->nullable();
            $table->timestamps();
        });

        Schema::create('product_lenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('lens_index', 10)->default('1.56'); // 1.50, 1.56, 1.60, 1.67, 1.74
            $table->enum('lens_design', ['single_vision', 'bifocal_kt', 'bifocal_d', 'progressive_standard', 'progressive_digital_freeform', 'anti_fatigue', 'office_occupational'])->default('single_vision');
            $table->enum('lens_material', ['cr39', 'polycarbonate', 'trivex', 'high_index_resin', 'mineral_glass'])->default('cr39');
            $table->string('coating', 100)->default('Hard Coat + AR'); // Anti-Reflective, Blue Blocker, Photochromic
            $table->boolean('is_photochromic')->default(false);
            $table->boolean('is_blue_cut')->default(false);
            $table->boolean('is_polarized')->default(false);
            $table->timestamps();
        });

        Schema::create('product_contact_lenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('base_curve', 4, 2)->default(8.60);
            $table->decimal('diameter', 4, 1)->default(14.2);
            $table->enum('wear_duration', ['daily_disposable', 'bi_weekly', 'monthly', 'annual'])->default('monthly');
            $table->string('water_content', 10)->default('55%');
            $table->integer('pack_size')->default(6);
            $table->timestamps();
        });

        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id']);
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('movement_type', ['purchase', 'sale', 'transfer_in', 'transfer_out', 'adjustment', 'lab_consumption', 'return', 'initial_stock']);
            $table->integer('quantity'); // Positive for in, negative for out
            $table->integer('balance_after');
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->string('reference_type')->nullable(); // Order, PO, Adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('po_number', 50)->unique();
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->enum('status', ['draft', 'ordered', 'partially_received', 'received', 'cancelled'])->default('ordered');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('tax_rate', 5, 2)->default(12.00);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('inventory_stocks');
        Schema::dropIfExists('product_contact_lenses');
        Schema::dropIfExists('product_lenses');
        Schema::dropIfExists('product_frames');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};

