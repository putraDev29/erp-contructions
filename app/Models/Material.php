<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'material_category_id',
        'unit_id',
        'spec',
        'specification',
        'last_purchase_price',
        'ref_price',
        'reference_price',
        'min_stock',
        'minimum_stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'last_purchase_price' => 'decimal:2',
            'ref_price' => 'decimal:2',
            'min_stock' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function setSpecificationAttribute($value)
    {
        $this->attributes['spec'] = $value;
    }

    public function getSpecificationAttribute()
    {
        return $this->attributes['spec'] ?? null;
    }

    public function setMinimumStockAttribute($value)
    {
        $this->attributes['min_stock'] = $value;
    }

    public function getMinimumStockAttribute()
    {
        return $this->attributes['min_stock'] ?? 0;
    }

    public function setReferencePriceAttribute($value)
    {
        $this->attributes['ref_price'] = $value;
    }

    public function getReferencePriceAttribute()
    {
        return $this->attributes['ref_price'] ?? $this->attributes['last_purchase_price'] ?? 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(MaterialStock::class);
    }

    public function getTotalStockAttribute(): float
    {
        return (float) $this->stocks()->sum('current_stock');
    }

    public function isLowStock(): bool
    {
        return $this->total_stock <= $this->min_stock;
    }
}
