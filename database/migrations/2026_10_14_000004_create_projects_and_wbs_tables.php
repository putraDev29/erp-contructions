<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique(); // PRJ-2026-001
            $table->string('name');
            $table->enum('type', ['residential', 'commercial', 'steel_construction', 'renovation', 'civil_works', 'others'])->default('residential');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->text('location')->nullable();
            $table->string('coordinates')->nullable();
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('site_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tender_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->decimal('contract_amount', 15, 2)->default(0);
            $table->decimal('approved_budget_amount', 15, 2)->default(0);
            $table->decimal('target_margin_percent', 5, 2)->default(15.00);
            $table->enum('contract_method', ['lump_sum', 'unit_price', 'cost_plus'])->default('lump_sum');
            $table->string('payment_system')->default('termin_progres'); // termin_progres, milestone, bulanan
            $table->decimal('retention_percent', 5, 2)->default(5.00);
            $table->decimal('retention_amount', 15, 2)->default(0);
            $table->decimal('down_payment_percent', 5, 2)->default(20.00);
            $table->decimal('down_payment_amount', 15, 2)->default(0);
            $table->enum('status', [
                'draft', 'estimation', 'tender', 'pending_approval', 
                'contract', 'preparation', 'in_progress', 'on_hold', 
                'completed', 'cancelled'
            ])->default('draft');
            $table->decimal('physical_progress_percent', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('contract_number')->unique();
            $table->date('contract_date');
            $table->decimal('contract_amount', 15, 2);
            $table->decimal('retention_percent', 5, 2)->default(5.00);
            $table->decimal('down_payment_percent', 5, 2)->default(20.00);
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('project_tasks')->nullOnDelete();
            $table->string('task_code'); // WBS-01, WBS-01.01
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('planned_volume', 12, 4)->default(1);
            $table->decimal('actual_volume', 12, 4)->default(0);
            $table->string('unit')->default('ls');
            $table->decimal('weight_percent', 5, 2)->default(0); // Bobot pekerjaan (%)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('progress_percent', 5, 2)->default(0);
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('project_task_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_task_id')->constrained('project_tasks')->cascadeOnDelete();
            $table->date('update_date');
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('incremental_progress', 5, 2)->default(0);
            $table->decimal('cumulative_progress', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('variation_orders', function (Blueprint $table) {
            $table->id();
            $table->string('vo_number')->unique(); // VO-PRJ01-001
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->date('vo_date');
            $table->string('description');
            $table->text('reason')->nullable();
            $table->decimal('contract_amount_change', 15, 2)->default(0); // bisa positif (penambahan) atau negatif (pengurangan)
            $table->decimal('budget_amount_change', 15, 2)->default(0);
            $table->integer('schedule_days_change')->default(0);
            $table->string('customer_approval_ref')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('variation_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_order_id')->constrained('variation_orders')->cascadeOnDelete();
            $table->string('description');
            $table->enum('type', ['addition', 'deduction'])->default('addition');
            $table->decimal('volume', 12, 4)->default(1);
            $table->string('unit')->default('ls');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variation_order_items');
        Schema::dropIfExists('variation_orders');
        Schema::dropIfExists('project_task_updates');
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('project_contracts');
        Schema::dropIfExists('projects');
    }
};
