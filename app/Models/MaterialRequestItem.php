<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_request_id',
        'material_id',
        'requested_qty',
        'approved_qty',
        'ordered_qty',
        'unit',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'requested_qty' => 'decimal:4',
            'approved_qty' => 'decimal:4',
            'ordered_qty' => 'decimal:4',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class, 'material_request_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
