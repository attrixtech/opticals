<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_job_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->string('job_number', 50)->unique();
            $table->enum('lab_type', ['in_house', 'external'])->default('in_house');
            $table->string('external_lab_name')->nullable();
            
            // Optical Snapshot JSON (preserves exact prescription, measurements, frame and lens details)
            $table->json('optical_specifications');

            // Processing Pipeline Stages
            $table->enum('stage', [
                'job_created', 
                'lens_picked', 
                'surfacing', 
                'coating', 
                'edging', 
                'frame_mounting', 
                'qc_pending', 
                'completed', 
                'rework'
            ])->default('job_created');

            $table->enum('priority', ['normal', 'urgent', 'express'])->default('normal');
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('estimated_completion_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->decimal('lab_cost', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('lab_job_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_job_card_id')->constrained()->cascadeOnDelete();
            $table->string('from_stage', 50)->nullable();
            $table->string('to_stage', 50);
            $table->text('remarks')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('quality_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_job_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();
            
            // 8-Point Optical Verification Checklist
            $table->boolean('check_lens_power_sph_cyl_axis')->default(true);
            $table->boolean('check_optical_center_pd')->default(true);
            $table->boolean('check_fitting_height')->default(true);
            $table->boolean('check_lens_edging_bevel_fit')->default(true);
            $table->boolean('check_lens_surface_scratches_coating')->default(true);
            $table->boolean('check_frame_four_point_alignment')->default(true);
            $table->boolean('check_screws_and_lens_security')->default(true);
            $table->boolean('check_cleanliness_and_presentation')->default(true);

            $table->enum('qc_status', ['passed', 'rejected'])->default('passed');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('inspected_at');
            $table->timestamps();
        });

        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('repair_number', 50)->unique();
            $table->string('frame_description');
            $table->text('issue_description'); // Broken hinge, missing nose pad, screw replacement, alignment
            $table->decimal('estimated_cost', 10, 2)->default(0.00);
            $table->decimal('advance_paid', 10, 2)->default(0.00);
            $table->enum('status', ['received', 'in_repair', 'ready', 'delivered'])->default('received');
            $table->date('received_date');
            $table->date('delivery_due_date')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('technician_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('warranty_number', 50)->unique();
            $table->string('product_type', 50); // Frame, Lens Coating
            $table->integer('warranty_months')->default(12);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'claimed', 'expired'])->default('active');
            $table->text('claim_details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('repairs');
        Schema::dropIfExists('quality_controls');
        Schema::dropIfExists('lab_job_logs');
        Schema::dropIfExists('lab_job_cards');
    }
};

