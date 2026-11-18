<?php

namespace App\Services;

use App\Models\GoodsReceipt;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryService
{
    /**
     * Process stock increment from Goods Receipt and update Moving Weighted Average Cost.
     */
    public function processGoodsReceipt(GoodsReceipt $receipt): void
    {
        DB::transaction(function () use ($receipt) {
            $trxNumber = SequenceService::generate('STK-IN', 'inventory_transactions');

            $invTrx = InventoryTransaction::create([
                'transaction_number' => $trxNumber,
                'transaction_date' => $receipt->receipt_date,
                'type' => 'receipt',
                'warehouse_id' => $receipt->warehouse_id,
                'project_id' => $receipt->purchaseOrder?->project_id,
                'reference_type' => 'goods_receipt',
                'reference_id' => $receipt->id,
                'user_id' => auth()->id() ?? $receipt->received_by,
                'notes' => "Penerimaan Material dari PO {$receipt->purchaseOrder?->po_number}",
            ]);

            foreach ($receipt->items as $item) {
                $qty = (float) $item->received_qty;
                if ($qty <= 0) {
                    continue;
                }

                $material = Material::findOrFail($item->material_id);
                $poPrice = $item->poItem ? (float) $item->poItem->unit_price : (float) $material->last_purchase_price;

                // Update or create MaterialStock in warehouse
                $stock = MaterialStock::firstOrCreate(
                    [
                        'warehouse_id' => $receipt->warehouse_id,
                        'material_id' => $material->id,
                    ],
                    [
                        'current_stock' => 0,
                        'moving_avg_cost' => $poPrice,
                        'total_value' => 0,
                    ]
                );

                $currentQty = (float) $stock->current_stock;
                $currentAvg = (float) $stock->moving_avg_cost;
                $newQty = $currentQty + $qty;

                // Moving Weighted Average Calculation
                if ($newQty > 0) {
                    $newAvgCost = (($currentQty * $currentAvg) + ($qty * $poPrice)) / $newQty;
                } else {
                    $newAvgCost = $poPrice;
                }

                $stock->current_stock = $newQty;
                $stock->moving_avg_cost = round($newAvgCost, 2);
                $stock->total_value = round($newQty * $newAvgCost, 2);
                $stock->save();

                // Update material's last purchase price
                $material->update(['last_purchase_price' => $poPrice]);

                // Create transaction item
                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $invTrx->id,
                    'material_id' => $material->id,
                    'qty' => $qty,
                    'unit' => $item->unit,
                    'unit_cost' => $poPrice,
                    'total_cost' => round($qty * $poPrice, 2),
                    'notes' => "Penerimaan PO item ID {$item->purchase_order_item_id}",
                ]);

                // Update PO Item received qty
                if ($item->poItem) {
                    $item->poItem->increment('received_qty', $qty);
                }
            }

            // Check if PO is fully received or partially received
            $po = $receipt->purchaseOrder;
            if ($po) {
                $allReceived = true;
                foreach ($po->items as $poItem) {
                    if ((float) $poItem->received_qty < (float) $poItem->qty) {
                        $allReceived = false;
                        break;
                    }
                }
                $po->update(['status' => $allReceived ? 'received_full' : 'received_partial']);
            }

            // Trigger double-entry accounting posting
            app(AccountingService::class)->postGoodsReceipt($receipt);

            ActivityLog::record(
                'verified',
                'GoodsReceipt',
                $receipt->id,
                "Verifikasi Penerimaan Barang {$receipt->receipt_number} & update stok gudang"
            );
        });
    }

    /**
     * Issue material from Warehouse to a specific Project.
     */
    public function issueMaterialToProject(
        int $warehouseId,
        int $projectId,
        array $items, // array of ['material_id' => int, 'qty' => float, 'notes' => ?string]
        ?string $date = null,
        ?string $notes = null
    ): InventoryTransaction {
        return DB::transaction(function () use ($warehouseId, $projectId, $items, $date, $notes) {
            $trxNumber = SequenceService::generate('STK-OUT', 'inventory_transactions');
            $trxDate = $date ?: date('Y-m-d');

            $invTrx = InventoryTransaction::create([
                'transaction_number' => $trxNumber,
                'transaction_date' => $trxDate,
                'type' => 'issuance_to_project',
                'warehouse_id' => $warehouseId,
                'project_id' => $projectId,
                'reference_type' => 'project_issuance',
                'user_id' => auth()->id() ?? 1,
                'notes' => $notes ?: "Pengeluaran material ke proyek ID {$projectId}",
            ]);

            foreach ($items as $itemData) {
                $materialId = $itemData['material_id'];
                $qty = (float) $itemData['qty'];

                if ($qty <= 0) {
                    continue;
                }

                $stock = MaterialStock::where('warehouse_id', $warehouseId)
                    ->where('material_id', $materialId)
                    ->lockForUpdate()
                    ->first();

                if (!$stock || (float) $stock->current_stock < $qty) {
                    $mat = Material::find($materialId);
                    $matName = $mat ? $mat->name : "ID {$materialId}";
                    $available = $stock ? $stock->current_stock : 0;
                    throw new Exception("Stok tidak mencukupi untuk material {$matName}! Tersedia: {$available}, Diminta: {$qty}");
                }

                $unitCost = (float) $stock->moving_avg_cost;
                $totalCost = round($qty * $unitCost, 2);

                // Deduct stock
                $stock->current_stock -= $qty;
                $stock->total_value = round($stock->current_stock * $unitCost, 2);
                $stock->save();

                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $invTrx->id,
                    'material_id' => $materialId,
                    'qty' => $qty,
                    'unit' => $stock->material?->unit?->symbol ?: 'pcs',
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Trigger double-entry accounting posting
            app(AccountingService::class)->postMaterialIssuanceToProject($invTrx);

            ActivityLog::record(
                'created',
                'InventoryTransaction',
                $invTrx->id,
                "Pengeluaran material {$invTrx->transaction_number} ke proyek ID {$projectId}"
            );

            return $invTrx;
        });
    }

    /**
     * Transfer material between warehouses.
     */
    public function transferBetweenWarehouses(
        int $fromWarehouseId,
        int $toWarehouseId,
        array $items,
        ?string $date = null,
        ?string $notes = null
    ): InventoryTransaction {
        return DB::transaction(function () use ($fromWarehouseId, $toWarehouseId, $items, $date, $notes) {
            $trxNumber = SequenceService::generate('TRF', 'inventory_transactions');
            $trxDate = $date ?: date('Y-m-d');

            $invTrx = InventoryTransaction::create([
                'transaction_number' => $trxNumber,
                'transaction_date' => $trxDate,
                'type' => 'transfer_out',
                'warehouse_id' => $fromWarehouseId,
                'reference_type' => 'warehouse_transfer',
                'user_id' => auth()->id() ?? 1,
                'notes' => $notes ?: "Transfer antar gudang ke ID {$toWarehouseId}",
            ]);

            foreach ($items as $itemData) {
                $materialId = $itemData['material_id'];
                $qty = (float) $itemData['qty'];
                if ($qty <= 0) continue;

                $fromStock = MaterialStock::where('warehouse_id', $fromWarehouseId)
                    ->where('material_id', $materialId)
                    ->lockForUpdate()
                    ->first();

                if (!$fromStock || (float) $fromStock->current_stock < $qty) {
                    $mat = Material::find($materialId);
                    $matName = $mat ? $mat->name : "ID {$materialId}";
                    throw new Exception("Stok tidak mencukupi di gudang asal untuk material {$matName}!");
                }

                $avgCost = (float) $fromStock->moving_avg_cost;
                $fromStock->current_stock -= $qty;
                $fromStock->total_value = round($fromStock->current_stock * $avgCost, 2);
                $fromStock->save();

                // Increment in destination warehouse
                $toStock = MaterialStock::firstOrCreate(
                    ['warehouse_id' => $toWarehouseId, 'material_id' => $materialId],
                    ['current_stock' => 0, 'moving_avg_cost' => $avgCost, 'total_value' => 0]
                );

                $toCurrentQty = (float) $toStock->current_stock;
                $toCurrentAvg = (float) $toStock->moving_avg_cost;
                $toNewQty = $toCurrentQty + $qty;
                $toNewAvg = $toNewQty > 0 ? (($toCurrentQty * $toCurrentAvg) + ($qty * $avgCost)) / $toNewQty : $avgCost;

                $toStock->current_stock = $toNewQty;
                $toStock->moving_avg_cost = round($toNewAvg, 2);
                $toStock->total_value = round($toNewQty * $toNewAvg, 2);
                $toStock->save();

                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $invTrx->id,
                    'material_id' => $materialId,
                    'qty' => $qty,
                    'unit' => $fromStock->material?->unit?->symbol ?: 'pcs',
                    'unit_cost' => $avgCost,
                    'total_cost' => round($qty * $avgCost, 2),
                    'notes' => "Transfer ke Gudang ID {$toWarehouseId}",
                ]);
            }

            ActivityLog::record(
                'created',
                'InventoryTransaction',
                $invTrx->id,
                "Transfer material {$invTrx->transaction_number} dari Gudang {$fromWarehouseId} ke {$toWarehouseId}"
            );

            return $invTrx;
        });
    }
}
