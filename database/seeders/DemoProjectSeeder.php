<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Customer;
use App\Models\User;
use App\Models\Budget;
use App\Models\BudgetDivision;
use App\Models\BudgetItem;
use App\Models\BudgetItemComponent;
use App\Models\ProjectTask;
use App\Models\Material;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Warehouse;
use App\Models\CustomerInvoice;
use App\Models\CustomerPayment;
use App\Models\BankAccount;
use App\Services\InventoryService;
use App\Services\AccountingService;
use App\Services\SequenceService;

class DemoProjectSeeder extends Seeder
{
    public function run(): void
    {
        $pm = User::where('email', 'pm@konstruksimaju.co.id')->first();
        $admin = User::where('email', 'admin@konstruksimaju.co.id')->first();
        $customer1 = Customer::first();
        $warehouse = Warehouse::first();
        $supplier = Supplier::first();
        $bankBca = BankAccount::where('code', 'BCA-OPERASIONAL')->first();

        // 1. Proyek Pembangunan Gudang Struktur Baja Karawang
        $project1 = Project::updateOrCreate(
            ['project_code' => 'PRJ-2026-001'],
            [
                'name' => 'Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2',
                'type' => 'steel_construction',
                'customer_id' => $customer1->id,
                'location' => 'Kawasan Industri KIIC Lot C-4, Karawang Barat',
                'coordinates' => '-6.3421, 107.2891',
                'project_manager_id' => $pm?->id,
                'tender_date' => '2026-01-15',
                'start_date' => '2026-02-01',
                'deadline_date' => '2026-11-30',
                'contract_amount' => 3850000000.00,
                'approved_budget_amount' => 3200000000.00,
                'target_margin_percent' => 16.88,
                'contract_method' => 'lump_sum',
                'payment_system' => 'termin_progres',
                'retention_percent' => 5.00,
                'retention_amount' => 192500000.00,
                'down_payment_percent' => 20.00,
                'down_payment_amount' => 770000000.00,
                'status' => 'in_progress',
                'physical_progress_percent' => 42.50,
                'notes' => 'Gudang rangka portal baja WF 200/250, atap Zincalume 0.45mm, lantai beton K-300 floor hardener.',
            ]
        );

        // Assign PM and Admin to Project 1
        if ($pm) {
            $project1->assignedUsers()->syncWithoutDetaching([$pm->id => ['role_in_project' => 'project_manager']]);
        }
        if ($admin) {
            $project1->assignedUsers()->syncWithoutDetaching([$admin->id => ['role_in_project' => 'director']]);
        }

        // 2. WBS Tasks for Project 1
        $task1 = ProjectTask::updateOrCreate(
            ['project_id' => $project1->id, 'task_code' => 'WBS-01'],
            [
                'name' => 'Pekerjaan Persiapan & Pondasi Tapak (Borepile / Pedestal)',
                'planned_volume' => 1,
                'unit' => 'ls',
                'weight_percent' => 25.00,
                'start_date' => '2026-02-01',
                'end_date' => '2026-04-15',
                'progress_percent' => 100.00,
                'status' => 'completed',
            ]
        );

        $task2 = ProjectTask::updateOrCreate(
            ['project_id' => $project1->id, 'task_code' => 'WBS-02'],
            [
                'name' => 'Fabrikasi & Erection Kolom Baja WF & Rafter Kuda-Kuda',
                'planned_volume' => 1,
                'unit' => 'ls',
                'weight_percent' => 45.00,
                'start_date' => '2026-04-16',
                'end_date' => '2026-07-31',
                'progress_percent' => 50.00, // Menghasilkan 22.5% bobot proyek
                'status' => 'in_progress',
            ]
        );

        $task3 = ProjectTask::updateOrCreate(
            ['project_id' => $project1->id, 'task_code' => 'WBS-03'],
            [
                'name' => 'Pemasangan Gording C-Channel, Seng Spandek & Cladding Dinding',
                'planned_volume' => 1,
                'unit' => 'ls',
                'weight_percent' => 20.00,
                'start_date' => '2026-08-01',
                'end_date' => '2026-09-30',
                'progress_percent' => 0.00,
                'status' => 'pending',
            ]
        );

        $task4 = ProjectTask::updateOrCreate(
            ['project_id' => $project1->id, 'task_code' => 'WBS-04'],
            [
                'name' => 'Pengecoran Lantai Gudang K-300 Tebal 15cm & Finishing',
                'planned_volume' => 1,
                'unit' => 'ls',
                'weight_percent' => 10.00,
                'start_date' => '2026-10-01',
                'end_date' => '2026-11-25',
                'progress_percent' => 0.00,
                'status' => 'pending',
            ]
        );

        // 3. Approved Budget (RAB) for Project 1
        $budget = Budget::updateOrCreate(
            ['rab_number' => 'RAB-2026-001'],
            [
                'project_id' => $project1->id,
                'version' => 1,
                'title' => 'RAB Pembangunan Gudang Struktur Baja Karawang 2.400 m2',
                'direct_cost' => 2850000000.00,
                'overhead_percent' => 7.00,
                'overhead_cost' => 199500000.00,
                'contingency_percent' => 5.28,
                'contingency_cost' => 150500000.00,
                'total_rab_amount' => 3200000000.00,
                'pricing_method' => 'margin',
                'target_margin_percent' => 16.88,
                'offered_price' => 3850000000.00,
                'status' => 'approved',
                'approved_by' => $admin?->id,
                'approved_at' => '2026-01-28 14:00:00',
            ]
        );

        // Budget Divisions & Items
        $div1 = BudgetDivision::updateOrCreate(
            ['budget_id' => $budget->id, 'division_code' => 'DIV-01'],
            ['name' => 'Pekerjaan Struktur Bawah & Pondasi', 'order_number' => 1]
        );

        $div2 = BudgetDivision::updateOrCreate(
            ['budget_id' => $budget->id, 'division_code' => 'DIV-02'],
            ['name' => 'Pekerjaan Pengadaan & Erection Rangka Baja Utama', 'order_number' => 2]
        );

        $matBaja = Material::where('code', 'MAT-WF200')->first();
        $matBeton = Material::where('code', 'MAT-D16')->first();
        $matReady = Material::where('code', 'MAT-READYK300')->first();

        BudgetItem::updateOrCreate(
            ['budget_division_id' => $div1->id, 'item_code' => 'ITM-01.01'],
            [
                'name' => 'Pengecoran Pondasi Pedestal Beton Bertulang K-300',
                'spec' => 'Ready Mix K-300 + Pembesian Ulir D16 + Bekisting Film Face',
                'volume' => 180.0000,
                'unit' => 'm3',
                'unit_material_price' => 1850000.00,
                'unit_labor_price' => 450000.00,
                'direct_unit_cost' => 2300000.00,
                'total_direct_cost' => 414000000.00,
                'offered_unit_price' => 2750000.00,
                'total_offered_price' => 495000000.00,
            ]
        );

        BudgetItem::updateOrCreate(
            ['budget_division_id' => $div2->id, 'item_code' => 'ITM-02.01'],
            [
                'name' => 'Pengadaan & Fabrikasi Rangka Kolom Baja Profil WF 200',
                'spec' => 'WF 200x100x5.5x8 Gunung Garuda, Cat Primer Zinchromate',
                'volume' => 320.0000,
                'unit' => 'btg',
                'unit_material_price' => 3850000.00,
                'unit_labor_price' => 650000.00,
                'direct_unit_cost' => 4500000.00,
                'total_direct_cost' => 1440000000.00,
                'offered_unit_price' => 5400000.00,
                'total_offered_price' => 1728000000.00,
            ]
        );

        // 4. Procurement: PO and Goods Receipt for Project 1
        if ($supplier && $matBaja) {
            $po = PurchaseOrder::updateOrCreate(
                ['po_number' => 'PO-202602-0001'],
                [
                    'project_id' => $project1->id,
                    'supplier_id' => $supplier->id,
                    'po_date' => '2026-02-10',
                    'subtotal' => 192500000.00,
                    'tax_percent' => 11.00,
                    'tax_amount' => 21175000.00,
                    'total_amount' => 213675000.00,
                    'payment_terms' => 'Net 30',
                    'status' => 'received_full',
                    'approved_by' => $admin?->id,
                    'approved_at' => '2026-02-11 10:00:00',
                    'notes' => 'Pengadaan 50 batang WF 200 untuk Proyek Karawang tahap 1',
                ]
            );

            $poItem = PurchaseOrderItem::updateOrCreate(
                ['purchase_order_id' => $po->id, 'material_id' => $matBaja->id],
                [
                    'qty' => 50,
                    'unit' => 'btg',
                    'unit_price' => 3850000.00,
                    'discount' => 0,
                    'total_price' => 192500000.00,
                    'received_qty' => 50,
                ]
            );

            $receipt = GoodsReceipt::updateOrCreate(
                ['receipt_number' => 'GR-202602-0001'],
                [
                    'purchase_order_id' => $po->id,
                    'warehouse_id' => $warehouse->id,
                    'receipt_date' => '2026-02-15',
                    'delivery_note_number' => 'SJ-KS-99882',
                    'received_by' => $pm?->id ?: 1,
                    'status' => 'verified',
                    'notes' => 'Pemeriksaan dimensi WF lolos uji QC',
                ]
            );

            GoodsReceiptItem::updateOrCreate(
                ['goods_receipt_id' => $receipt->id, 'purchase_order_item_id' => $poItem->id],
                [
                    'material_id' => $matBaja->id,
                    'ordered_qty' => 50,
                    'received_qty' => 50,
                    'rejected_qty' => 0,
                    'unit' => 'btg',
                ]
            );

            // Process Goods Receipt via InventoryService (increments stock & posts journal)
            try {
                app(InventoryService::class)->processGoodsReceipt($receipt);
            } catch (\Exception $e) {
                // Ignore if already posted
            }

            // Issue 20 btg to project 1
            try {
                app(InventoryService::class)->issueMaterialToProject(
                    $warehouse->id,
                    $project1->id,
                    [['material_id' => $matBaja->id, 'qty' => 20, 'notes' => 'Pemakaian kolom baja rangka portal 1-5']],
                    '2026-03-01',
                    'Pengeluaran baja profil ke proyek Karawang'
                );
            } catch (\Exception $e) {
                // Ignore if already issued
            }
        }

        // 5. Customer Invoices and Payment for Project 1
        $inv1 = CustomerInvoice::updateOrCreate(
            ['invoice_number' => 'INV-202602-0001'],
            [
                'project_id' => $project1->id,
                'customer_id' => $customer1->id,
                'invoice_date' => '2026-02-05',
                'due_date' => '2026-02-20',
                'invoice_type' => 'down_payment',
                'milestone_description' => 'Uang Muka Kerja 20% Kontrak Karawang',
                'progress_percent' => 0.00,
                'invoice_amount' => 770000000.00,
                'tax_percent' => 11.00,
                'tax_amount' => 84700000.00,
                'retention_deducted' => 0,
                'net_amount' => 854700000.00,
                'paid_amount' => 854700000.00,
                'remaining_amount' => 0.00,
                'status' => 'paid',
                'notes' => 'Uang muka telah diterima lunas via transfer BCA',
            ]
        );

        // Post invoice journal
        try {
            app(AccountingService::class)->postCustomerInvoice($inv1);
        } catch (\Exception $e) {}

        if ($bankBca) {
            $payment1 = CustomerPayment::updateOrCreate(
                ['payment_number' => 'PAY-AR-202602-0001'],
                [
                    'customer_invoice_id' => $inv1->id,
                    'bank_account_id' => $bankBca->id,
                    'payment_date' => '2026-02-18',
                    'amount' => 854700000.00,
                    'payment_method' => 'transfer',
                    'reference_number' => 'TRF-BCA-992211',
                    'notes' => 'Pembayaran Uang Muka dari Sinarmas Land',
                    'created_by' => $admin?->id ?: 1,
                ]
            );

            try {
                app(AccountingService::class)->postCustomerPayment($payment1);
            } catch (\Exception $e) {}
        }

        // Proyek 2: Residential Luxury Villa BSD
        $customer2 = Customer::where('code', 'CUST-002')->first() ?: $customer1;
        Project::updateOrCreate(
            ['project_code' => 'PRJ-2026-002'],
            [
                'name' => 'Pembangunan Rumah Mewah Modern Tropis 2 Lantai BSD',
                'type' => 'residential',
                'customer_id' => $customer2->id,
                'location' => 'Cluster Lakewood Blok AB-08, BSD City',
                'project_manager_id' => $pm?->id,
                'tender_date' => '2026-03-01',
                'start_date' => '2026-03-15',
                'deadline_date' => '2026-12-15',
                'contract_amount' => 2450000000.00,
                'approved_budget_amount' => 2050000000.00,
                'target_margin_percent' => 16.32,
                'contract_method' => 'lump_sum',
                'payment_system' => 'termin_progres',
                'retention_percent' => 5.00,
                'retention_amount' => 122500000.00,
                'down_payment_percent' => 20.00,
                'down_payment_amount' => 490000000.00,
                'status' => 'in_progress',
                'physical_progress_percent' => 28.00,
                'notes' => 'Rumah tinggal mewah 2 lantai, struktur beton bertulang, marmer lokal & lantai parket jati.',
            ]
        );
    }
}
