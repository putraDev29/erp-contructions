<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'normal_balance',
        'parent_id',
        'is_active',
        'is_reconcilable',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_reconcilable' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function getBalanceAttribute(): float
    {
        $debit = (float) $this->journalLines()->whereHas('journalEntry', function ($q) {
            $q->where('status', 'posted');
        })->sum('debit');

        $credit = (float) $this->journalLines()->whereHas('journalEntry', function ($q) {
            $q->where('status', 'posted');
        })->sum('credit');

        if ($this->normal_balance === 'debit') {
            return $debit - $credit;
        }

        return $credit - $debit;
    }
}
