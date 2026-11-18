<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use App\Models\AccountMapping;
use App\Models\FiscalPeriod;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $coas = [
            // Assets (1-xxxx)
            ['code' => '1-10101', 'name' => 'Kas Tunai Kantor & Proyek', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => true],
            ['code' => '1-10102', 'name' => 'Bank BCA Operasional', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => true],
            ['code' => '1-10103', 'name' => 'Bank Mandiri Proyek', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => true],
            ['code' => '1-10200', 'name' => 'Piutang Usaha Pelanggan (AR)', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '1-10250', 'name' => 'Piutang Retensi Proyek', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '1-10300', 'name' => 'Persediaan Material Konstruksi', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '1-10400', 'name' => 'Uang Muka Karyawan & Kasbon', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '1-10500', 'name' => 'Uang Muka Pembelian Supplier', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '1-20100', 'name' => 'Aset Tetap - Kendaraan & Alat Berat', 'type' => 'asset', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '1-20199', 'name' => 'Akumulasi Penyusutan Alat Berat', 'type' => 'asset', 'normal_balance' => 'credit', 'is_reconcilable' => false],

            // Liabilities (2-xxxx)
            ['code' => '2-10100', 'name' => 'Utang Usaha Supplier (AP)', 'type' => 'liability', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '2-10200', 'name' => 'Utang Upah Tenaga Kerja & Gaji', 'type' => 'liability', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '2-10300', 'name' => 'Utang Subkontraktor Terakru', 'type' => 'liability', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '2-10400', 'name' => 'Uang Muka Pelanggan / Termin Ditunda', 'type' => 'liability', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '2-10500', 'name' => 'Utang Pajak (PPh & PPN)', 'type' => 'liability', 'normal_balance' => 'credit', 'is_reconcilable' => false],

            // Equity (3-xxxx)
            ['code' => '3-10100', 'name' => 'Modal Saham Disetor', 'type' => 'equity', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '3-10200', 'name' => 'Saldo Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '3-10300', 'name' => 'Laba / Rugi Tahun Berjalan', 'type' => 'equity', 'normal_balance' => 'credit', 'is_reconcilable' => false],

            // Revenue (4-xxxx)
            ['code' => '4-10100', 'name' => 'Pendapatan Jasa Konstruksi', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '4-10200', 'name' => 'Pendapatan Pekerjaan Tambahan (VO)', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_reconcilable' => false],
            ['code' => '4-10300', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'normal_balance' => 'credit', 'is_reconcilable' => false],

            // Direct Costs / COGS (5-xxxx)
            ['code' => '5-10100', 'name' => 'Beban Pokok - Pemakaian Material Proyek', 'type' => 'direct_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '5-10200', 'name' => 'Beban Pokok - Upah & Tenaga Kerja Lapangan', 'type' => 'direct_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '5-10300', 'name' => 'Beban Pokok - Subkontraktor', 'type' => 'direct_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '5-10400', 'name' => 'Beban Pokok - Sewa & Alat Berat Proyek', 'type' => 'direct_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '5-10500', 'name' => 'Beban Pokok - Mobilisasi & Transportasi', 'type' => 'direct_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '5-10600', 'name' => 'Beban Pokok - Operasional & K3 Lapangan', 'type' => 'direct_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],

            // Indirect / Overhead (6-xxxx)
            ['code' => '6-10100', 'name' => 'Beban Gaji & Tunjangan Staff Kantor', 'type' => 'indirect_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '6-10200', 'name' => 'Beban Sewa & Utilitas Kantor', 'type' => 'indirect_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '6-10300', 'name' => 'Beban Perlengkapan & ATK Kantor', 'type' => 'indirect_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
            ['code' => '6-10400', 'name' => 'Beban Administrasi Bank', 'type' => 'indirect_expense', 'normal_balance' => 'debit', 'is_reconcilable' => false],
        ];

        $accountMap = [];
        foreach ($coas as $c) {
            $created = ChartOfAccount::updateOrCreate(['code' => $c['code']], $c);
            $accountMap[$c['code']] = $created->id;
        }

        // Account Mappings
        $mappings = [
            'cash_default' => ['code' => '1-10101', 'desc' => 'Akun Kas Tunai Default'],
            'bank_default' => ['code' => '1-10102', 'desc' => 'Akun Bank Operasional Default'],
            'ar_customer' => ['code' => '1-10200', 'desc' => 'Piutang Usaha Pelanggan'],
            'inventory_material' => ['code' => '1-10300', 'desc' => 'Persediaan Material Konstruksi'],
            'advance_employee' => ['code' => '1-10400', 'desc' => 'Uang Muka & Kasbon Pekerja'],
            'ap_supplier' => ['code' => '2-10100', 'desc' => 'Hutang Usaha Supplier'],
            'wage_payable' => ['code' => '2-10200', 'desc' => 'Utang Gaji & Upah Tenaga Kerja'],
            'subcon_payable' => ['code' => '2-10300', 'desc' => 'Utang Subkontraktor'],
            'unearned_revenue' => ['code' => '2-10400', 'desc' => 'Uang Muka Diterima Pelanggan'],
            'revenue_construction' => ['code' => '4-10100', 'desc' => 'Pendapatan Jasa Konstruksi'],
            'cogs_material' => ['code' => '5-10100', 'desc' => 'Beban Pokok Material Proyek'],
            'cogs_labor' => ['code' => '5-10200', 'desc' => 'Beban Pokok Upah & Tenaga Kerja'],
            'cogs_subcon' => ['code' => '5-10300', 'desc' => 'Beban Pokok Subkontraktor'],
            'cogs_equipment' => ['code' => '5-10400', 'desc' => 'Beban Pokok Alat Berat & Peralatan'],
        ];

        foreach ($mappings as $key => $val) {
            if (isset($accountMap[$val['code']])) {
                AccountMapping::updateOrCreate(
                    ['mapping_key' => $key],
                    [
                        'chart_of_account_id' => $accountMap[$val['code']],
                        'description' => $val['desc'],
                    ]
                );
            }
        }

        // Fiscal period 2026
        FiscalPeriod::updateOrCreate(
            ['name' => 'Tahun Fiskal 2026'],
            [
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'is_closed' => false,
            ]
        );
    }
}
