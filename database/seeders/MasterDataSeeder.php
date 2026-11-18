<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;
use App\Models\Unit;
use App\Models\MaterialCategory;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\MaterialStock;
use App\Models\BankAccount;
use App\Models\ExpenseCategory;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Subcontractor;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\ChartOfAccount;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Company Setting
        CompanySetting::instance();

        // Units
        $units = [
            ['name' => 'Meter', 'symbol' => 'm'],
            ['name' => 'Meter Persegi', 'symbol' => 'm2'],
            ['name' => 'Meter Kubik', 'symbol' => 'm3'],
            ['name' => 'Batang', 'symbol' => 'btg'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Zak / Sak', 'symbol' => 'zak'],
            ['name' => 'Lump Sum', 'symbol' => 'ls'],
            ['name' => 'Titik', 'symbol' => 'ttk'],
            ['name' => 'Unit / Buah', 'symbol' => 'unit'],
            ['name' => 'Trip / Rit', 'symbol' => 'rit'],
            ['name' => 'Jam Kerja', 'symbol' => 'jam'],
        ];

        $unitMap = [];
        foreach ($units as $u) {
            $created = Unit::updateOrCreate(['symbol' => $u['symbol']], $u);
            $unitMap[$u['symbol']] = $created->id;
        }

        // Material Categories
        $categories = [
            ['code' => 'CAT-STEEL', 'name' => 'Baja Konstruksi & Besi Beton', 'description' => 'Baja WF, H-Beam, Besi Beton Ulir, Plat'],
            ['code' => 'CAT-CEMENT', 'name' => 'Semen, Mortar & ReadyMix', 'description' => 'Semen Portland, Mortar Perekat, Readymix K-300'],
            ['code' => 'CAT-AGGREGATE', 'name' => 'Pasir, Batu & Agregat', 'description' => 'Pasir Pasang, Pasir Cor, Split 1-2, Batu Kali'],
            ['code' => 'CAT-FINISH', 'name' => 'Finishing & Arsitektur', 'description' => 'Granit, Keramik, Cat Weathercoat, Plafon Gypsum'],
            ['code' => 'CAT-MEP', 'name' => 'Mekanikal, Elektrikal & Plumbing', 'description' => 'Pipa PVC, Kabel NYM, Panel Listrik, Sanitary'],
        ];

        $catMap = [];
        foreach ($categories as $c) {
            $created = MaterialCategory::updateOrCreate(['code' => $c['code']], $c);
            $catMap[$c['code']] = $created->id;
        }

        // Warehouses
        $warehouses = [
            ['code' => 'WH-MAIN', 'name' => 'Gudang Pusat Logistik Cakung', 'address' => 'Kawasan Industri Pulogadung, Jakarta Timur', 'is_active' => true],
            ['code' => 'WH-PROJ-BSD', 'name' => 'Gudang Lapangan Proyek BSD', 'address' => 'Site Office Cluster Lakewood BSD, Tangerang Selatan', 'is_active' => true],
        ];

        $whMap = [];
        foreach ($warehouses as $w) {
            $created = Warehouse::updateOrCreate(['code' => $w['code']], $w);
            $whMap[$w['code']] = $created->id;
        }

        // Materials
        $materials = [
            [
                'code' => 'MAT-WF200',
                'name' => 'Baja Profil WF 200x100x5.5x8 (12m)',
                'material_category_id' => $catMap['CAT-STEEL'],
                'unit_id' => $unitMap['btg'],
                'spec' => 'Panjang 12 Meter, Berat ~256 kg/btg (Gunung Garuda / Krakatau Steel)',
                'last_purchase_price' => 3850000.00,
                'ref_price' => 4100000.00,
                'min_stock' => 10,
            ],
            [
                'code' => 'MAT-D16',
                'name' => 'Besi Beton Ulir D16 (12m)',
                'material_category_id' => $catMap['CAT-STEEL'],
                'unit_id' => $unitMap['btg'],
                'spec' => 'Standar SNI BjTS 420B, Panjang 12 Meter',
                'last_purchase_price' => 188000.00,
                'ref_price' => 205000.00,
                'min_stock' => 50,
            ],
            [
                'code' => 'MAT-SEMEN50',
                'name' => 'Semen Portland Gresik 50 kg',
                'material_category_id' => $catMap['CAT-CEMENT'],
                'unit_id' => $unitMap['zak'],
                'spec' => 'Tipe I SNI 50kg',
                'last_purchase_price' => 72000.00,
                'ref_price' => 78000.00,
                'min_stock' => 100,
            ],
            [
                'code' => 'MAT-PASIR-COR',
                'name' => 'Pasir Cor Kasar Bangka',
                'material_category_id' => $catMap['CAT-AGGREGATE'],
                'unit_id' => $unitMap['m3'],
                'spec' => 'Kadar lumpur < 5%, butiran tajam',
                'last_purchase_price' => 320000.00,
                'ref_price' => 350000.00,
                'min_stock' => 20,
            ],
            [
                'code' => 'MAT-READYK300',
                'name' => 'Beton Ready Mix K-300 B-0 Non Fly-Ash',
                'material_category_id' => $catMap['CAT-CEMENT'],
                'unit_id' => $unitMap['m3'],
                'spec' => 'Slump 12 ± 2 cm, Truk Mixer Standar 7 m3',
                'last_purchase_price' => 920000.00,
                'ref_price' => 990000.00,
                'min_stock' => 0,
            ],
            [
                'code' => 'MAT-CAT-WEATH',
                'name' => 'Cat Eksterior Dulux Weathershield 20 Liter',
                'material_category_id' => $catMap['CAT-FINISH'],
                'unit_id' => $unitMap['unit'],
                'spec' => 'Pail 20 Liter, Warna Brilliant White',
                'last_purchase_price' => 1850000.00,
                'ref_price' => 2050000.00,
                'min_stock' => 5,
            ],
        ];

        foreach ($materials as $m) {
            $created = Material::updateOrCreate(['code' => $m['code']], $m);

            // Seed initial stock in WH-MAIN
            MaterialStock::updateOrCreate(
                [
                    'warehouse_id' => $whMap['WH-MAIN'],
                    'material_id' => $created->id,
                ],
                [
                    'current_stock' => $m['min_stock'] * 2,
                    'moving_avg_cost' => $m['last_purchase_price'],
                    'total_value' => ($m['min_stock'] * 2) * $m['last_purchase_price'],
                ]
            );
        }

        // Bank Accounts
        $coaKas = ChartOfAccount::where('code', '1-10101')->first();
        $coaBca = ChartOfAccount::where('code', '1-10102')->first();
        $coaMandiri = ChartOfAccount::where('code', '1-10103')->first();

        BankAccount::updateOrCreate(
            ['code' => 'KAS-KANTOR'],
            [
                'bank_name' => 'Kas Tunai Kantor & Brankas',
                'account_number' => 'KAS-01',
                'account_name' => 'Kas Operasional',
                'type' => 'cash',
                'initial_balance' => 25000000.00,
                'current_balance' => 25000000.00,
                'coa_id' => $coaKas?->id,
                'is_active' => true,
            ]
        );

        BankAccount::updateOrCreate(
            ['code' => 'BCA-OPERASIONAL'],
            [
                'bank_name' => 'Bank BCA',
                'account_number' => '542-019-8822',
                'account_name' => 'PT Konstruksi Maju Nusantara',
                'branch' => 'KCU Sudirman',
                'type' => 'bank',
                'initial_balance' => 750000000.00,
                'current_balance' => 750000000.00,
                'coa_id' => $coaBca?->id,
                'is_active' => true,
            ]
        );

        BankAccount::updateOrCreate(
            ['code' => 'MANDIRI-PROYEK'],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '123-00-9876543-2',
                'account_name' => 'PT Konstruksi Maju Nusantara - Proyek',
                'branch' => 'KC Thamrin',
                'type' => 'bank',
                'initial_balance' => 500000000.00,
                'current_balance' => 500000000.00,
                'coa_id' => $coaMandiri?->id,
                'is_active' => true,
            ]
        );

        // Customers
        Customer::updateOrCreate(
            ['code' => 'CUST-001'],
            [
                'name' => 'PT Bumi Serpong Damai Tbk (Sinarmas Land)',
                'company_name' => 'Sinarmas Land Group',
                'type' => 'corporate',
                'address' => 'Sinar Mas Land Plaza, BSD Green Office Park, Tangerang',
                'phone' => '+62 21 5036 8888',
                'email' => 'procurement@sinarmasland.com',
                'npwp' => '01.321.456.7-054.000',
                'contact_person' => 'Ibu Diana (VP Project Development)',
                'is_active' => true,
            ]
        );

        Customer::updateOrCreate(
            ['code' => 'CUST-002'],
            [
                'name' => 'Bapak Ir. Bambang Hermanto',
                'company_name' => null,
                'type' => 'individual',
                'address' => 'Jl. Bukit Hijau Indah Blok TC-12, Pondok Indah, Jakarta Selatan',
                'phone' => '08118899221',
                'email' => 'bambang.hermanto@gmail.com',
                'contact_person' => 'Bapak Bambang',
                'is_active' => true,
            ]
        );

        // Suppliers
        Supplier::updateOrCreate(
            ['code' => 'SUP-001'],
            [
                'name' => 'PT Krakatau Steel Baja Konstruksi',
                'contact_person' => 'Bapak Danu (Sales Manager)',
                'address' => 'Jl. Industri No. 5, Cilegon, Banten',
                'phone' => '+62 254 392 111',
                'email' => 'sales@krakatausteel.co.id',
                'npwp' => '01.000.123.4-081.000',
                'bank_name' => 'Bank Mandiri',
                'bank_account_no' => '137-00-1122334-4',
                'bank_account_name' => 'PT Krakatau Steel Baja Konstruksi',
                'payment_terms_days' => 30,
                'is_active' => true,
            ]
        );

        Supplier::updateOrCreate(
            ['code' => 'SUP-002'],
            [
                'name' => 'PT Semen Indonesia Distributor Regional 2',
                'contact_person' => 'Ibu Fitri',
                'address' => 'Jl. Pemuda No. 45, Jakarta Timur',
                'phone' => '+62 21 475 2299',
                'email' => 'order@sidistributor.co.id',
                'bank_name' => 'Bank BCA',
                'bank_account_no' => '001-229-3344',
                'bank_account_name' => 'PT Semen Indonesia Distributor',
                'payment_terms_days' => 14,
                'is_active' => true,
            ]
        );

        // Subcontractors
        Subcontractor::updateOrCreate(
            ['code' => 'SUB-001'],
            [
                'name' => 'PT Erection Baja Perkasa',
                'company_name' => 'Erection Baja Perkasa',
                'specialty' => 'Erection & Fabrikasi Baja WF Berat',
                'contact_person' => 'Bapak Suparno (Direktur Operasional)',
                'phone' => '081299887711',
                'email' => 'admin@erectionbaja.co.id',
                'bank_name' => 'Bank BCA',
                'bank_account_no' => '604-019-3311',
                'bank_account_name' => 'PT Erection Baja Perkasa',
                'is_active' => true,
            ]
        );

        // Employees
        Employee::updateOrCreate(
            ['nik' => 'EMP-001'],
            [
                'name' => 'Sujatno',
                'position' => 'Mandor Besi & Struktur',
                'phone' => '081311223344',
                'address' => 'Kp. Babakan RT 02/04, Tangerang',
                'employment_type' => 'daily',
                'daily_rate' => 220000.00,
                'hourly_rate' => 30000.00,
                'payment_method' => 'transfer',
                'bank_name' => 'Bank BRI',
                'bank_account_no' => '0123-01-098765-50-1',
                'bank_account_name' => 'Sujatno',
                'is_active' => true,
            ]
        );

        Employee::updateOrCreate(
            ['nik' => 'EMP-002'],
            [
                'name' => 'Agus Priyanto',
                'position' => 'Tukang Batu & Cor',
                'phone' => '081399881122',
                'address' => 'Dukuh Krajan, Klaten, Jawa Tengah',
                'employment_type' => 'daily',
                'daily_rate' => 170000.00,
                'hourly_rate' => 22000.00,
                'payment_method' => 'cash',
                'is_active' => true,
            ]
        );

        Employee::updateOrCreate(
            ['nik' => 'EMP-003'],
            [
                'name' => 'Wahyudi Santoso',
                'position' => 'Tukang Las & Fabrikasi',
                'phone' => '081233445566',
                'address' => 'Cikarang Barat, Bekasi',
                'employment_type' => 'daily',
                'daily_rate' => 190000.00,
                'hourly_rate' => 25000.00,
                'payment_method' => 'transfer',
                'bank_name' => 'Bank BCA',
                'bank_account_no' => '880-019-8271',
                'bank_account_name' => 'Wahyudi Santoso',
                'is_active' => true,
            ]
        );

        // Equipment Fleet
        Equipment::updateOrCreate(
            ['code' => 'EQP-CRANE25'],
            [
                'name' => 'Mobile Crane Kato 25 Ton',
                'asset_number' => 'AST-CRN-001',
                'ownership_status' => 'owned',
                'model_spec' => 'Kato CR-250, Boom 28m',
                'status' => 'available',
                'operator_name' => 'Bambang Supriadi',
                'fuel_type' => 'Biosolar B35',
                'current_location' => 'Pool Alat Pulogadung',
                'daily_rental_rate' => 4500000.00,
                'hourly_rental_rate' => 600000.00,
            ]
        );

        Equipment::updateOrCreate(
            ['code' => 'EQP-EXCA200'],
            [
                'name' => 'Excavator Komatsu PC200-8',
                'asset_number' => 'AST-EXC-002',
                'ownership_status' => 'owned',
                'model_spec' => 'Komatsu PC200-8 Standard Bucket 0.9 m3',
                'status' => 'in_use',
                'operator_name' => 'Hendro Prasetyo',
                'fuel_type' => 'Biosolar B35',
                'current_location' => 'Site BSD Lakewood',
                'daily_rental_rate' => 3200000.00,
                'hourly_rental_rate' => 420000.00,
            ]
        );

        Equipment::updateOrCreate(
            ['code' => 'EQP-GENSET50'],
            [
                'name' => 'Genset Silent Perkins 50 kVA',
                'asset_number' => 'AST-GEN-003',
                'ownership_status' => 'owned',
                'model_spec' => 'Perkins 1103A-33TG1 Stamford Alternator',
                'status' => 'available',
                'operator_name' => 'Rahmat Hidayat',
                'fuel_type' => 'Solar Industri Dexlite',
                'current_location' => 'Gudang Pusat Cakung',
                'daily_rental_rate' => 950000.00,
                'hourly_rental_rate' => 125000.00,
            ]
        );

        Equipment::updateOrCreate(
            ['code' => 'EQP-PUMP-SEWA'],
            [
                'name' => 'Concrete Pump Long Boom 36 Meter',
                'asset_number' => 'SEWA-CP-2026',
                'ownership_status' => 'rented',
                'model_spec' => 'IHI Concrete Pump Boom 36m (Vendor: PT Pompa Beton Mandiri)',
                'status' => 'in_use',
                'operator_name' => 'Kru Vendor Pompa',
                'fuel_type' => 'Solar Industri',
                'current_location' => 'Site BSD Lakewood',
                'daily_rental_rate' => 6500000.00,
                'hourly_rental_rate' => 850000.00,
            ]
        );
    }
}
