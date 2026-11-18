<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'contract_number',
        'contract_date',
        'contract_amount',
        'retention_percent',
        'down_payment_percent',
        'file_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'contract_date' => 'date',
            'contract_amount' => 'decimal:2',
            'retention_percent' => 'decimal:2',
            'down_payment_percent' => 'decimal:2',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
