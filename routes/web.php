<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\VariationOrderController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SubcontractorController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated ERP Routes
Route::middleware('auth')->group(function () {
    // Dashboard & Overview
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Projects & WBS
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/tasks', [ProjectTaskController::class, 'store'])->name('projects.tasks.store');
    Route::post('projects/tasks/{task}/progress', [ProjectTaskController::class, 'updateProgress'])->name('projects.tasks.progress');
    Route::resource('variation-orders', VariationOrderController::class)->only(['index', 'create', 'store']);
    Route::post('variation-orders/{variationOrder}/approve', [VariationOrderController::class, 'approve'])->name('variation-orders.approve');

    // Estimator & RAB
    Route::resource('budgets', BudgetController::class);
    Route::post('budgets/{budget}/approve', [BudgetController::class, 'approve'])->name('budgets.approve');
    Route::post('budgets/{budget}/revisions', [BudgetController::class, 'createRevision'])->name('budgets.revisions');
    Route::get('budgets/{budget}/pdf', [BudgetController::class, 'exportPdf'])->name('budgets.pdf');
    Route::get('budgets/{budget}/export-pdf', [BudgetController::class, 'exportPdf'])->name('budgets.export-pdf');

    // Procurement
    Route::get('material-requests', [ProcurementController::class, 'materialRequests'])->name('material-requests.index');
    Route::get('material-requests/create', [ProcurementController::class, 'createMaterialRequest'])->name('material-requests.create');
    Route::post('material-requests', [ProcurementController::class, 'storeMaterialRequest'])->name('material-requests.store');
    Route::post('material-requests/{materialRequest}/approve', [ProcurementController::class, 'approveMaterialRequest'])->name('material-requests.approve');

    Route::get('purchase-orders', [ProcurementController::class, 'purchaseOrders'])->name('purchase-orders.index');
    Route::get('purchase-orders/create', [ProcurementController::class, 'createPurchaseOrder'])->name('purchase-orders.create');
    Route::post('purchase-orders', [ProcurementController::class, 'storePurchaseOrder'])->name('purchase-orders.store');
    Route::get('purchase-orders/{purchaseOrder}', [ProcurementController::class, 'showPurchaseOrder'])->name('purchase-orders.show');
    Route::post('purchase-orders/{purchaseOrder}/approve', [ProcurementController::class, 'approvePurchaseOrder'])->name('purchase-orders.approve');
    Route::get('purchase-orders/{purchaseOrder}/pdf', [ProcurementController::class, 'exportPoPdf'])->name('purchase-orders.pdf');

    Route::get('goods-receipts', [ProcurementController::class, 'goodsReceipts'])->name('goods-receipts.index');
    Route::post('goods-receipts', [ProcurementController::class, 'storeGoodsReceipt'])->name('goods-receipts.store');

    Route::get('supplier-invoices', [ProcurementController::class, 'supplierInvoices'])->name('supplier-invoices.index');
    Route::get('supplier-invoices/create', [ProcurementController::class, 'createSupplierInvoice'])->name('supplier-invoices.create');
    Route::post('supplier-invoices', [ProcurementController::class, 'storeSupplierInvoice'])->name('supplier-invoices.store');
    Route::post('supplier-invoices/{supplierInvoice}/payment', [FinanceController::class, 'storeSupplierPayment'])->name('supplier-invoices.payment');

    // Inventory & Warehouse
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/issuance', [InventoryController::class, 'createIssuance'])->name('inventory.issuance');
    Route::post('inventory/issuance', [InventoryController::class, 'storeIssuance'])->name('inventory.issuance.store');
    Route::get('inventory/transfer', [InventoryController::class, 'createTransfer'])->name('inventory.transfer');
    Route::post('inventory/transfer', [InventoryController::class, 'storeTransfer'])->name('inventory.transfer.store');
    Route::get('inventory/stock-card/{material}', [InventoryController::class, 'stockCard'])->name('inventory.stock-card');

    // Labor & Payroll
    Route::resource('employees', EmployeeController::class);
    Route::get('attendances', [PayrollController::class, 'attendances'])->name('attendances.index');
    Route::post('attendances', [PayrollController::class, 'storeAttendance'])->name('attendances.store');
    Route::get('advances', [PayrollController::class, 'advances'])->name('advances.index');
    Route::post('advances', [PayrollController::class, 'storeAdvance'])->name('advances.store');
    Route::get('payrolls', [PayrollController::class, 'periods'])->name('payrolls.index');
    Route::post('payrolls', [PayrollController::class, 'storePeriod'])->name('payrolls.store');
    Route::get('payrolls/{period}', [PayrollController::class, 'show'])->name('payrolls.show');
    Route::post('payrolls/{period}/calculate', [PayrollController::class, 'calculate'])->name('payrolls.calculate');
    Route::post('payrolls/{period}/pay', [PayrollController::class, 'pay'])->name('payrolls.pay');
    Route::get('payrolls/slip/{payroll}/pdf', [PayrollController::class, 'slipPdf'])->name('payrolls.slip-pdf');

    // Subcontractors
    Route::get('subcontractors', [SubcontractorController::class, 'index'])->name('subcontractors.index');
    Route::post('subcontractors', [SubcontractorController::class, 'store'])->name('subcontractors.store');
    Route::get('subcontractors/contracts', [SubcontractorController::class, 'contracts'])->name('subcontractors.contracts');
    Route::post('subcontractors/contracts', [SubcontractorController::class, 'storeContract'])->name('subcontractors.contracts.store');
    Route::post('subcontractors/progress', [SubcontractorController::class, 'storeProgress'])->name('subcontractors.progress.store');

    // Equipment & Fleet Machinery
    Route::resource('equipment', EquipmentController::class);
    Route::post('equipment/{equipment}/usage', [EquipmentController::class, 'storeUsage'])->name('equipment.usage');

    // Finance, Cash & Bank, AR
    Route::get('finance/cash-bank', [FinanceController::class, 'cashBank'])->name('finance.cash-bank');
    Route::post('finance/cash-transaction', [FinanceController::class, 'storeCashTransaction'])->name('finance.cash-transaction.store');
    Route::post('finance/transfer', [FinanceController::class, 'storeTransfer'])->name('finance.transfer.store');

    Route::get('customer-invoices', [FinanceController::class, 'customerInvoices'])->name('customer-invoices.index');
    Route::get('customer-invoices/create', [FinanceController::class, 'createCustomerInvoice'])->name('customer-invoices.create');
    Route::post('customer-invoices', [FinanceController::class, 'storeCustomerInvoice'])->name('customer-invoices.store');
    Route::get('customer-invoices/{customerInvoice}', [FinanceController::class, 'showCustomerInvoice'])->name('customer-invoices.show');
    Route::post('customer-invoices/{customerInvoice}/payment', [FinanceController::class, 'storeCustomerPayment'])->name('customer-invoices.payment');

    // Double-Entry Accounting
    Route::get('accounting/coa', [AccountingController::class, 'coa'])->name('accounting.coa');
    Route::get('accounting/journals', [AccountingController::class, 'journals'])->name('accounting.journals');
    Route::get('accounting/journals/create', [AccountingController::class, 'createJournal'])->name('accounting.journals.create');
    Route::post('accounting/journals', [AccountingController::class, 'storeJournal'])->name('accounting.journals.store');
    Route::post('accounting/journals/{journalEntry}/reverse', [AccountingController::class, 'reverseJournal'])->name('accounting.journals.reverse');
    Route::get('accounting/general-ledger', [AccountingController::class, 'generalLedger'])->name('accounting.general-ledger');
    Route::get('accounting/trial-balance', [AccountingController::class, 'trialBalance'])->name('accounting.trial-balance');

    // Reports & Cost Control
    Route::get('reports/cost-control', [ReportController::class, 'costControl'])->name('reports.cost-control');
    Route::get('reports/project-profit-loss', [ReportController::class, 'projectProfitLoss'])->name('reports.project-profit-loss');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
    Route::get('reports/aging', [ReportController::class, 'aging'])->name('reports.aging');

    // Master Data & Configuration
    Route::get('materials', [MasterDataController::class, 'materials'])->name('materials.index');
    Route::post('materials', [MasterDataController::class, 'storeMaterial'])->name('materials.store');
    Route::get('partners', [MasterDataController::class, 'partners'])->name('master.partners');
    Route::post('partners/customers', [MasterDataController::class, 'storeCustomer'])->name('master.customers.store');
    Route::post('partners/suppliers', [MasterDataController::class, 'storeSupplier'])->name('master.suppliers.store');
    Route::get('settings/company', [MasterDataController::class, 'companySettings'])->name('settings.company');
    Route::put('settings/company', [MasterDataController::class, 'updateCompanySettings'])->name('settings.company.update');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});
