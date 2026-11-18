<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // SUB-001
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('specialty')->nullable(); // Baja Berat, ME, Pengecatan, Kaca & Aluminium, Borepile
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subcontractor_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique(); // SPK-SUB-2026-001
            $table->foreignId('subcontractor_id')->constrained('subcontractors')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('scope_of_work'); // Pekerjaan Pemasangan Rangka Baja WF & Gording
            $table->decimal('contract_amount', 15, 2);
            $table->decimal('retention_percent', 5, 2)->default(5.00);
            $table->decimal('down_payment_amount', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'active', 'completed', 'terminated'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('subcontractor_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_contract_id')->constrained('subcontractor_contracts')->cascadeOnDelete();
            $table->date('progress_date');
            $table->decimal('claimed_progress_percent', 5, 2);
            $table->decimal('approved_progress_percent', 5, 2);
            $table->string('bpa_number')->unique(); // BAP-SUB-2026-001
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('subcontractor_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV-SUB-2026-001
            $table->foreignId('subcontractor_contract_id')->constrained('subcontractor_contracts')->cascadeOnDelete();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('progress_percent', 5, 2);
            $table->decimal('invoice_amount', 15, 2);
            $table->decimal('retention_deduction', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('net_invoice_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'partially_paid', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('subcontractor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_invoice_id')->constrained('subcontractor_invoices')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractor_payments');
        Schema::dropIfExists('subcontractor_invoices');
        Schema::dropIfExists('subcontractor_progress');
        Schema::dropIfExists('subcontractor_contracts');
        Schema::dropIfExists('subcontractors');
    }
};
