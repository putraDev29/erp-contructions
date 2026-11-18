<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'type',
        'customer_type',
        'address',
        'phone',
        'email',
        'npwp',
        'contact_person',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function setCustomerTypeAttribute($value)
    {
        $this->attributes['type'] = $value;
    }

    public function getCustomerTypeAttribute()
    {
        return $this->attributes['type'] ?? 'corporate';
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function customerInvoices(): HasMany
    {
        return $this->hasMany(CustomerInvoice::class);
    }
}
