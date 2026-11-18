<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('rab_number')->unique(); // RAB-202610-001
            $table->integer('version')->default(1);
            $table->string('title');
            $table->decimal('direct_cost', 15, 2)->default(0);
            $table->decimal('overhead_percent', 5, 2)->default(5.00);
            $table->decimal('overhead_cost', 15, 2)->default(0);
            $table->decimal('contingency_percent', 5, 2)->default(3.00);
            $table->decimal('contingency_cost', 15, 2)->default(0);
            $table->decimal('total_rab_amount', 15, 2)->default(0); // Biaya langsung + overhead + contingency
            $table->enum('pricing_method', ['markup', 'margin'])->default('margin');
            $table->decimal('target_margin_percent', 5, 2)->default(15.00);
            $table->decimal('target_markup_percent', 5, 2)->default(17.65);
            $table->decimal('offered_price', 15, 2)->default(0); // Nilai penawaran ke klien
            $table->enum('status', ['draft', 'submitted', 'approved', 'revised', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('revision_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('budget_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets')->cascadeOnDelete();
            $table->string('division_code'); // DIV-01, DIV-02 (Pekerjaan Persiapan, Pekerjaan Struktur, Pekerjaan Baja, Finishing, dll)
            $table->string('name');
            $table->integer('order_number')->default(1);
            $table->timestamps();
        });

        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_division_id')->constrained('budget_divisions')->cascadeOnDelete();
            $table->string('item_code'); // ITM-01.01
            $table->string('name'); // Contoh: Pemancangan Tiang Pancang, Pemasangan Baja WF 250, Cor Kolom K-300
            $table->string('spec')->nullable();
            $table->decimal('volume', 12, 4)->default(1);
            $table->string('unit')->default('m3');
            $table->decimal('unit_material_price', 15, 2)->default(0);
            $table->decimal('unit_labor_price', 15, 2)->default(0);
            $table->decimal('unit_equipment_price', 15, 2)->default(0);
            $table->decimal('unit_subcon_price', 15, 2)->default(0);
            $table->decimal('direct_unit_cost', 15, 2)->default(0);
            $table->decimal('total_direct_cost', 15, 2)->default(0); // volume * direct_unit_cost
            $table->decimal('offered_unit_price', 15, 2)->default(0);
            $table->decimal('total_offered_price', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('budget_item_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_item_id')->constrained('budget_items')->cascadeOnDelete();
            $table->enum('component_type', ['material', 'labor', 'equipment', 'subcon']);
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->string('description'); // Semen PC 50kg, Tukang Besi, Crane 25 Ton
            $table->decimal('coefficient', 12, 4)->default(1); // Koefisien Analisa Harga Satuan Pekerjaan (AHSP)
            $table->string('unit')->default('zak');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0); // coefficient * unit_price
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_item_components');
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('budget_divisions');
        Schema::dropIfExists('budgets');
    }
};
