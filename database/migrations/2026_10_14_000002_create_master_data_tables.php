<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('PT. KONSTRUKSI MAJU NUSANTARA');
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->text('bank_info')->nullable();
            $table->string('document_footer')->nullable();
            $table->decimal('default_tax_rate', 5, 2)->default(11.00);
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('currency')->default('IDR');
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->enum('type', ['individual', 'corporate', 'government'])->default('corporate');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->integer('payment_terms_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('position'); // Mandor, Tukang, Pelaksana, Surveyor, Logistik, Staff
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date')->nullable();
            $table->enum('employment_type', ['permanent', 'contract', 'daily', 'piece_rate'])->default('daily');
            $table->decimal('daily_rate', 15, 2)->default(0);
            $table->decimal('hourly_rate', 15, 2)->default(0);
            $table->decimal('piece_rate', 15, 2)->default(0);
            $table->string('payment_method')->default('transfer'); // transfer, cash
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name'); // Baja & Besi, Semen & Mortar, Pasir & Batu, Keramik & Finishing, dsb
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Meter, Batang, Zak, Kilogram, M3, M2, Titik, Unit, dsb
            $table->string('symbol')->unique(); // m, btg, zak, kg, m3, m2, ttk, unit
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('material_category_id')->constrained('material_categories')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->string('spec')->nullable(); // Spesifikasi teknis misal WF 200x100, Mortar MU-380
            $table->decimal('last_purchase_price', 15, 2)->default(0);
            $table->decimal('ref_price', 15, 2)->default(0);
            $table->decimal('min_stock', 12, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name'); // Gudang Utama, Gudang Transit, Gudang Proyek A
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('material_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->decimal('current_stock', 12, 4)->default(0);
            $table->decimal('moving_avg_cost', 15, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['warehouse_id', 'material_id']);
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // KAS-01, BCA-01, MANDIRI-01
            $table->string('bank_name'); // Kas Tunai, Bank BCA, Bank Mandiri, Bank BRI
            $table->string('account_number')->nullable();
            $table->string('account_name');
            $table->string('branch')->nullable();
            $table->enum('type', ['cash', 'bank'])->default('bank');
            $table->decimal('initial_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->unsignedBigInteger('coa_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name'); // Beban Material, Upah Tenaga Kerja, Sewa Alat, Subkon, Overhead Kantor
            $table->enum('type', ['direct_cost', 'overhead', 'operational'])->default('direct_cost');
            $table->unsignedBigInteger('coa_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name'); // Excavator, Mobile Crane, Bar Bender, Genset 50kVA, Scaffolding
            $table->string('asset_number')->nullable();
            $table->enum('ownership_status', ['owned', 'rented'])->default('owned');
            $table->string('model_spec')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'breakdown'])->default('available');
            $table->string('current_location')->nullable();
            $table->decimal('daily_rental_rate', 15, 2)->default(0);
            $table->decimal('hourly_rental_rate', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('material_stocks');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('units');
        Schema::dropIfExists('material_categories');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('company_settings');
    }
};
