<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'project_id',
        'code',
        'name',
        'asset_number',
        'ownership_status',
        'model_spec',
        'status',
        'operator_name',
        'fuel_type',
        'current_location',
        'daily_rental_rate',
        'hourly_rental_rate',
        'purchase_date',
        'purchase_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'daily_rental_rate' => 'decimal:2',
            'hourly_rental_rate' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'purchase_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(EquipmentUsage::class);
    }
}
