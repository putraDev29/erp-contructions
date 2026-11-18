<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapping_key',
        'chart_of_account_id',
        'description',
    ];

    public function coa(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public static function getAccountId(string $key): ?int
    {
        $mapping = self::where('mapping_key', $key)->first();
        return $mapping ? $mapping->chart_of_account_id : null;
    }
}
