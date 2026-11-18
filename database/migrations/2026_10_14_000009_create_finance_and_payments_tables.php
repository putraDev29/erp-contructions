<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // TRX-202610-0001
            $table->date('transaction_date');
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->enum('type', ['in', 'out']);
            $table->foreignId('category_id')->nullable()->constrained('expense_categories')->nullOnDelete();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->decimal('amount', 15, 2);
            $table->string('counterparty')->nullable(); // Nama pembayar atau penerima
            $table->string('description');
            $table->string('receipt_attachment')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['posted', 'reversed'])->default('posted');
            $table->unsignedBigInteger('coa_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique(); // TRF-202610-0001
            $table->date('transfer_date');
            $table->foreignId('from_bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->foreignId('to_bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('transfer_fee', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('petty_cash_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique(); // PC-202610-0001
            $table->date('voucher_date');
            $table->decimal('amount', 15, 2);
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->string('purpose');
            $table->enum('status', ['pending', 'approved', 'paid', 'settled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV-202610-0001
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('invoice_type', [
                'down_payment', 'milestone_progress', 'final_settlement', 
                'retention', 'variation_order'
            ])->default('milestone_progress');
            $table->string('milestone_description'); // Uang Muka 20%, Termin I (Progres 35%), Retensi 5%
            $table->decimal('progress_percent', 5, 2)->default(0);
            $table->decimal('invoice_amount', 15, 2);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('retention_deducted', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2); // Nilai yang harus dibayar
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->enum('status', [
                'draft', 'sent', 'partially_paid', 'paid', 'overdue', 'cancelled'
            ])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // PAY-AR-202610-0001
            $table->foreignId('customer_invoice_id')->constrained('customer_invoices')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('transfer'); // transfer, cheque, giro, cash
            $table->string('reference_number')->nullable(); // Nomor Referensi Bank Klien
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // PAY-AP-202610-0001
            $table->foreignId('supplier_invoice_id')->constrained('supplier_invoices')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('transfer');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
        Schema::dropIfExists('customer_payments');
        Schema::dropIfExists('customer_invoices');
        Schema::dropIfExists('petty_cash_vouchers');
        Schema::dropIfExists('bank_transfers');
        Schema::dropIfExists('cash_transactions');
    }
};
