<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_code', 50)->unique();
            $table->string('name');
            $table->string('phone', 25)->index();
            $table->string('email')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'unspecified'])->default('unspecified');
            $table->date('date_of_birth')->nullable();
            $table->date('anniversary_date')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('state_code', 5)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->decimal('outstanding_balance', 12, 2)->default(0.00);
            $table->integer('loyalty_points')->default(0);
            $table->text('medical_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('prescription_number', 50)->unique();
            $table->date('prescription_date');
            $table->date('expiry_date')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('clinic_hospital')->nullable();
            $table->enum('prescription_type', ['single_vision', 'distance', 'reading', 'bifocal', 'progressive', 'occupational', 'computer'])->default('single_vision');
            
            // Right Eye (OD - Oculus Dexter)
            $table->decimal('od_sph', 5, 2)->nullable();
            $table->decimal('od_cyl', 5, 2)->nullable();
            $table->unsignedSmallInteger('od_axis')->nullable(); // 0 - 180
            $table->decimal('od_add', 4, 2)->nullable();
            $table->decimal('od_prism', 4, 2)->nullable();
            $table->string('od_base', 10)->nullable(); // UP, DOWN, IN, OUT
            $table->string('od_va_distance', 20)->nullable(); // e.g. 6/6
            $table->string('od_va_near', 20)->nullable(); // e.g. N6
            
            // Left Eye (OS - Oculus Sinister)
            $table->decimal('os_sph', 5, 2)->nullable();
            $table->decimal('os_cyl', 5, 2)->nullable();
            $table->unsignedSmallInteger('os_axis')->nullable(); // 0 - 180
            $table->decimal('os_add', 4, 2)->nullable();
            $table->decimal('os_prism', 4, 2)->nullable();
            $table->string('os_base', 10)->nullable(); // UP, DOWN, IN, OUT
            $table->string('os_va_distance', 20)->nullable();
            $table->string('os_va_near', 20)->nullable();

            $table->decimal('pd_distance', 4, 1)->nullable();
            $table->decimal('pd_near', 4, 1)->nullable();
            $table->text('clinical_notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('eye_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->date('measurement_date');

            // Pupillary Distance
            $table->decimal('pd_binocular', 4, 1)->nullable();
            $table->decimal('pd_right', 4, 1)->nullable();
            $table->decimal('pd_left', 4, 1)->nullable();
            $table->decimal('pd_near', 4, 1)->nullable();

            // Frame Optical Geometry (mm)
            $table->decimal('frame_a_width', 4, 1)->nullable();
            $table->decimal('frame_b_height', 4, 1)->nullable();
            $table->decimal('frame_dbl', 4, 1)->nullable(); // Distance between lenses (bridge)
            $table->decimal('frame_ed', 4, 1)->nullable();  // Effective diameter
            $table->decimal('frame_temple_length', 4, 1)->nullable();

            // 3D Fitting Measurements for Progressives
            $table->decimal('fitting_height_right', 4, 1)->nullable();
            $table->decimal('fitting_height_left', 4, 1)->nullable();
            $table->decimal('pantoscopic_tilt', 4, 1)->nullable(); // degrees
            $table->decimal('face_form_angle', 4, 1)->nullable();  // wrap angle in degrees
            $table->decimal('vertex_distance', 4, 1)->nullable();  // mm (usually 10-14mm)

            $table->foreignId('measured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eye_measurements');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('customers');
    }
};

