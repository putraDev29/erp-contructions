<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_order_id',
        'description',
        'type',
        'volume',
        'unit',
        'unit_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'volume' => 'decimal:4',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function variationOrder(): BelongsTo
    {
        return $this->belongsTo(VariationOrder::class);
    }
}
