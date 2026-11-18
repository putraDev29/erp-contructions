<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubcontractorInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'subcontractor_contract_id',
        'invoice_date',
        'due_date',
        'progress_percent',
        'invoice_amount',
        'retention_deduction',
        'tax_amount',
        'net_invoice_amount',
        'paid_amount',
        'remaining_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'progress_percent' => 'decimal:2',
            'invoice_amount' => 'decimal:2',
            'retention_deduction' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'net_invoice_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SubcontractorContract::class, 'subcontractor_contract_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubcontractorPayment::class);
    }
}
