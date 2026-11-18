# CONSTRUCTA ERP - Sistem Manajemen Kontraktor & Konstruksi

Aplikasi Enterprise Resource Planning (ERP) lengkap, modular, dan terintegrasi penuh untuk perusahaan kontraktor pembangunan gedung komersial, perumahan, pekerjaan konstruksi baja, renovasi, dan pekerjaan teknik sipil.

Dibangun dengan **Laravel 11 Full-Stack**, **Tailwind CSS**, **Alpine.js**, **Chart.js**, **Lucide Icons**, dan database **MySQL / MariaDB**.

---

## 🏗️ Cakupan Siklus Bisnis

1. **Manajemen Calon Proyek & Kontrak**:
   - Tracking proyek dari status *draft, estimasi, penawaran, approved, kontrak, persiapan, berjalan, ditunda, selesai, dibatalkan*.
   - Variation Order (VO) untuk addendum penambahan / pengurangan pekerjaan dan penyesuaian nilai kontrak.
   - WBS (Work Breakdown Structure) dengan bobot persentase hierarkis dan perhitungan progres fisik terbobot.

2. **Estimator & Rencana Anggaran Biaya (RAB)**:
   - Estimasi volume, harga satuan material, upah kerja, sewa alat, dan subkontraktor.
   - Penghitungan harga penawaran presisi dengan metode **Target Margin** (`biaya / (1 - margin%)`) vs **Cost Markup** (`biaya * (1 + markup%)`).
   - Versioning RAB otomatis (Revisi V1, V2, dst.) dan export dokumen resmi PDF.

3. **Pengadaan & Manajemen Rantai Pasok (Procurement)**:
   - Alur pengadaan: *Material Request (MR) &rarr; Persetujuan &rarr; Purchase Order (PO) &rarr; Goods Receipt (Penerimaan Material) &rarr; Faktur Supplier (AP)*.
   - Otomasi penomoran PO (`PO-YYYY-XXXX`) dan cetak PDF purchase order.

4. **Gudang & Valuasi Persediaan (Inventory)**:
   - Metode valuasi persediaan **Moving Weighted Average Cost**.
   - Penambahan stok otomatis saat Goods Receipt diverifikasi.
   - Pengeluaran material ke proyek dengan locking atomik (`lockForUpdate()`) dan pengakuan biaya proyek langsung.
   - Mutasi antargudang dan Kartu Stok historis per material.

5. **Manajemen Tenaga Kerja & Payroll Proyek**:
   - Master pekerja: Karyawan tetap, pekerja harian, dan tim borongan.
   - Pencatatan absensi harian dan lembur proyek.
   - Kasbon (Pinjaman Karyawan) dengan pemotongan angsuran otomatis saat periode payroll diproses.
   - Slip Gaji PDF individual dan rekap payroll per proyek.

6. **Manajemen Subkontraktor**:
   - SPK Subkontraktor, Berita Acara Progres (BAP), pencatatan tagihan dan termin pembayaran.

7. **Kas & Bank Terintegrasi**:
   - Kas Masuk, Kas Keluar, dan Transfer Antar Rekening (internal transfer tanpa mempengaruhi laba rugi perusahaan).
   - Pengelolaan petty cash (kas kecil) proyek dan rekonsiliasi saldo bank.

8. **Piutang Pelanggan (AR) & Hutang Supplier (AP)**:
   - Tagihan termin bertahap (Uang Muka, Termin Progres 1..N, Retensi 5%).
   - Pembayaran parsial / lunas dengan kwitansi resmi.
   - Laporan Aging Piutang dan Aging Hutang (0-30, 31-60, 61-90, >90 hari).

9. **Double-Entry Accounting Otomatis**:
   - Bagan Akun Standar Kontraktor (Aset, Liabilitas, Ekuitas, Pendapatan Konstruksi, Biaya Langsung Proyek, Biaya Overhead).
   - Posting otomatis jurnal berpasangan seimbang (`Total Debit == Total Kredit`) saat transaksi di-post.
   - Mekanisme **Jurnal Pembalik (Reversal)** untuk koreksi transaksi berstatus posted tanpa menghapus audit trail historis.
   - Buku Besar (General Ledger), Neraca Saldo (Trial Balance), dan Penutupan Periode Fiskal.

10. **Pengendalian Biaya & Laba Rugi Proyek (Cost Control & P&L)**:
    - Analisis varians Anggaran Aktif vs Realisasi Biaya Aktual vs Komitmen PO yang belum ditagihkan.
    - Laba Rugi real-time per proyek (Pendapatan Diakui - Biaya Langsung Proyek - Alokasi Biaya Overhead).
    - Arus Kas (Cash Flow) aktual perusahaan dan per proyek.

11. **Keamanan & Audit Trail**:
    - Role-based access control (10 peran: Super Admin, Direktur, PM, Mandor, Accounting, Purchasing, Gudang, HR, Estimator, Auditor).
    - Pencatatan seluruh aktivitas kritis pengguna pada tabel `activity_logs`.

---

## 🚀 Persyaratan Sistem

- **PHP**: 8.2 atau lebih baru (dengan ekstensi `pdo_mysql`, `mbstring`, `xml`, `curl`, `gd`, `zip`).
- **Composer**: 2.x
- **Node.js**: 18.x / 20.x & **NPM**
- **Database**: MySQL 8.0+ atau MariaDB 10.4+
- **Web Server**: Nginx / Apache / Caddy (atau bawaan PHP CLI untuk dev)

---

## 🛠️ Panduan Instalasi Lokal

### 1. Clone & Konfigurasi Lingkungan
```bash
cd "c:\project\contruction erp"
cp .env.example .env
```

Pastikan konfigurasi koneksi database di `.env` sesuai:
```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=construction_erp
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Install Dependensi PHP & Node
```bash
composer install
npm install
npm run build
```

### 3. Generate Application Key & Storage Symlink
```bash
php artisan key:generate
php artisan storage:link
```

### 4. Jalankan Migrasi & Seeder Database
```bash
php artisan migrate --seed
```

> **Data Default Administrator**:
> - **URL Login**: `http://localhost:8000/login`
> - **Email**: `admin@konstruksimaju.co.id`
> - **Password**: `Admin123!`

### 5. Menjalankan Server Lokal
```bash
php artisan serve --port=8000
```
Akses di browser: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Pengujian Otomatis (PHPUnit)

Jalankan seluruh test suite bisnis untuk memvalidasi integritas logika perbankan, akuntansi double-entry, moving average inventory, dan payroll:

```bash
php artisan test
```

Semua 11 test kasus bisnis utama diuji secara ketat:
- `user can login and access dashboard`
- `rab margin and markup calculation`
- `procurement po and goods receipt`
- `inventory issuance to project`
- `payroll calculation and kasbon deduction`
- `double entry balancing and reversal`
- `project cost service`
- `customer invoice and payment`
- `supplier invoice and payment`

---

## 📄 Panduan Deployment Produksi

Silakan buka dokumentasi lengkap di [DEPLOYMENT.md](file:///C:/project/contruction%20erp/DEPLOYMENT.md) untuk panduan konfigurasi Nginx, cPanel Shared Hosting, Supervisor Worker, dan Cron Scheduler.
