<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\BankAccount;
use App\Models\Employee;
use App\Models\EmployeeAdvance;
use App\Models\AttendanceRecord;
use App\Models\PayrollPeriod;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\CustomerInvoice;
use App\Models\CustomerPayment;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use App\Models\JournalEntry;
use App\Services\BudgetService;
use App\Services\InventoryService;
use App\Services\PayrollService;
use App\Services\AccountingService;
use App\Services\ProjectCostService;
use Illuminate\Support\Facades\DB;
use Exception;

class ConstructionErpBusinessTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@konstruksimaju.co.id')->first() ?? User::factory()->create();
    }

    /**
     * Test 1: Authentication and Dashboard access
     */
    public function test_user_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@konstruksimaju.co.id',
            'password' => 'Admin123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);

        $dashResponse = $this->actingAs($this->admin)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Dashboard Eksekutif Kontraktor');
    }

    /**
     * Test 2: RAB Pricing Calculation (Markup vs Margin)
     */
    public function test_rab_margin_and_markup_calculation(): void
    {
        $budgetService = app(BudgetService::class);
        $cost = 1000000; // Rp 1,000,000

        // Markup 20%: Price = Cost * (1 + 0.20) = 1,200,000
        $markupResult = $budgetService->calculatePricing($cost, 'markup', 20);
        $this->assertEquals(1200000, $markupResult['offered_price']);
        $this->assertEquals(20, $markupResult['markup_percent']);

        // Margin 20%: Price = Cost / (1 - 0.20) = 1,250,000
        $marginResult = $budgetService->calculatePricing($cost, 'margin', 20);
        $this->assertEquals(1250000, $marginResult['offered_price']);
        $this->assertEquals(20, $marginResult['margin_percent']);

        // Validate margin < 100%
        $this->expectException(Exception::class);
        $budgetService->calculatePricing($cost, 'margin', 100);
    }

    /**
     * Test 3: Procurement PO -> Goods Receipt -> Inventory Moving Average Cost & Balanced Journal
     */
    public function test_procurement_po_and_goods_receipt(): void
    {
        $project = Project::first();
        $supplier = Supplier::first();
        $material = Material::first();
        $warehouse = Warehouse::first();

        $inventoryService = app(InventoryService::class);

        // Create PO
        $po = PurchaseOrder::create([
            'po_number' => 'PO-TEST-' . uniqid(),
            'project_id' => $project->id,
            'supplier_id' => $supplier->id,
            'po_date' => date('Y-m-d'),
            'subtotal' => 5000000,
            'tax_percent' => 11,
            'tax_amount' => 550000,
            'shipping_fee' => 0,
            'total_amount' => 5550000,
            'payment_terms' => 'Net 30',
            'status' => 'approved',
        ]);

        $poItem = $po->items()->create([
            'material_id' => $material->id,
            'qty' => 10,
            'unit' => 'pcs',
            'unit_price' => 500000,
            'discount' => 0,
            'total_price' => 5000000,
            'received_qty' => 0,
        ]);

        // Receive Goods Receipt
        $gr = GoodsReceipt::create([
            'receipt_number' => 'GR-TEST-' . uniqid(),
            'purchase_order_id' => $po->id,
            'warehouse_id' => $warehouse->id,
            'receipt_date' => date('Y-m-d'),
            'delivery_note_number' => 'SJ-VENDOR-999',
            'received_by' => $this->admin->id,
            'status' => 'verified',
        ]);

        $gr->items()->create([
            'purchase_order_item_id' => $poItem->id,
            'material_id' => $material->id,
            'ordered_qty' => 10,
            'received_qty' => 10,
            'rejected_qty' => 0,
            'unit' => 'pcs',
        ]);

        $inventoryService->processGoodsReceipt($gr);

        // Check stock incremented
        $stock = DB::table('material_stocks')
            ->where('warehouse_id', $warehouse->id)
            ->where('material_id', $material->id)
            ->first();

        $this->assertNotNull($stock);
        $this->assertGreaterThanOrEqual(10, $stock->current_stock);

        // Check PO status
        $po->refresh();
        $this->assertEquals('received_full', $po->status);

        // Check double-entry journal created and balanced (debit == credit)
        $journal = JournalEntry::where('reference_type', 'goods_receipt')
            ->where('reference_id', $gr->id)
            ->first();

        $this->assertNotNull($journal);
        $this->assertEquals($journal->total_debit, $journal->total_credit);
        $this->assertEquals('posted', $journal->status);
    }

    /**
     * Test 4: Inventory Issuance to Project Deducts Stock and Posts COGS Journal
     */
    public function test_inventory_issuance_to_project(): void
    {
        $project = Project::first();
        $warehouse = Warehouse::first();
        $material = Material::first();
        $inventoryService = app(InventoryService::class);

        $stockBefore = DB::table('material_stocks')
            ->where('warehouse_id', $warehouse->id)
            ->where('material_id', $material->id)
            ->value('current_stock') ?? 0;

        $qtyToIssue = 2;
        if ($stockBefore < $qtyToIssue) {
            DB::table('material_stocks')->updateOrInsert(
                ['warehouse_id' => $warehouse->id, 'material_id' => $material->id],
                ['current_stock' => 10, 'moving_avg_cost' => 500000, 'total_value' => 5000000]
            );
            $stockBefore = 10;
        }

        $trx = $inventoryService->issueMaterialToProject(
            $warehouse->id,
            $project->id,
            [['material_id' => $material->id, 'qty' => $qtyToIssue, 'notes' => 'Test Pemakaian']]
        );

        $stockAfter = DB::table('material_stocks')
            ->where('warehouse_id', $warehouse->id)
            ->where('material_id', $material->id)
            ->value('current_stock');

        $this->assertEquals($stockBefore - $qtyToIssue, $stockAfter);

        // Check journal balanced
        $journal = JournalEntry::where('reference_type', 'inventory_transaction')
            ->where('reference_id', $trx->id)
            ->first();

        $this->assertNotNull($journal);
        $this->assertEquals($journal->total_debit, $journal->total_credit);
    }

    /**
     * Test 5: Payroll Calculation and Automatic Kasbon Deduction
     */
    public function test_payroll_calculation_and_kasbon_deduction(): void
    {
        $employee = Employee::first();
        $project = Project::first();
        $payrollService = app(PayrollService::class);

        // Record 5 days attendance
        for ($i = 1; $i <= 5; $i++) {
            AttendanceRecord::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date' => date('Y-m-d', strtotime("-{$i} days")),
                ],
                [
                    'project_id' => $project->id,
                    'status' => 'present',
                    'overtime_hours' => 2,
                    'daily_wage' => $employee->daily_rate,
                    'overtime_wage' => ($employee->hourly_rate * 1.5 * 2),
                ]
            );
        }

        // Give active advance (Kasbon)
        $adv = EmployeeAdvance::create([
            'advance_number' => 'ADV-TEST-' . uniqid(),
            'employee_id' => $employee->id,
            'date' => date('Y-m-d', strtotime('-10 days')),
            'amount' => 300000,
            'remaining_amount' => 300000,
            'status' => 'approved',
        ]);

        $period = PayrollPeriod::create([
            'period_code' => 'PAY-TEST-' . uniqid(),
            'title' => 'Test Period',
            'start_date' => date('Y-m-d', strtotime('-7 days')),
            'end_date' => date('Y-m-d'),
            'status' => 'draft',
            'total_gross' => 0,
            'total_deductions' => 0,
            'total_net' => 0,
        ]);

        $payrollService->calculatePeriod($period);

        // Check advance was deducted
        $adv->refresh();
        $this->assertLessThan(300000, $adv->remaining_amount);

        // Check payroll record created
        $payroll = $period->payrolls()->where('employee_id', $employee->id)->first();
        $this->assertNotNull($payroll);
        $this->assertGreaterThan(0, $payroll->advance_deductions);
        $this->assertEquals($payroll->gross_salary - $payroll->advance_deductions, $payroll->net_salary);
    }

    /**
     * Test 6: Double-Entry Balancing & Strict Reversal Integrity
     */
    public function test_double_entry_balancing_and_reversal(): void
    {
        $accountingService = app(AccountingService::class);
        $coaDebit = DB::table('chart_of_accounts')->where('is_active', true)->where('normal_balance', 'debit')->first();
        $coaCredit = DB::table('chart_of_accounts')->where('is_active', true)->where('normal_balance', 'credit')->first();

        $amount = 1500000;
        $lines = [
            ['coa_id' => $coaDebit->id, 'debit' => $amount, 'credit' => 0, 'description' => 'Test Debit'],
            ['coa_id' => $coaCredit->id, 'debit' => 0, 'credit' => $amount, 'description' => 'Test Credit'],
        ];

        $entry = $accountingService->createAndPostEntry(
            'Test Manual Journal Entry',
            'manual_entry',
            null,
            null,
            $lines,
            date('Y-m-d')
        );

        $this->assertNotNull($entry);
        $this->assertEquals($amount, $entry->total_debit);
        $this->assertEquals($amount, $entry->total_credit);
        $this->assertEquals('posted', $entry->status);

        // Reverse journal
        $reversal = $accountingService->reverseJournalEntry($entry->id, 'Koreksi pengujian');
        $this->assertNotNull($reversal);
        $this->assertTrue($reversal->is_reversal);
        $this->assertEquals($entry->id, $reversal->reversed_entry_id);
        $this->assertEquals($entry->total_credit, $reversal->total_debit);
        $this->assertEquals($entry->total_debit, $reversal->total_credit);
    }

    /**
     * Test 7: Project Cost Service & Cost Control Variance
     */
    public function test_project_cost_service(): void
    {
        $project = Project::first();
        $costService = app(ProjectCostService::class);

        $summary = $costService->getProjectCostSummary($project);

        $this->assertArrayHasKey('active_budget', $summary);
        $this->assertArrayHasKey('total_actual_cost', $summary);
        $this->assertArrayHasKey('recognized_revenue', $summary);
        $this->assertArrayHasKey('gross_profit', $summary);
        $this->assertArrayHasKey('variance', $summary);
        $this->assertArrayHasKey('absorption_percent', $summary);

        // Gross profit = revenue - actual cost
        $expectedProfit = $summary['recognized_revenue'] - $summary['total_actual_cost'];
        $this->assertEquals($expectedProfit, $summary['gross_profit']);
    }

    /**
     * Test 8: Customer Invoice (AR) and Payment
     */
    public function test_customer_invoice_and_payment(): void
    {
        $project = Project::first();
        $bank = BankAccount::first();
        $milestone = 'Test Termin ' . uniqid();

        // 1. Create Invoice
        $response = $this->actingAs($this->admin)->post('/customer-invoices', [
            'project_id' => $project->id,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+14 days')),
            'milestone_description' => $milestone,
            'progress_percent' => 30,
            'gross_amount' => 10000000,
            'retention_amount' => 500000,
            'tax_percent' => 11,
        ]);

        $response->assertSessionHasNoErrors();
        $invoice = CustomerInvoice::where('milestone_description', $milestone)->first();
        $this->assertNotNull($invoice);
        $this->assertEquals('sent', $invoice->status);
        $this->assertGreaterThan(0, $invoice->net_amount);

        // 2. Pay Invoice
        $payAmount = $invoice->net_amount;
        $payResponse = $this->actingAs($this->admin)->post("/customer-invoices/{$invoice->id}/payment", [
            'bank_account_id' => $bank->id,
            'payment_date' => date('Y-m-d'),
            'amount' => $payAmount,
            'reference_number' => 'REF-TEST-99',
            'notes' => 'Pelunasan Test',
        ]);

        $payResponse->assertSessionHasNoErrors();
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
        $this->assertEquals(0, $invoice->remaining_amount);
    }

    /**
     * Test 9: Supplier Invoice (AP) and Payment
     */
    public function test_supplier_invoice_and_payment(): void
    {
        $supplier = Supplier::first();
        $po = PurchaseOrder::first();
        $bank = BankAccount::first();

        // Top up bank balance if needed
        $bank->update(['current_balance' => 500000000]);

        $invoice = SupplierInvoice::create([
            'invoice_number' => 'INV-SUP-TEST-' . uniqid(),
            'supplier_invoice_ref' => 'REF-SUP-' . uniqid(),
            'supplier_id' => $supplier->id,
            'purchase_order_id' => $po->id,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'subtotal' => 2000000,
            'tax_amount' => 220000,
            'total_amount' => 2220000,
            'paid_amount' => 0,
            'remaining_amount' => 2220000,
            'status' => 'verified',
        ]);

        // Pay Supplier
        $payResponse = $this->actingAs($this->admin)->post("/supplier-invoices/{$invoice->id}/payment", [
            'bank_account_id' => $bank->id,
            'payment_date' => date('Y-m-d'),
            'amount' => 2220000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Pelunasan Vendor Test',
        ]);

        $payResponse->assertSessionHasNoErrors();
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
        $this->assertEquals(0, $invoice->remaining_amount);
    }
}

