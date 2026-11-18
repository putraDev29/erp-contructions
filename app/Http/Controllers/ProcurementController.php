<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceItem;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\ActivityLog;
use App\Services\SequenceService;
use App\Services\InventoryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;

class ProcurementController extends Controller
{
    // ==========================================
    // MATERIAL REQUESTS (MR)
    // ==========================================
    public function materialRequests(Request $request)
    {
        $query = MaterialRequest::with(['project', 'requestedByUser', 'approvedByUser', 'items.material.unit']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(10)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('procurement.material_requests.index', compact('requests', 'projects'));
    }

    public function createMaterialRequest(Request $request)
    {
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->orderBy('name')->get();
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $selectedProject = $request->has('project_id') ? Project::find($request->project_id) : null;

        return view('procurement.material_requests.create', compact('projects', 'materials', 'selectedProject'));
    }

    public function storeMaterialRequest(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'required_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materials,id'],
            'items.*.requested_qty' => ['required', 'numeric', 'min:0.0001'],
        ]);

        $mr = DB::transaction(function () use ($validated) {
            $reqNumber = SequenceService::generate('MR', 'material_requests', 'request_number');

            $mr = MaterialRequest::create([
                'request_number' => $reqNumber,
                'project_id' => $validated['project_id'],
                'requested_by' => auth()->id(),
                'required_date' => $validated['required_date'],
                'status' => 'pending_approval',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $material = Material::findOrFail($item['material_id']);
                MaterialRequestItem::create([
                    'material_request_id' => $mr->id,
                    'material_id' => $material->id,
                    'requested_qty' => $item['requested_qty'],
                    'approved_qty' => $item['requested_qty'], // default sama
                    'ordered_qty' => 0,
                    'unit' => $material->unit?->symbol ?: 'pcs',
                ]);
            }

            ActivityLog::record(
                'created',
                'MaterialRequest',
                $mr->id,
                "Membuat Permintaan Material {$mr->request_number} untuk Proyek ID {$mr->project_id}"
            );

            return $mr;
        });

        return redirect()->route('material-requests.index')->with('success', "Permintaan Material {$mr->request_number} berhasil diajukan.");
    }

    public function approveMaterialRequest(MaterialRequest $materialRequest)
    {
        $materialRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::record(
            'approved',
            'MaterialRequest',
            $materialRequest->id,
            "Menyetujui Permintaan Material {$materialRequest->request_number}"
        );

        return back()->with('success', "Permintaan Material {$materialRequest->request_number} telah DISETUJUI.");
    }

    // ==========================================
    // PURCHASE ORDERS (PO)
    // ==========================================
    public function purchaseOrders(Request $request)
    {
        $query = PurchaseOrder::with(['project', 'supplier', 'approvedByUser']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $purchaseOrders = $query->latest()->paginate(10)->withQueryString();
        $projects = Project::orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('procurement.purchase_orders.index', compact('purchaseOrders', 'projects', 'suppliers'));
    }

    public function createPurchaseOrder(Request $request)
    {
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $selectedProject = $request->has('project_id') ? Project::find($request->project_id) : null;

        return view('procurement.purchase_orders.create', compact('projects', 'suppliers', 'materials', 'selectedProject'));
    }

    public function storePurchaseOrder(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'po_date' => ['required', 'date'],
            'payment_terms' => ['required', 'string'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materials,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $po = DB::transaction(function () use ($validated) {
            $poNumber = SequenceService::generate('PO', 'purchase_orders', 'po_number');

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += ((float) $item['qty'] * (float) $item['unit_price']);
            }

            $taxPercent = (float) ($validated['tax_percent'] ?? 0);
            $taxAmount = round($subtotal * ($taxPercent / 100), 2);
            $shippingFee = (float) ($validated['shipping_fee'] ?? 0);
            $totalAmount = $subtotal + $taxAmount + $shippingFee;

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'project_id' => $validated['project_id'],
                'supplier_id' => $validated['supplier_id'],
                'po_date' => $validated['po_date'],
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'tax_percent' => $taxPercent,
                'tax_amount' => $taxAmount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'payment_terms' => $validated['payment_terms'],
                'status' => 'pending_approval',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $material = Material::findOrFail($item['material_id']);
                $itemTotal = (float) $item['qty'] * (float) $item['unit_price'];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'material_id' => $material->id,
                    'qty' => $item['qty'],
                    'unit' => $material->unit?->symbol ?: 'pcs',
                    'unit_price' => $item['unit_price'],
                    'discount' => 0,
                    'total_price' => $itemTotal,
                    'received_qty' => 0,
                ]);
            }

            ActivityLog::record(
                'created',
                'PurchaseOrder',
                $po->id,
                "Membuat PO {$po->po_number} senilai Rp " . number_format($totalAmount, 2)
            );

            return $po;
        });

        return redirect()->route('purchase-orders.show', $po)->with('success', "Purchase Order {$po->po_number} berhasil dibuat.");
    }

    public function showPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load([
            'project',
            'supplier',
            'items.material.unit',
            'goodsReceipts.items',
            'approvedByUser',
        ]);

        $warehouses = Warehouse::where('is_active', true)->get();

        return view('procurement.purchase_orders.show', compact('purchaseOrder', 'warehouses'));
    }

    public function approvePurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::record(
            'approved',
            'PurchaseOrder',
            $purchaseOrder->id,
            "Menyetujui Purchase Order {$purchaseOrder->po_number}"
        );

        return back()->with('success', "Purchase Order {$purchaseOrder->po_number} telah DISETUJUI.");
    }

    public function exportPoPdf(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['project', 'supplier', 'items.material.unit', 'approvedByUser']);
        $company = \App\Models\CompanySetting::instance();

        $pdf = Pdf::loadView('procurement.purchase_orders.pdf', compact('purchaseOrder', 'company'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("PO-{$purchaseOrder->po_number}.pdf");
    }

    // ==========================================
    // GOODS RECEIPTS (GR)
    // ==========================================
    public function goodsReceipts(Request $request)
    {
        $query = GoodsReceipt::with(['purchaseOrder.project', 'purchaseOrder.supplier', 'warehouse', 'receivedByUser', 'items.material.unit']);

        if ($warehouseId = $request->input('warehouse_id')) {
            $query->where('warehouse_id', $warehouseId);
        }

        $receipts = $query->latest()->paginate(10)->withQueryString();
        $warehouses = Warehouse::all();

        return view('procurement.goods_receipts.index', compact('receipts', 'warehouses'));
    }

    public function storeGoodsReceipt(Request $request, InventoryService $inventoryService)
    {
        $validated = $request->validate([
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'receipt_date' => ['required', 'date'],
            'delivery_note_number' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.po_item_id' => ['required', 'exists:purchase_order_items,id'],
            'items.*.received_qty' => ['required', 'numeric', 'min:0'],
            'items.*.rejected_qty' => ['nullable', 'numeric', 'min:0'],
        ]);

        $receipt = DB::transaction(function () use ($validated, $inventoryService) {
            $receiptNumber = SequenceService::generate('GR', 'goods_receipts', 'receipt_number');

            $receipt = GoodsReceipt::create([
                'receipt_number' => $receiptNumber,
                'purchase_order_id' => $validated['purchase_order_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'receipt_date' => $validated['receipt_date'],
                'delivery_note_number' => $validated['delivery_note_number'],
                'received_by' => auth()->id(),
                'status' => 'verified',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $poItem = PurchaseOrderItem::findOrFail($itemData['po_item_id']);
                $receivedQty = (float) $itemData['received_qty'];
                $rejectedQty = (float) ($itemData['rejected_qty'] ?? 0);

                if ($receivedQty > 0 || $rejectedQty > 0) {
                    GoodsReceiptItem::create([
                        'goods_receipt_id' => $receipt->id,
                        'purchase_order_item_id' => $poItem->id,
                        'material_id' => $poItem->material_id,
                        'ordered_qty' => $poItem->qty,
                        'received_qty' => $receivedQty,
                        'rejected_qty' => $rejectedQty,
                        'unit' => $poItem->unit,
                    ]);
                }
            }

            // Execute inventory moving average valuation, stock increment, and auto double-entry posting!
            $inventoryService->processGoodsReceipt($receipt);

            return $receipt;
        });

        return redirect()->route('goods-receipts.index')->with('success', "Penerimaan Barang {$receipt->receipt_number} berhasil dicatat & stok bertambah.");
    }

    // ==========================================
    // SUPPLIER INVOICES (AP)
    // ==========================================
    public function supplierInvoices(Request $request)
    {
        $query = SupplierInvoice::with(['supplier', 'purchaseOrder.project']);

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->get();
        $bankAccounts = \App\Models\BankAccount::where('is_active', true)->get();

        return view('procurement.supplier_invoices.index', compact('invoices', 'suppliers', 'bankAccounts'));
    }

    public function createSupplierInvoice(Request $request)
    {
        $purchaseOrders = PurchaseOrder::whereIn('status', ['received_partial', 'received_full', 'approved'])->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('procurement.supplier_invoices.create', compact('purchaseOrders', 'suppliers'));
    }

    public function storeSupplierInvoice(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'supplier_invoice_ref' => ['required', 'string', 'max:100'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $po = PurchaseOrder::findOrFail($validated['purchase_order_id']);

        // Check duplicate invoice from same supplier
        $existing = SupplierInvoice::where('supplier_id', $po->supplier_id)
            ->where('supplier_invoice_ref', $validated['supplier_invoice_ref'])
            ->first();

        if ($existing) {
            return back()->with('error', "Nomor faktur {$validated['supplier_invoice_ref']} sudah terdaftar untuk supplier ini.");
        }

        $invNumber = SequenceService::generate('INV-SUP', 'supplier_invoices', 'invoice_number');
        $totalAmount = (float) $validated['total_amount'];
        $taxAmount = (float) ($validated['tax_amount'] ?? 0);
        $subtotal = $totalAmount - $taxAmount;

        $invoice = SupplierInvoice::create([
            'invoice_number' => $invNumber,
            'supplier_invoice_ref' => $validated['supplier_invoice_ref'],
            'supplier_id' => $po->supplier_id,
            'purchase_order_id' => $po->id,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'remaining_amount' => $totalAmount,
            'status' => 'verified',
        ]);

        ActivityLog::record(
            'created',
            'SupplierInvoice',
            $invoice->id,
            "Mencatat tagihan supplier {$invoice->invoice_number} (Ref: {$invoice->supplier_invoice_ref}) senilai Rp " . number_format($totalAmount, 2)
        );

        return redirect()->route('supplier-invoices.index')->with('success', "Faktur Supplier {$invoice->invoice_number} berhasil didaftarkan.");
    }
}
