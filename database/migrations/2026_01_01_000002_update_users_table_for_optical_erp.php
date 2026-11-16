<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            $table->string('role', 50)->default('staff')->after('password'); // admin, manager, optometrist, lab_technician, cashier, sales
            $table->string('phone', 20)->nullable()->after('role');
            $table->decimal('commission_rate', 5, 2)->default(0.00)->after('phone');
            $table->boolean('is_active')->default(true)->after('commission_rate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['company_id', 'branch_id', 'role', 'phone', 'commission_rate', 'is_active']);
        });
    }
};

