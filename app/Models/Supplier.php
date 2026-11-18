<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'address',
        'phone',
        'email',
        'npwp',
        'bank_name',
        'bank_account_no',
        'bank_account_name',
        'payment_terms',
        'payment_terms_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'payment_terms_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getPaymentTermsAttribute()
    {
        $days = $this->attributes['payment_terms_days'] ?? 30;
        return $days . ' Hari';
    }

    public function setPaymentTermsAttribute($value)
    {
        $this->attributes['payment_terms_days'] = (int) $value;
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function supplierInvoices(): HasMany
    {
        return $this->hasMany(SupplierInvoice::class);
    }

    public function getTotalPayableAttribute(): float
    {
        return (float) $this->supplierInvoices()
            ->whereIn('status', ['verified', 'partially_paid'])
            ->sum('remaining_amount');
    }
}
