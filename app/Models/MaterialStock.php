<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'material_id',
        'current_stock',
        'moving_avg_cost',
        'total_value',
    ];

    protected function casts(): array
    {
        return [
            'current_stock' => 'decimal:4',
            'moving_avg_cost' => 'decimal:2',
            'total_value' => 'decimal:2',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
