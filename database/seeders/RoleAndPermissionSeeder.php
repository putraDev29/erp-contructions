<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $rolesData = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'description' => 'Akses penuh ke seluruh sistem dan konfigurasi'],
            ['name' => 'director', 'display_name' => 'Direktur / Pemilik', 'description' => 'Eksekutif: approval proyek, approval RAB, laporan laba rugi & dashboard'],
            ['name' => 'project_manager', 'display_name' => 'Project Manager', 'description' => 'Pengelolaan proyek, WBS, approval progres, cost control'],
            ['name' => 'site_manager', 'display_name' => 'Site Manager / Mandor', 'description' => 'Operasional lapangan, absensi, request material, update progres'],
            ['name' => 'finance_accounting', 'display_name' => 'Finance / Accounting', 'description' => 'Kas, bank, invoice, pembayaran, posting jurnal & laporan keuangan'],
            ['name' => 'procurement', 'display_name' => 'Procurement / Purchasing', 'description' => 'Purchase order, negosiasi supplier, pembelian material'],
            ['name' => 'warehouse', 'display_name' => 'Warehouse / Gudang', 'description' => 'Penerimaan barang, stok gudang, pengeluaran material ke proyek'],
            ['name' => 'hr_payroll', 'display_name' => 'HR / Payroll', 'description' => 'Karyawan, absensi, kasbon, dan penggajian tenaga kerja'],
            ['name' => 'estimator', 'display_name' => 'Estimator / RAB', 'description' => 'Estimasi biaya, pembuatan dan revisi RAB'],
            ['name' => 'auditor', 'display_name' => 'Auditor / Viewer', 'description' => 'Pemeriksaan audit trail, laporan keuangan, view-only'],
        ];

        $roles = [];
        foreach ($rolesData as $r) {
            $roles[$r['name']] = Role::updateOrCreate(['name' => $r['name']], $r);
        }

        // 2. Create Permissions
        $permissionsData = [
            // Projects
            ['name' => 'projects.view', 'display_name' => 'Lihat Proyek', 'group' => 'projects'],
            ['name' => 'projects.create', 'display_name' => 'Buat Proyek Baru', 'group' => 'projects'],
            ['name' => 'projects.update', 'display_name' => 'Edit Proyek', 'group' => 'projects'],
            ['name' => 'projects.approve', 'display_name' => 'Persetujuan Proyek', 'group' => 'projects'],
            // Budgets & RAB
            ['name' => 'budgets.view', 'display_name' => 'Lihat RAB', 'group' => 'budgets'],
            ['name' => 'budgets.create', 'display_name' => 'Buat RAB', 'group' => 'budgets'],
            ['name' => 'budgets.approve', 'display_name' => 'Persetujuan RAB', 'group' => 'budgets'],
            // Procurement
            ['name' => 'purchases.create', 'display_name' => 'Buat Permintaan & PO', 'group' => 'procurement'],
            ['name' => 'purchases.approve', 'display_name' => 'Persetujuan PO', 'group' => 'procurement'],
            // Inventory
            ['name' => 'inventory.manage', 'display_name' => 'Kelola Stok & Gudang', 'group' => 'inventory'],
            // Payroll
            ['name' => 'payroll.manage', 'display_name' => 'Kelola Absensi & Gaji', 'group' => 'payroll'],
            // Finance
            ['name' => 'finance.manage', 'display_name' => 'Kelola Kas & Bank', 'group' => 'finance'],
            // Accounting
            ['name' => 'accounting.manage', 'display_name' => 'Kelola Jurnal & COA', 'group' => 'accounting'],
            // Reports
            ['name' => 'reports.view', 'display_name' => 'Lihat Semua Laporan', 'group' => 'reports'],
            // Users & Settings
            ['name' => 'users.manage', 'display_name' => 'Kelola Pengguna & Hak Akses', 'group' => 'settings'],
            ['name' => 'settings.manage', 'display_name' => 'Kelola Pengaturan Perusahaan', 'group' => 'settings'],
        ];

        $allPermissionIds = [];
        foreach ($permissionsData as $p) {
            $perm = Permission::updateOrCreate(['name' => $p['name']], $p);
            $allPermissionIds[] = $perm->id;
        }

        // Assign all permissions to super_admin
        $roles['super_admin']->permissions()->sync($allPermissionIds);

        // Director permissions
        $roles['director']->permissions()->sync(
            Permission::whereIn('name', [
                'projects.view', 'projects.approve', 'budgets.view', 'budgets.approve',
                'purchases.approve', 'reports.view', 'finance.manage', 'accounting.manage'
            ])->pluck('id')
        );

        // Project Manager permissions
        $roles['project_manager']->permissions()->sync(
            Permission::whereIn('name', [
                'projects.view', 'projects.create', 'projects.update', 'budgets.view',
                'budgets.create', 'purchases.create', 'purchases.approve', 'reports.view'
            ])->pluck('id')
        );

        // Finance permissions
        $roles['finance_accounting']->permissions()->sync(
            Permission::whereIn('name', [
                'projects.view', 'budgets.view', 'finance.manage', 'accounting.manage', 'reports.view'
            ])->pluck('id')
        );

        // 3. Create Default Development Admin User
        User::updateOrCreate(
            ['email' => 'admin@konstruksimaju.co.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('Admin123!'),
                'role_id' => $roles['super_admin']->id,
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        // Additional Development Test Users
        User::updateOrCreate(
            ['email' => 'direktur@konstruksimaju.co.id'],
            [
                'name' => 'Ir. Hendra Gunawan (Direktur)',
                'password' => Hash::make('Admin123!'),
                'role_id' => $roles['director']->id,
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pm@konstruksimaju.co.id'],
            [
                'name' => 'Budi Santoso, ST (Project Manager)',
                'password' => Hash::make('Admin123!'),
                'role_id' => $roles['project_manager']->id,
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'finance@konstruksimaju.co.id'],
            [
                'name' => 'Siti Rahmawati, SE (Finance & Akunting)',
                'password' => Hash::make('Admin123!'),
                'role_id' => $roles['finance_accounting']->id,
                'phone' => '081234567893',
                'is_active' => true,
            ]
        );
    }
}
