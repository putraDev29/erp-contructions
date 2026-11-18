<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Employees: Align column names
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'employee_number')) {
                $table->string('employee_number')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('employees', 'role_title')) {
                $table->string('role_title')->nullable()->after('position');
            }
            if (!Schema::hasColumn('employees', 'hire_date')) {
                $table->date('hire_date')->nullable()->after('join_date');
            }
            if (!Schema::hasColumn('employees', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_account_no');
            }
            if (!Schema::hasColumn('employees', 'bank_account_holder')) {
                $table->string('bank_account_holder')->nullable()->after('bank_account_name');
            }
        });

        // Sync existing data for employees
        DB::statement("UPDATE employees SET employee_number = nik WHERE employee_number IS NULL");
        DB::statement("UPDATE employees SET role_title = position WHERE role_title IS NULL");
        DB::statement("UPDATE employees SET hire_date = join_date WHERE hire_date IS NULL");
        DB::statement("UPDATE employees SET bank_account_number = bank_account_no WHERE bank_account_number IS NULL");
        DB::statement("UPDATE employees SET bank_account_holder = bank_account_name WHERE bank_account_holder IS NULL");

        // 2. Subcontractors: Align bank account columns
        Schema::table('subcontractors', function (Blueprint $table) {
            if (!Schema::hasColumn('subcontractors', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_account_no');
            }
            if (!Schema::hasColumn('subcontractors', 'bank_account_holder')) {
                $table->string('bank_account_holder')->nullable()->after('bank_account_name');
            }
        });

        DB::statement("UPDATE subcontractors SET bank_account_number = bank_account_no WHERE bank_account_number IS NULL");
        DB::statement("UPDATE subcontractors SET bank_account_holder = bank_account_name WHERE bank_account_holder IS NULL");

        // 3. Subcontractor Contracts: Align work_scope
        Schema::table('subcontractor_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('subcontractor_contracts', 'work_scope')) {
                $table->text('work_scope')->nullable()->after('scope_of_work');
            }
        });

        DB::statement("UPDATE subcontractor_contracts SET work_scope = scope_of_work WHERE work_scope IS NULL");

        // 4. Subcontractor Progress: Add fields for full BAP workflow
        Schema::table('subcontractor_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('subcontractor_progress', 'progress_number')) {
                $table->string('progress_number')->nullable()->after('id');
            }
            if (!Schema::hasColumn('subcontractor_progress', 'inspection_date')) {
                $table->date('inspection_date')->nullable()->after('progress_date');
            }
            if (!Schema::hasColumn('subcontractor_progress', 'progress_percent')) {
                $table->decimal('progress_percent', 5, 2)->default(0)->after('approved_progress_percent');
            }
            if (!Schema::hasColumn('subcontractor_progress', 'certified_amount')) {
                $table->decimal('certified_amount', 15, 2)->default(0)->after('progress_percent');
            }
            if (!Schema::hasColumn('subcontractor_progress', 'status')) {
                $table->string('status')->default('certified')->after('certified_amount');
            }
        });

        // 5. Equipment Enhancements
        Schema::table('equipment', function (Blueprint $table) {
            if (!Schema::hasColumn('equipment', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('id')->constrained('projects')->nullOnDelete();
            }
            if (!Schema::hasColumn('equipment', 'operator_name')) {
                $table->string('operator_name')->nullable()->after('status');
            }
            if (!Schema::hasColumn('equipment', 'fuel_type')) {
                $table->string('fuel_type')->default('Solar / Biosolar')->after('operator_name');
            }
            if (!Schema::hasColumn('equipment', 'purchase_date')) {
                $table->date('purchase_date')->nullable()->after('hourly_rental_rate');
            }
            if (!Schema::hasColumn('equipment', 'purchase_price')) {
                $table->decimal('purchase_price', 15, 2)->default(0)->after('purchase_date');
            }
            if (!Schema::hasColumn('equipment', 'notes')) {
                $table->text('notes')->nullable()->after('purchase_price');
            }
        });

        // 6. Equipment Usages / Operational Log
        if (!Schema::hasTable('equipment_usages')) {
            Schema::create('equipment_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->date('usage_date');
                $table->decimal('hours_used', 5, 2)->default(0);
                $table->decimal('fuel_liters', 8, 2)->default(0);
                $table->decimal('fuel_cost', 15, 2)->default(0);
                $table->decimal('operator_cost', 15, 2)->default(0);
                $table->decimal('total_cost', 15, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_usages');
        // No destructive down needed
    }
};
