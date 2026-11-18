<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'project_id',
        'usage_date',
        'hours_used',
        'fuel_liters',
        'fuel_cost',
        'operator_cost',
        'total_cost',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'usage_date' => 'date',
            'hours_used' => 'decimal:2',
            'fuel_liters' => 'decimal:2',
            'fuel_cost' => 'decimal:2',
            'operator_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
