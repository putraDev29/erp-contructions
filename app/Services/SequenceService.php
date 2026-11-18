<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SequenceService
{
    /**
     * Generate formatted sequential document number.
     * Example prefixes: PRJ, PO, GR, INV, JE, TRX, TRF, KSB, PAY, SUB, RAB, VO
     */
    public static function generate(string $prefix, string $table, string $column = 'transaction_number', int $padding = 4): string
    {
        $yearMonth = date('Ym');
        $likePattern = "{$prefix}-{$yearMonth}-%";

        $lastRecord = DB::table($table)
            ->where($column, 'like', $likePattern)
            ->orderBy($column, 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastRecord && isset($lastRecord->$column)) {
            $parts = explode('-', $lastRecord->$column);
            if (count($parts) >= 3) {
                $lastSeq = (int) end($parts);
                $nextNumber = $lastSeq + 1;
            }
        }

        $formattedSeq = str_pad((string) $nextNumber, $padding, '0', STR_PAD_LEFT);
        return "{$prefix}-{$yearMonth}-{$formattedSeq}";
    }

    public static function generateProjectCode(): string
    {
        $year = date('Y');
        $likePattern = "PRJ-{$year}-%";

        $lastRecord = DB::table('projects')
            ->where('project_code', 'like', $likePattern)
            ->orderBy('project_code', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastRecord && isset($lastRecord->project_code)) {
            $parts = explode('-', $lastRecord->project_code);
            if (count($parts) >= 3) {
                $lastSeq = (int) end($parts);
                $nextNumber = $lastSeq + 1;
            }
        }

        $formattedSeq = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
        return "PRJ-{$year}-{$formattedSeq}";
    }
}
