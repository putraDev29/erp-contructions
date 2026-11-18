<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'phone',
        'email',
        'npwp',
        'bank_info',
        'document_footer',
        'default_tax_rate',
        'timezone',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'default_tax_rate' => 'decimal:2',
        ];
    }

    public static function instance(): self
    {
        return self::firstOrCreate([], [
            'name' => 'PT. KONSTRUKSI MAJU NUSANTARA',
            'address' => 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan, DKI Jakarta 12190',
            'phone' => '+62 21 520 8899',
            'email' => 'corporate@konstruksimaju.co.id',
            'npwp' => '01.234.567.8-012.000',
            'bank_info' => 'Bank Mandiri Cabang Sudirman Rek: 123-00-9876543-2 a.n PT Konstruksi Maju Nusantara',
            'document_footer' => 'Constructa ERP - Solusi Terpadu Kontraktor & Manajemen Proyek Konstruksi',
            'default_tax_rate' => 11.00,
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
        ]);
    }
}
