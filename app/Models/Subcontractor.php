<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcontractor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'specialty',
        'contact_person',
        'phone',
        'email',
        'address',
        'npwp',
        'bank_name',
        'bank_account_no',
        'bank_account_number',
        'bank_account_name',
        'bank_account_holder',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getBankAccountNumberAttribute($val)
    {
        return $val ?: $this->attributes['bank_account_no'] ?? null;
    }

    public function getBankAccountHolderAttribute($val)
    {
        return $val ?: $this->attributes['bank_account_name'] ?? null;
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(SubcontractorContract::class);
    }
}
