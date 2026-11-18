<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\Project;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Services\InventoryService;
use Exception;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $selectedWarehouse = $request->input('warehouse_id');
        $warehouses = Warehouse::withCount('stocks')->get();

        $stocksQuery = MaterialStock::with(['material.unit', 'material.category', 'warehouse']);

        if ($selectedWarehouse) {
            $stocksQuery->where('warehouse_id', $selectedWarehouse);
        }

        if ($search = $request->input('search')) {
            $stocksQuery->whereHas('material', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $stocks = $stocksQuery->orderBy('current_stock', 'desc')->paginate(15)->withQueryString();

        // Calculate total inventory valuation
        $totalValuation = MaterialStock::sum('total_value');
        $totalItems = MaterialStock::where('current_stock', '>', 0)->count();

        // Low stock warning (stock < material minimum_stock)
        $lowStocks = MaterialStock::with(['material.unit', 'warehouse'])
            ->join('materials', 'material_stocks.material_id', '=', 'materials.id')
            ->whereColumn('material_stocks.current_stock', '<=', 'materials.min_stock')
            ->select('material_stocks.*')
            ->take(5)
            ->get();

        $recentTransactions = InventoryTransaction::with(['warehouse', 'project', 'user'])
            ->latest()
            ->take(8)
            ->get();

        return view('inventory.index', compact(
            'warehouses',
            'stocks',
            'totalValuation',
            'totalItems',
            'lowStocks',
            'recentTransactions',
            'selectedWarehouse'
        ));
    }

    public function createIssuance(Request $request)
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->get();
        $materials = Material::where('is_active', true)->with('unit')->get();

        return view('inventory.issuance', compact('warehouses', 'projects', 'materials'));
    }

    public function storeIssuance(Request $request, InventoryService $inventoryService)
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materials,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.0001'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        try {
            $trx = $inventoryService->issueMaterialToProject(
                (int) $validated['warehouse_id'],
                (int) $validated['project_id'],
                $validated['items'],
                $validated['transaction_date'],
                $validated['notes'] ?? null
            );

            return redirect()->route('inventory.index')->with('success', "Pengeluaran material {$trx->transaction_number} ke proyek berhasil dicatat.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function createTransfer()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $materials = Material::where('is_active', true)->with('unit')->get();

        return view('inventory.transfer', compact('warehouses', 'materials'));
    }

    public function storeTransfer(Request $request, InventoryService $inventoryService)
    {
        $validated = $request->validate([
            'from_warehouse_id' => ['required', 'exists:warehouses,id', 'different:to_warehouse_id'],
            'to_warehouse_id' => ['required', 'exists:warehouses,id'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materials,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.0001'],
        ]);

        try {
            $trx = $inventoryService->transferBetweenWarehouses(
                (int) $validated['from_warehouse_id'],
                (int) $validated['to_warehouse_id'],
                $validated['items'],
                $validated['transaction_date'],
                $validated['notes'] ?? null
            );

            return redirect()->route('inventory.index')->with('success', "Transfer material {$trx->transaction_number} antar gudang berhasil.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function stockCard(Material $material, Request $request)
    {
        $material->load('unit', 'category');
        
        $itemsQuery = InventoryTransactionItem::where('material_id', $material->id)
            ->with(['transaction.warehouse', 'transaction.project'])
            ->orderBy('id', 'desc');

        $mutations = $itemsQuery->paginate(20);

        return view('inventory.stock_card', compact('material', 'mutations'));
    }
}
