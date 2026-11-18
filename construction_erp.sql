-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2026 at 10:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `construction_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_mappings`
--

CREATE TABLE `account_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mapping_key` varchar(255) NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_mappings`
--

INSERT INTO `account_mappings` (`id`, `mapping_key`, `chart_of_account_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'cash_default', 1, 'Akun Kas Tunai Default', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'bank_default', 2, 'Akun Bank Operasional Default', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 'ar_customer', 4, 'Piutang Usaha Pelanggan', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 'inventory_material', 6, 'Persediaan Material Konstruksi', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, 'advance_employee', 7, 'Uang Muka & Kasbon Pekerja', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(6, 'ap_supplier', 11, 'Hutang Usaha Supplier', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(7, 'wage_payable', 12, 'Utang Gaji & Upah Tenaga Kerja', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(8, 'subcon_payable', 13, 'Utang Subkontraktor', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(9, 'unearned_revenue', 14, 'Uang Muka Diterima Pelanggan', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(10, 'revenue_construction', 19, 'Pendapatan Jasa Konstruksi', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(11, 'cogs_material', 22, 'Beban Pokok Material Proyek', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(12, 'cogs_labor', 23, 'Beban Pokok Upah & Tenaga Kerja', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(13, 'cogs_subcon', 24, 'Beban Pokok Subkontraktor', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(14, 'cogs_equipment', 25, 'Beban Pokok Alat Berat & Peralatan', '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `old_values`, `new_values`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, NULL, 'posted', 'JournalEntry', 1, 'Posting Jurnal Umum: JE-202610-0001 (Penerimaan Material dari PO PO-202602-0001 (Surat Jalan: SJ-KS-99882))', NULL, NULL, '127.0.0.1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, NULL, 'verified', 'GoodsReceipt', 1, 'Verifikasi Penerimaan Barang GR-202602-0001 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, NULL, 'posted', 'JournalEntry', 2, 'Posting Jurnal Umum: JE-202610-0002 (Pengeluaran Material ke Proyek: STK-OUT-202610-0001)', NULL, NULL, '127.0.0.1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, NULL, 'created', 'InventoryTransaction', 2, 'Pengeluaran material STK-OUT-202610-0001 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, NULL, 'posted', 'JournalEntry', 3, 'Posting Jurnal Umum: JE-202610-0003 (Penerbitan Invoice INV-202602-0001 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(6, NULL, 'posted', 'JournalEntry', 4, 'Posting Jurnal Umum: JE-202610-0004 (Penerimaan Pembayaran Pelanggan: PAY-AR-202602-0001)', NULL, NULL, '127.0.0.1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(7, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:01:41', '2026-10-14 03:01:41'),
(8, NULL, 'posted', 'JournalEntry', 5, 'Posting Jurnal Umum: JE-202610-0005 (Penerimaan Material dari PO PO-TEST-6acef0974b36b (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(9, NULL, 'verified', 'GoodsReceipt', 2, 'Verifikasi Penerimaan Barang GR-TEST-6acef0974e8b9 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(10, NULL, 'posted', 'JournalEntry', 6, 'Posting Jurnal Umum: JE-202610-0006 (Pengeluaran Material ke Proyek: STK-OUT-202610-0002)', NULL, NULL, '127.0.0.1', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(11, NULL, 'created', 'InventoryTransaction', 4, 'Pengeluaran material STK-OUT-202610-0002 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(12, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(13, NULL, 'posted', 'JournalEntry', 7, 'Posting Jurnal Umum: JE-202610-0007 (Penerimaan Material dari PO PO-TEST-6acef170b36f9 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(14, NULL, 'verified', 'GoodsReceipt', 3, 'Verifikasi Penerimaan Barang GR-TEST-6acef170b6b34 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(15, NULL, 'posted', 'JournalEntry', 8, 'Posting Jurnal Umum: JE-202610-0008 (Pengeluaran Material ke Proyek: STK-OUT-202610-0003)', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(16, NULL, 'created', 'InventoryTransaction', 6, 'Pengeluaran material STK-OUT-202610-0003 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(17, NULL, 'calculated', 'PayrollPeriod', 1, 'Kalkulasi Payroll periode PAY-TEST-6acef170ee8b3 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:21', '2026-10-14 03:05:21'),
(18, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(19, NULL, 'posted', 'JournalEntry', 9, 'Posting Jurnal Umum: JE-202610-0009 (Penerimaan Material dari PO PO-TEST-6acef185b2ee8 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(20, NULL, 'verified', 'GoodsReceipt', 4, 'Verifikasi Penerimaan Barang GR-TEST-6acef185b5816 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(21, NULL, 'posted', 'JournalEntry', 10, 'Posting Jurnal Umum: JE-202610-0010 (Pengeluaran Material ke Proyek: STK-OUT-202610-0004)', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(22, NULL, 'created', 'InventoryTransaction', 8, 'Pengeluaran material STK-OUT-202610-0004 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(23, NULL, 'calculated', 'PayrollPeriod', 2, 'Kalkulasi Payroll periode PAY-TEST-6acef186009f2 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(24, NULL, 'posted', 'JournalEntry', 11, 'Posting Jurnal Umum: JE-202610-0011 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(25, NULL, 'reversed', 'JournalEntry', 11, 'Jurnal JE-202610-0011 dibalik dengan nomor REV-202610-0001. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(26, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:16', '2026-10-14 03:06:16'),
(27, NULL, 'posted', 'JournalEntry', 13, 'Posting Jurnal Umum: JE-202610-0012 (Penerimaan Material dari PO PO-TEST-6acef1a90b7ed (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(28, NULL, 'verified', 'GoodsReceipt', 5, 'Verifikasi Penerimaan Barang GR-TEST-6acef1a90f2e7 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(29, NULL, 'posted', 'JournalEntry', 14, 'Posting Jurnal Umum: JE-202610-0013 (Pengeluaran Material ke Proyek: STK-OUT-202610-0005)', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(30, NULL, 'created', 'InventoryTransaction', 10, 'Pengeluaran material STK-OUT-202610-0005 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(31, NULL, 'calculated', 'PayrollPeriod', 3, 'Kalkulasi Payroll periode PAY-TEST-6acef1a95f0ca (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(32, NULL, 'posted', 'JournalEntry', 15, 'Posting Jurnal Umum: JE-202610-0014 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(33, NULL, 'reversed', 'JournalEntry', 15, 'Jurnal JE-202610-0014 dibalik dengan nomor REV-202610-0002. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(34, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:28', '2026-10-14 03:09:28'),
(35, NULL, 'posted', 'JournalEntry', 17, 'Posting Jurnal Umum: JE-202610-0015 (Penerimaan Material dari PO PO-TEST-6acef26920af6 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(36, NULL, 'verified', 'GoodsReceipt', 6, 'Verifikasi Penerimaan Barang GR-TEST-6acef26924078 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(37, NULL, 'posted', 'JournalEntry', 18, 'Posting Jurnal Umum: JE-202610-0016 (Pengeluaran Material ke Proyek: STK-OUT-202610-0006)', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(38, NULL, 'created', 'InventoryTransaction', 12, 'Pengeluaran material STK-OUT-202610-0006 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(39, NULL, 'calculated', 'PayrollPeriod', 4, 'Kalkulasi Payroll periode PAY-TEST-6acef2697c1ef (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(40, NULL, 'posted', 'JournalEntry', 19, 'Posting Jurnal Umum: JE-202610-0017 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(41, NULL, 'reversed', 'JournalEntry', 19, 'Jurnal JE-202610-0017 dibalik dengan nomor REV-202610-0003. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(42, 1, 'posted', 'JournalEntry', 21, 'Posting Jurnal Umum: JE-202610-0018 (Penerbitan Invoice INV-CLI-202610-0001 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(43, 1, 'created', 'CustomerInvoice', 2, 'Menerbitkan invoice piutang INV-CLI-202610-0001 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(44, 1, 'posted', 'JournalEntry', 22, 'Posting Jurnal Umum: JE-202610-0019 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0001)', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(45, 1, 'paid', 'CustomerPayment', 2, 'Menerima pelunasan piutang KWT-CLI-202610-0001 Rp 10.545.000 untuk invoice INV-CLI-202610-0001', NULL, NULL, '127.0.0.1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(46, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:10', '2026-10-14 03:11:10'),
(47, NULL, 'posted', 'JournalEntry', 23, 'Posting Jurnal Umum: JE-202610-0020 (Penerimaan Material dari PO PO-TEST-6acef2cf37fc3 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(48, NULL, 'verified', 'GoodsReceipt', 7, 'Verifikasi Penerimaan Barang GR-TEST-6acef2cf3aa69 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(49, NULL, 'posted', 'JournalEntry', 24, 'Posting Jurnal Umum: JE-202610-0021 (Pengeluaran Material ke Proyek: STK-OUT-202610-0007)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(50, NULL, 'created', 'InventoryTransaction', 14, 'Pengeluaran material STK-OUT-202610-0007 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(51, NULL, 'calculated', 'PayrollPeriod', 5, 'Kalkulasi Payroll periode PAY-TEST-6acef2cf6becb (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(52, NULL, 'posted', 'JournalEntry', 25, 'Posting Jurnal Umum: JE-202610-0022 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(53, NULL, 'reversed', 'JournalEntry', 25, 'Jurnal JE-202610-0022 dibalik dengan nomor REV-202610-0004. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(54, 1, 'posted', 'JournalEntry', 27, 'Posting Jurnal Umum: JE-202610-0023 (Penerbitan Invoice INV-CLI-202610-0002 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(55, 1, 'created', 'CustomerInvoice', 3, 'Menerbitkan invoice piutang INV-CLI-202610-0002 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(56, 1, 'posted', 'JournalEntry', 28, 'Posting Jurnal Umum: JE-202610-0024 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0002)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(57, 1, 'paid', 'CustomerPayment', 3, 'Menerima pelunasan piutang KWT-CLI-202610-0002 Rp 10.545.000 untuk invoice INV-CLI-202610-0002', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(58, 1, 'posted', 'JournalEntry', 29, 'Posting Jurnal Umum: JE-202610-0025 (Pembayaran Hutang Supplier: KWT-SUP-202610-0001)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(59, 1, 'paid', 'SupplierPayment', 2, 'Membayar hutang supplier KWT-SUP-202610-0001 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acef2cfbc0b0', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(60, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(61, NULL, 'posted', 'JournalEntry', 30, 'Posting Jurnal Umum: JE-202610-0026 (Penerimaan Material dari PO PO-TEST-6acef2d5b3a6a (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(62, NULL, 'verified', 'GoodsReceipt', 8, 'Verifikasi Penerimaan Barang GR-TEST-6acef2d5b62be & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(63, NULL, 'posted', 'JournalEntry', 31, 'Posting Jurnal Umum: JE-202610-0027 (Pengeluaran Material ke Proyek: STK-OUT-202610-0008)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(64, NULL, 'created', 'InventoryTransaction', 16, 'Pengeluaran material STK-OUT-202610-0008 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(65, NULL, 'calculated', 'PayrollPeriod', 6, 'Kalkulasi Payroll periode PAY-TEST-6acef2d5e7ca2 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(66, NULL, 'posted', 'JournalEntry', 32, 'Posting Jurnal Umum: JE-202610-0028 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(67, NULL, 'reversed', 'JournalEntry', 32, 'Jurnal JE-202610-0028 dibalik dengan nomor REV-202610-0005. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(68, 1, 'posted', 'JournalEntry', 34, 'Posting Jurnal Umum: JE-202610-0029 (Penerbitan Invoice INV-CLI-202610-0003 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(69, 1, 'created', 'CustomerInvoice', 4, 'Menerbitkan invoice piutang INV-CLI-202610-0003 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(70, 1, 'posted', 'JournalEntry', 35, 'Posting Jurnal Umum: JE-202610-0030 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0003)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(71, 1, 'paid', 'CustomerPayment', 4, 'Menerima pelunasan piutang KWT-CLI-202610-0003 Rp 10.545.000 untuk invoice INV-CLI-202610-0003', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(72, 1, 'posted', 'JournalEntry', 36, 'Posting Jurnal Umum: JE-202610-0031 (Pembayaran Hutang Supplier: KWT-SUP-202610-0002)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(73, 1, 'paid', 'SupplierPayment', 3, 'Membayar hutang supplier KWT-SUP-202610-0002 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acef2d68a815', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(74, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(75, NULL, 'posted', 'JournalEntry', 37, 'Posting Jurnal Umum: JE-202610-0032 (Penerimaan Material dari PO PO-TEST-6acef2e7de253 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(76, NULL, 'verified', 'GoodsReceipt', 9, 'Verifikasi Penerimaan Barang GR-TEST-6acef2e7e0a31 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(77, NULL, 'posted', 'JournalEntry', 38, 'Posting Jurnal Umum: JE-202610-0033 (Pengeluaran Material ke Proyek: STK-OUT-202610-0009)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(78, NULL, 'created', 'InventoryTransaction', 18, 'Pengeluaran material STK-OUT-202610-0009 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(79, NULL, 'calculated', 'PayrollPeriod', 7, 'Kalkulasi Payroll periode PAY-TEST-6acef2e857654 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(80, NULL, 'posted', 'JournalEntry', 39, 'Posting Jurnal Umum: JE-202610-0034 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(81, NULL, 'reversed', 'JournalEntry', 39, 'Jurnal JE-202610-0034 dibalik dengan nomor REV-202610-0006. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(82, 1, 'posted', 'JournalEntry', 41, 'Posting Jurnal Umum: JE-202610-0035 (Penerbitan Invoice INV-CLI-202610-0004 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(83, 1, 'created', 'CustomerInvoice', 5, 'Menerbitkan invoice piutang INV-CLI-202610-0004 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(84, 1, 'posted', 'JournalEntry', 42, 'Posting Jurnal Umum: JE-202610-0036 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0004)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(85, 1, 'paid', 'CustomerPayment', 5, 'Menerima pelunasan piutang KWT-CLI-202610-0004 Rp 10.545.000 untuk invoice INV-CLI-202610-0004', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(86, 1, 'posted', 'JournalEntry', 43, 'Posting Jurnal Umum: JE-202610-0037 (Pembayaran Hutang Supplier: KWT-SUP-202610-0003)', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(87, 1, 'paid', 'SupplierPayment', 4, 'Membayar hutang supplier KWT-SUP-202610-0003 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acef2e8cf125', NULL, NULL, '127.0.0.1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(88, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(89, NULL, 'posted', 'JournalEntry', 44, 'Posting Jurnal Umum: JE-202610-0038 (Penerimaan Material dari PO PO-TEST-6acef304c3b1f (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(90, NULL, 'verified', 'GoodsReceipt', 10, 'Verifikasi Penerimaan Barang GR-TEST-6acef304c6b58 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(91, NULL, 'posted', 'JournalEntry', 45, 'Posting Jurnal Umum: JE-202610-0039 (Pengeluaran Material ke Proyek: STK-OUT-202610-0010)', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(92, NULL, 'created', 'InventoryTransaction', 20, 'Pengeluaran material STK-OUT-202610-0010 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(93, NULL, 'calculated', 'PayrollPeriod', 8, 'Kalkulasi Payroll periode PAY-TEST-6acef30532ad1 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(94, NULL, 'posted', 'JournalEntry', 46, 'Posting Jurnal Umum: JE-202610-0040 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(95, NULL, 'reversed', 'JournalEntry', 46, 'Jurnal JE-202610-0040 dibalik dengan nomor REV-202610-0007. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(96, 1, 'posted', 'JournalEntry', 48, 'Posting Jurnal Umum: JE-202610-0041 (Penerbitan Invoice INV-CLI-202610-0005 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(97, 1, 'created', 'CustomerInvoice', 6, 'Menerbitkan invoice piutang INV-CLI-202610-0005 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(98, 1, 'posted', 'JournalEntry', 49, 'Posting Jurnal Umum: JE-202610-0042 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0005)', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(99, 1, 'paid', 'CustomerPayment', 6, 'Menerima pelunasan piutang KWT-CLI-202610-0005 Rp 10.545.000 untuk invoice INV-CLI-202610-0005', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(100, 1, 'posted', 'JournalEntry', 50, 'Posting Jurnal Umum: JE-202610-0043 (Pembayaran Hutang Supplier: KWT-SUP-202610-0004)', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(101, 1, 'paid', 'SupplierPayment', 5, 'Membayar hutang supplier KWT-SUP-202610-0004 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acef305d7ab3', NULL, NULL, '127.0.0.1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(102, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:30:14', '2026-10-14 03:30:14'),
(103, 1, 'created', 'MaterialRequest', 1, 'Membuat Permintaan Material MR-202610-0001 untuk Proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:32:36', '2026-10-14 03:32:36'),
(104, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(105, NULL, 'posted', 'JournalEntry', 51, 'Posting Jurnal Umum: JE-202610-0044 (Penerimaan Material dari PO PO-TEST-6acef81de2b64 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(106, NULL, 'verified', 'GoodsReceipt', 11, 'Verifikasi Penerimaan Barang GR-TEST-6acef81de4d99 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(107, NULL, 'posted', 'JournalEntry', 52, 'Posting Jurnal Umum: JE-202610-0045 (Pengeluaran Material ke Proyek: STK-OUT-202610-0011)', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(108, NULL, 'created', 'InventoryTransaction', 22, 'Pengeluaran material STK-OUT-202610-0011 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(109, NULL, 'calculated', 'PayrollPeriod', 9, 'Kalkulasi Payroll periode PAY-TEST-6acef81e163ba (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(110, NULL, 'posted', 'JournalEntry', 53, 'Posting Jurnal Umum: JE-202610-0046 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(111, NULL, 'reversed', 'JournalEntry', 53, 'Jurnal JE-202610-0046 dibalik dengan nomor REV-202610-0008. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(112, 1, 'posted', 'JournalEntry', 55, 'Posting Jurnal Umum: JE-202610-0047 (Penerbitan Invoice INV-CLI-202610-0006 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(113, 1, 'created', 'CustomerInvoice', 7, 'Menerbitkan invoice piutang INV-CLI-202610-0006 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(114, 1, 'posted', 'JournalEntry', 56, 'Posting Jurnal Umum: JE-202610-0048 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0006)', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(115, 1, 'paid', 'CustomerPayment', 7, 'Menerima pelunasan piutang KWT-CLI-202610-0006 Rp 10.545.000 untuk invoice INV-CLI-202610-0006', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(116, 1, 'posted', 'JournalEntry', 57, 'Posting Jurnal Umum: JE-202610-0049 (Pembayaran Hutang Supplier: KWT-SUP-202610-0005)', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(117, 1, 'paid', 'SupplierPayment', 6, 'Membayar hutang supplier KWT-SUP-202610-0005 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acef81e6cae1', NULL, NULL, '127.0.0.1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(118, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:56', '2026-10-14 04:02:56'),
(119, NULL, 'posted', 'JournalEntry', 58, 'Posting Jurnal Umum: JE-202610-0050 (Penerimaan Material dari PO PO-TEST-6acefef0ef2ed (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(120, NULL, 'verified', 'GoodsReceipt', 12, 'Verifikasi Penerimaan Barang GR-TEST-6acefef0f314b & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(121, NULL, 'posted', 'JournalEntry', 59, 'Posting Jurnal Umum: JE-202610-0051 (Pengeluaran Material ke Proyek: STK-OUT-202610-0012)', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(122, NULL, 'created', 'InventoryTransaction', 24, 'Pengeluaran material STK-OUT-202610-0012 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(123, NULL, 'calculated', 'PayrollPeriod', 10, 'Kalkulasi Payroll periode PAY-TEST-6acefef15a482 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(124, NULL, 'posted', 'JournalEntry', 60, 'Posting Jurnal Umum: JE-202610-0052 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(125, NULL, 'reversed', 'JournalEntry', 60, 'Jurnal JE-202610-0052 dibalik dengan nomor REV-202610-0009. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(126, 1, 'posted', 'JournalEntry', 62, 'Posting Jurnal Umum: JE-202610-0053 (Penerbitan Invoice INV-CLI-202610-0007 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(127, 1, 'created', 'CustomerInvoice', 8, 'Menerbitkan invoice piutang INV-CLI-202610-0007 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(128, 1, 'posted', 'JournalEntry', 63, 'Posting Jurnal Umum: JE-202610-0054 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0007)', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(129, 1, 'paid', 'CustomerPayment', 8, 'Menerima pelunasan piutang KWT-CLI-202610-0007 Rp 10.545.000 untuk invoice INV-CLI-202610-0007', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(130, 1, 'posted', 'JournalEntry', 64, 'Posting Jurnal Umum: JE-202610-0055 (Pembayaran Hutang Supplier: KWT-SUP-202610-0006)', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(131, 1, 'paid', 'SupplierPayment', 7, 'Membayar hutang supplier KWT-SUP-202610-0006 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acefef23e34a', NULL, NULL, '127.0.0.1', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(132, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:19', '2026-10-14 04:10:19'),
(133, NULL, 'posted', 'JournalEntry', 65, 'Posting Jurnal Umum: JE-202610-0056 (Penerimaan Material dari PO PO-TEST-6acf00ac6636e (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(134, NULL, 'verified', 'GoodsReceipt', 13, 'Verifikasi Penerimaan Barang GR-TEST-6acf00ac6ac50 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(135, NULL, 'posted', 'JournalEntry', 66, 'Posting Jurnal Umum: JE-202610-0057 (Pengeluaran Material ke Proyek: STK-OUT-202610-0013)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(136, NULL, 'created', 'InventoryTransaction', 26, 'Pengeluaran material STK-OUT-202610-0013 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(137, NULL, 'calculated', 'PayrollPeriod', 11, 'Kalkulasi Payroll periode PAY-TEST-6acf00acb36be (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(138, NULL, 'posted', 'JournalEntry', 67, 'Posting Jurnal Umum: JE-202610-0058 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(139, NULL, 'reversed', 'JournalEntry', 67, 'Jurnal JE-202610-0058 dibalik dengan nomor REV-202610-0010. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(140, 1, 'posted', 'JournalEntry', 69, 'Posting Jurnal Umum: JE-202610-0059 (Penerbitan Invoice INV-CLI-202610-0008 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(141, 1, 'created', 'CustomerInvoice', 9, 'Menerbitkan invoice piutang INV-CLI-202610-0008 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(142, 1, 'posted', 'JournalEntry', 70, 'Posting Jurnal Umum: JE-202610-0060 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0008)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(143, 1, 'paid', 'CustomerPayment', 9, 'Menerima pelunasan piutang KWT-CLI-202610-0008 Rp 10.545.000 untuk invoice INV-CLI-202610-0008', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(144, 1, 'posted', 'JournalEntry', 71, 'Posting Jurnal Umum: JE-202610-0061 (Pembayaran Hutang Supplier: KWT-SUP-202610-0007)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(145, 1, 'paid', 'SupplierPayment', 8, 'Membayar hutang supplier KWT-SUP-202610-0007 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acf00ad5d5f8', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(146, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:29', '2026-10-14 04:10:29'),
(147, NULL, 'posted', 'JournalEntry', 72, 'Posting Jurnal Umum: JE-202610-0062 (Penerimaan Material dari PO PO-TEST-6acf00b62d924 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(148, NULL, 'verified', 'GoodsReceipt', 14, 'Verifikasi Penerimaan Barang GR-TEST-6acf00b6332e0 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(149, NULL, 'posted', 'JournalEntry', 73, 'Posting Jurnal Umum: JE-202610-0063 (Pengeluaran Material ke Proyek: STK-OUT-202610-0014)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(150, NULL, 'created', 'InventoryTransaction', 28, 'Pengeluaran material STK-OUT-202610-0014 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(151, NULL, 'calculated', 'PayrollPeriod', 12, 'Kalkulasi Payroll periode PAY-TEST-6acf00b68b8ac (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(152, NULL, 'posted', 'JournalEntry', 74, 'Posting Jurnal Umum: JE-202610-0064 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(153, NULL, 'reversed', 'JournalEntry', 74, 'Jurnal JE-202610-0064 dibalik dengan nomor REV-202610-0011. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(154, 1, 'posted', 'JournalEntry', 76, 'Posting Jurnal Umum: JE-202610-0065 (Penerbitan Invoice INV-CLI-202610-0009 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(155, 1, 'created', 'CustomerInvoice', 10, 'Menerbitkan invoice piutang INV-CLI-202610-0009 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(156, 1, 'posted', 'JournalEntry', 77, 'Posting Jurnal Umum: JE-202610-0066 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0009)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(157, 1, 'paid', 'CustomerPayment', 10, 'Menerima pelunasan piutang KWT-CLI-202610-0009 Rp 10.545.000 untuk invoice INV-CLI-202610-0009', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(158, 1, 'posted', 'JournalEntry', 78, 'Posting Jurnal Umum: JE-202610-0067 (Pembayaran Hutang Supplier: KWT-SUP-202610-0008)', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(159, 1, 'paid', 'SupplierPayment', 9, 'Membayar hutang supplier KWT-SUP-202610-0008 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acf00b7150b8', NULL, NULL, '127.0.0.1', '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(160, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:38', '2026-10-14 04:13:38'),
(161, NULL, 'posted', 'JournalEntry', 79, 'Posting Jurnal Umum: JE-202610-0068 (Penerimaan Material dari PO PO-TEST-6acf0173323d0 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(162, NULL, 'verified', 'GoodsReceipt', 15, 'Verifikasi Penerimaan Barang GR-TEST-6acf017334d51 & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(163, NULL, 'posted', 'JournalEntry', 80, 'Posting Jurnal Umum: JE-202610-0069 (Pengeluaran Material ke Proyek: STK-OUT-202610-0015)', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(164, NULL, 'created', 'InventoryTransaction', 30, 'Pengeluaran material STK-OUT-202610-0015 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(165, NULL, 'calculated', 'PayrollPeriod', 13, 'Kalkulasi Payroll periode PAY-TEST-6acf01737f5f9 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(166, NULL, 'posted', 'JournalEntry', 81, 'Posting Jurnal Umum: JE-202610-0070 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(167, NULL, 'reversed', 'JournalEntry', 81, 'Jurnal JE-202610-0070 dibalik dengan nomor REV-202610-0012. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(168, 1, 'posted', 'JournalEntry', 83, 'Posting Jurnal Umum: JE-202610-0071 (Penerbitan Invoice INV-CLI-202610-0010 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(169, 1, 'created', 'CustomerInvoice', 11, 'Menerbitkan invoice piutang INV-CLI-202610-0010 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(170, 1, 'posted', 'JournalEntry', 84, 'Posting Jurnal Umum: JE-202610-0072 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0010)', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(171, 1, 'paid', 'CustomerPayment', 11, 'Menerima pelunasan piutang KWT-CLI-202610-0010 Rp 10.545.000 untuk invoice INV-CLI-202610-0010', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(172, 1, 'posted', 'JournalEntry', 85, 'Posting Jurnal Umum: JE-202610-0073 (Pembayaran Hutang Supplier: KWT-SUP-202610-0009)', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(173, 1, 'paid', 'SupplierPayment', 10, 'Membayar hutang supplier KWT-SUP-202610-0009 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acf017411ab7', NULL, NULL, '127.0.0.1', '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(174, 1, 'login', 'User', 1, 'User Administrator Utama berhasil masuk ke sistem', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(175, NULL, 'posted', 'JournalEntry', 86, 'Posting Jurnal Umum: JE-202610-0074 (Penerimaan Material dari PO PO-TEST-6acf03bb7cde0 (Surat Jalan: SJ-VENDOR-999))', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(176, NULL, 'verified', 'GoodsReceipt', 16, 'Verifikasi Penerimaan Barang GR-TEST-6acf03bb821fa & update stok gudang', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(177, NULL, 'posted', 'JournalEntry', 87, 'Posting Jurnal Umum: JE-202610-0075 (Pengeluaran Material ke Proyek: STK-OUT-202610-0016)', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(178, NULL, 'created', 'InventoryTransaction', 32, 'Pengeluaran material STK-OUT-202610-0016 ke proyek ID 1', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(179, NULL, 'calculated', 'PayrollPeriod', 14, 'Kalkulasi Payroll periode PAY-TEST-6acf03bc0b4e6 (Test Period)', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(180, NULL, 'posted', 'JournalEntry', 88, 'Posting Jurnal Umum: JE-202610-0076 (Test Manual Journal Entry)', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(181, NULL, 'reversed', 'JournalEntry', 88, 'Jurnal JE-202610-0076 dibalik dengan nomor REV-202610-0013. Alasan: Koreksi pengujian', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(182, 1, 'posted', 'JournalEntry', 90, 'Posting Jurnal Umum: JE-202610-0077 (Penerbitan Invoice INV-CLI-202610-0011 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land))', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(183, 1, 'created', 'CustomerInvoice', 12, 'Menerbitkan invoice piutang INV-CLI-202610-0011 senilai Rp 10.545.000 ke PT Bumi Serpong Damai Tbk (Sinarmas Land)', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(184, 1, 'posted', 'JournalEntry', 91, 'Posting Jurnal Umum: JE-202610-0078 (Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0011)', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(185, 1, 'paid', 'CustomerPayment', 12, 'Menerima pelunasan piutang KWT-CLI-202610-0011 Rp 10.545.000 untuk invoice INV-CLI-202610-0011', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(186, 1, 'posted', 'JournalEntry', 92, 'Posting Jurnal Umum: JE-202610-0079 (Pembayaran Hutang Supplier: KWT-SUP-202610-0010)', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:25', '2026-10-14 04:23:25'),
(187, 1, 'paid', 'SupplierPayment', 11, 'Membayar hutang supplier KWT-SUP-202610-0010 Rp 2.220.000 untuk faktur INV-SUP-TEST-6acf03bcef348', NULL, NULL, '127.0.0.1', '2026-10-14 04:23:25', '2026-10-14 04:23:25');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `approvable_type` varchar(255) NOT NULL,
  `approvable_id` bigint(20) UNSIGNED NOT NULL,
  `step` int(11) NOT NULL DEFAULT 1,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `comments` text DEFAULT NULL,
  `action_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('present','half_day','absent','leave','sick') NOT NULL DEFAULT 'present',
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `regular_hours` decimal(5,2) NOT NULL DEFAULT 8.00,
  `overtime_hours` decimal(5,2) NOT NULL DEFAULT 0.00,
  `daily_wage` decimal(15,2) NOT NULL DEFAULT 0.00,
  `overtime_wage` decimal(15,2) NOT NULL DEFAULT 0.00,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `employee_id`, `project_id`, `date`, `status`, `check_in`, `check_out`, `regular_hours`, `overtime_hours`, `daily_wage`, `overtime_wage`, `verified_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-10-13', 'present', NULL, NULL, 8.00, 2.00, 220000.00, 90000.00, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(2, 1, 1, '2026-10-12', 'present', NULL, NULL, 8.00, 2.00, 220000.00, 90000.00, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(3, 1, 1, '2026-10-11', 'present', NULL, NULL, 8.00, 2.00, 220000.00, 90000.00, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(4, 1, 1, '2026-10-10', 'present', NULL, NULL, 8.00, 2.00, 220000.00, 90000.00, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(5, 1, 1, '2026-10-09', 'present', NULL, NULL, 8.00, 2.00, 220000.00, 90000.00, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `type` enum('cash','bank') NOT NULL DEFAULT 'bank',
  `initial_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `coa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `code`, `bank_name`, `account_number`, `account_name`, `branch`, `type`, `initial_balance`, `current_balance`, `coa_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'KAS-KANTOR', 'Kas Tunai Kantor & Brankas', 'KAS-01', 'Kas Operasional', NULL, 'cash', 25000000.00, 497780000.00, 1, 1, '2026-10-14 02:22:08', '2026-10-14 04:23:25'),
(2, 'BCA-OPERASIONAL', 'Bank BCA', '542-019-8822', 'PT Konstruksi Maju Nusantara', 'KCU Sudirman', 'bank', 750000000.00, 750000000.00, 2, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 'MANDIRI-PROYEK', 'Bank Mandiri', '123-00-9876543-2', 'PT Konstruksi Maju Nusantara - Proyek', 'KC Thamrin', 'bank', 500000000.00, 500000000.00, 3, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transfers`
--

CREATE TABLE `bank_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_number` varchar(255) NOT NULL,
  `transfer_date` date NOT NULL,
  `from_bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `to_bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transfer_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `rab_number` varchar(255) NOT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `direct_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `overhead_percent` decimal(5,2) NOT NULL DEFAULT 5.00,
  `overhead_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `contingency_percent` decimal(5,2) NOT NULL DEFAULT 3.00,
  `contingency_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_rab_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `pricing_method` enum('markup','margin') NOT NULL DEFAULT 'margin',
  `target_margin_percent` decimal(5,2) NOT NULL DEFAULT 15.00,
  `target_markup_percent` decimal(5,2) NOT NULL DEFAULT 17.65,
  `offered_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','submitted','approved','revised','rejected') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `project_id`, `rab_number`, `version`, `title`, `direct_cost`, `overhead_percent`, `overhead_cost`, `contingency_percent`, `contingency_cost`, `total_rab_amount`, `pricing_method`, `target_margin_percent`, `target_markup_percent`, `offered_price`, `status`, `approved_by`, `approved_at`, `revision_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'RAB-2026-001', 1, 'RAB Pembangunan Gudang Struktur Baja Karawang 2.400 m2', 2850000000.00, 7.00, 199500000.00, 5.28, 150500000.00, 3200000000.00, 'margin', 16.88, 17.65, 3850000000.00, 'approved', 1, '2026-01-28 07:00:00', NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `budget_divisions`
--

CREATE TABLE `budget_divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_id` bigint(20) UNSIGNED NOT NULL,
  `division_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_divisions`
--

INSERT INTO `budget_divisions` (`id`, `budget_id`, `division_code`, `name`, `order_number`, `created_at`, `updated_at`) VALUES
(1, 1, 'DIV-01', 'Pekerjaan Struktur Bawah & Pondasi', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 1, 'DIV-02', 'Pekerjaan Pengadaan & Erection Rangka Baja Utama', 2, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `budget_items`
--

CREATE TABLE `budget_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_division_id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `spec` varchar(255) DEFAULT NULL,
  `volume` decimal(12,4) NOT NULL DEFAULT 1.0000,
  `unit` varchar(255) NOT NULL DEFAULT 'm3',
  `unit_material_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit_labor_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit_equipment_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit_subcon_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `direct_unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_direct_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `offered_unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_offered_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_items`
--

INSERT INTO `budget_items` (`id`, `budget_division_id`, `item_code`, `name`, `spec`, `volume`, `unit`, `unit_material_price`, `unit_labor_price`, `unit_equipment_price`, `unit_subcon_price`, `direct_unit_cost`, `total_direct_cost`, `offered_unit_price`, `total_offered_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'ITM-01.01', 'Pengecoran Pondasi Pedestal Beton Bertulang K-300', 'Ready Mix K-300 + Pembesian Ulir D16 + Bekisting Film Face', 180.0000, 'm3', 1850000.00, 450000.00, 0.00, 0.00, 2300000.00, 414000000.00, 2750000.00, 495000000.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 2, 'ITM-02.01', 'Pengadaan & Fabrikasi Rangka Kolom Baja Profil WF 200', 'WF 200x100x5.5x8 Gunung Garuda, Cat Primer Zinchromate', 320.0000, 'btg', 3850000.00, 650000.00, 0.00, 0.00, 4500000.00, 1440000000.00, 5400000.00, 1728000000.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `budget_item_components`
--

CREATE TABLE `budget_item_components` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_item_id` bigint(20) UNSIGNED NOT NULL,
  `component_type` enum('material','labor','equipment','subcon') NOT NULL,
  `material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `coefficient` decimal(12,4) NOT NULL DEFAULT 1.0000,
  `unit` varchar(255) NOT NULL DEFAULT 'zak',
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_transactions`
--

CREATE TABLE `cash_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_number` varchar(255) NOT NULL,
  `transaction_date` date NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `counterparty` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `receipt_attachment` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('posted','reversed') NOT NULL DEFAULT 'posted',
  `coa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('asset','liability','equity','revenue','direct_expense','indirect_expense') NOT NULL,
  `normal_balance` enum('debit','credit') NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_reconcilable` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `normal_balance`, `parent_id`, `is_active`, `is_reconcilable`, `created_at`, `updated_at`) VALUES
(1, '1-10101', 'Kas Tunai Kantor & Proyek', 'asset', 'debit', NULL, 1, 1, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(2, '1-10102', 'Bank BCA Operasional', 'asset', 'debit', NULL, 1, 1, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(3, '1-10103', 'Bank Mandiri Proyek', 'asset', 'debit', NULL, 1, 1, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(4, '1-10200', 'Piutang Usaha Pelanggan (AR)', 'asset', 'debit', NULL, 1, 0, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(5, '1-10250', 'Piutang Retensi Proyek', 'asset', 'debit', NULL, 1, 0, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(6, '1-10300', 'Persediaan Material Konstruksi', 'asset', 'debit', NULL, 1, 0, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(7, '1-10400', 'Uang Muka Karyawan & Kasbon', 'asset', 'debit', NULL, 1, 0, '2026-10-14 02:22:07', '2026-10-14 02:22:07'),
(8, '1-10500', 'Uang Muka Pembelian Supplier', 'asset', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(9, '1-20100', 'Aset Tetap - Kendaraan & Alat Berat', 'asset', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(10, '1-20199', 'Akumulasi Penyusutan Alat Berat', 'asset', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(11, '2-10100', 'Utang Usaha Supplier (AP)', 'liability', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(12, '2-10200', 'Utang Upah Tenaga Kerja & Gaji', 'liability', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(13, '2-10300', 'Utang Subkontraktor Terakru', 'liability', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(14, '2-10400', 'Uang Muka Pelanggan / Termin Ditunda', 'liability', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(15, '2-10500', 'Utang Pajak (PPh & PPN)', 'liability', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(16, '3-10100', 'Modal Saham Disetor', 'equity', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(17, '3-10200', 'Saldo Laba Ditahan', 'equity', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(18, '3-10300', 'Laba / Rugi Tahun Berjalan', 'equity', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(19, '4-10100', 'Pendapatan Jasa Konstruksi', 'revenue', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(20, '4-10200', 'Pendapatan Pekerjaan Tambahan (VO)', 'revenue', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(21, '4-10300', 'Pendapatan Lain-lain', 'revenue', 'credit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(22, '5-10100', 'Beban Pokok - Pemakaian Material Proyek', 'direct_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(23, '5-10200', 'Beban Pokok - Upah & Tenaga Kerja Lapangan', 'direct_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(24, '5-10300', 'Beban Pokok - Subkontraktor', 'direct_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(25, '5-10400', 'Beban Pokok - Sewa & Alat Berat Proyek', 'direct_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(26, '5-10500', 'Beban Pokok - Mobilisasi & Transportasi', 'direct_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(27, '5-10600', 'Beban Pokok - Operasional & K3 Lapangan', 'direct_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(28, '6-10100', 'Beban Gaji & Tunjangan Staff Kantor', 'indirect_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(29, '6-10200', 'Beban Sewa & Utilitas Kantor', 'indirect_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(30, '6-10300', 'Beban Perlengkapan & ATK Kantor', 'indirect_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(31, '6-10400', 'Beban Administrasi Bank', 'indirect_expense', 'debit', NULL, 1, 0, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'PT. KONSTRUKSI MAJU NUSANTARA',
  `logo` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `bank_info` text DEFAULT NULL,
  `document_footer` varchar(255) DEFAULT NULL,
  `default_tax_rate` decimal(5,2) NOT NULL DEFAULT 11.00,
  `timezone` varchar(255) NOT NULL DEFAULT 'Asia/Jakarta',
  `currency` varchar(255) NOT NULL DEFAULT 'IDR',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `name`, `logo`, `address`, `phone`, `email`, `npwp`, `bank_info`, `document_footer`, `default_tax_rate`, `timezone`, `currency`, `created_at`, `updated_at`) VALUES
(1, 'PT. KONSTRUKSI MAJU NUSANTARA', NULL, 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan, DKI Jakarta 12190', '+62 21 520 8899', 'corporate@konstruksimaju.co.id', '01.234.567.8-012.000', 'Bank Mandiri Cabang Sudirman Rek: 123-00-9876543-2 a.n PT Konstruksi Maju Nusantara', 'Constructa ERP - Solusi Terpadu Kontraktor & Manajemen Proyek Konstruksi', 11.00, 'Asia/Jakarta', 'IDR', '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `type` enum('individual','corporate','government') NOT NULL DEFAULT 'corporate',
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `name`, `company_name`, `type`, `address`, `phone`, `email`, `npwp`, `contact_person`, `notes`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CUST-001', 'PT Bumi Serpong Damai Tbk (Sinarmas Land)', 'Sinarmas Land Group', 'corporate', 'Sinar Mas Land Plaza, BSD Green Office Park, Tangerang', '+62 21 5036 8888', 'procurement@sinarmasland.com', '01.321.456.7-054.000', 'Ibu Diana (VP Project Development)', NULL, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(2, 'CUST-002', 'Bapak Ir. Bambang Hermanto', NULL, 'individual', 'Jl. Bukit Hijau Indah Blok TC-12, Pondok Indah, Jakarta Selatan', '08118899221', 'bambang.hermanto@gmail.com', NULL, 'Bapak Bambang', NULL, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoices`
--

CREATE TABLE `customer_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `invoice_type` enum('down_payment','milestone_progress','final_settlement','retention','variation_order') NOT NULL DEFAULT 'milestone_progress',
  `milestone_description` varchar(255) NOT NULL,
  `progress_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `invoice_amount` decimal(15,2) NOT NULL,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `retention_deducted` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','sent','partially_paid','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_invoices`
--

INSERT INTO `customer_invoices` (`id`, `invoice_number`, `project_id`, `customer_id`, `invoice_date`, `due_date`, `invoice_type`, `milestone_description`, `progress_percent`, `invoice_amount`, `tax_percent`, `tax_amount`, `retention_deducted`, `net_amount`, `paid_amount`, `remaining_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'INV-202602-0001', 1, 1, '2026-02-05', '2026-02-20', 'down_payment', 'Uang Muka Kerja 20% Kontrak Karawang', 0.00, 770000000.00, 11.00, 84700000.00, 0.00, 854700000.00, 854700000.00, 0.00, 'paid', 'Uang muka telah diterima lunas via transfer BCA', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'INV-CLI-202610-0001', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acef269d4d61', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(3, 'INV-CLI-202610-0002', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acef2cf9f9f6', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(4, 'INV-CLI-202610-0003', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acef2d64bdbb', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(5, 'INV-CLI-202610-0004', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acef2e89ea82', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(6, 'INV-CLI-202610-0005', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acef30592a9d', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(7, 'INV-CLI-202610-0006', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acef81e438b7', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(8, 'INV-CLI-202610-0007', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acefef1e35dd', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:58'),
(9, 'INV-CLI-202610-0008', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acf00ad2228a', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(10, 'INV-CLI-202610-0009', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acf00b6da23d', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(11, 'INV-CLI-202610-0010', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acf0173d04a3', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(12, 'INV-CLI-202610-0011', 1, 1, '2026-10-14', '2026-10-28', 'milestone_progress', 'Test Termin 6acf03bc82408', 30.00, 10000000.00, 11.00, 1045000.00, 500000.00, 10545000.00, 10545000.00, 0.00, 'paid', NULL, '2026-10-14 04:23:24', '2026-10-14 04:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payments`
--

CREATE TABLE `customer_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_number` varchar(255) NOT NULL,
  `customer_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'transfer',
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_payments`
--

INSERT INTO `customer_payments` (`id`, `payment_number`, `customer_invoice_id`, `bank_account_id`, `payment_date`, `amount`, `payment_method`, `reference_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PAY-AR-202602-0001', 1, 2, '2026-02-18', 854700000.00, 'transfer', 'TRF-BCA-992211', 'Pembayaran Uang Muka dari Sinarmas Land', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'KWT-CLI-202610-0001', 2, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(3, 'KWT-CLI-202610-0002', 3, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(4, 'KWT-CLI-202610-0003', 4, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(5, 'KWT-CLI-202610-0004', 5, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(6, 'KWT-CLI-202610-0005', 6, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(7, 'KWT-CLI-202610-0006', 7, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(8, 'KWT-CLI-202610-0007', 8, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(9, 'KWT-CLI-202610-0008', 9, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(10, 'KWT-CLI-202610-0009', 10, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(11, 'KWT-CLI-202610-0010', 11, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(12, 'KWT-CLI-202610-0011', 12, 1, '2026-10-14', 10545000.00, 'transfer', 'REF-TEST-99', 'Pelunasan Test', 1, '2026-10-14 04:23:24', '2026-10-14 04:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(255) NOT NULL,
  `employee_number` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `role_title` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `employment_type` enum('permanent','contract','daily','piece_rate') NOT NULL DEFAULT 'daily',
  `daily_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `piece_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'transfer',
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_account_holder` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `nik`, `employee_number`, `name`, `position`, `role_title`, `phone`, `address`, `join_date`, `hire_date`, `employment_type`, `daily_rate`, `hourly_rate`, `piece_rate`, `payment_method`, `bank_name`, `bank_account_no`, `bank_account_number`, `bank_account_name`, `bank_account_holder`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EMP-001', 'EMP-001', 'Sujatno', 'Mandor Besi & Struktur', 'Mandor Besi & Struktur', '081311223344', 'Kp. Babakan RT 02/04, Tangerang', NULL, NULL, 'daily', 220000.00, 30000.00, 0.00, 'transfer', 'Bank BRI', '0123-01-098765-50-1', '0123-01-098765-50-1', 'Sujatno', 'Sujatno', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(2, 'EMP-002', 'EMP-002', 'Agus Priyanto', 'Tukang Batu & Cor', 'Tukang Batu & Cor', '081399881122', 'Dukuh Krajan, Klaten, Jawa Tengah', NULL, NULL, 'daily', 170000.00, 22000.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(3, 'EMP-003', 'EMP-003', 'Wahyudi Santoso', 'Tukang Las & Fabrikasi', 'Tukang Las & Fabrikasi', '081233445566', 'Cikarang Barat, Bekasi', NULL, NULL, 'daily', 190000.00, 25000.00, 0.00, 'transfer', 'Bank BCA', '880-019-8271', '880-019-8271', 'Wahyudi Santoso', 'Wahyudi Santoso', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_advances`
--

CREATE TABLE `employee_advances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advance_number` varchar(255) NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `remaining_amount` decimal(15,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','paid','repaid') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `disbursed_bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_advances`
--

INSERT INTO `employee_advances` (`id`, `advance_number`, `employee_id`, `date`, `amount`, `remaining_amount`, `reason`, `status`, `approved_by`, `disbursed_bank_account_id`, `created_at`, `updated_at`) VALUES
(1, 'ADV-TEST-6acef170ed192', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(2, 'ADV-TEST-6acef185f3428', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:05:41', '2026-10-14 03:05:42'),
(3, 'ADV-TEST-6acef1a95db1e', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(4, 'ADV-TEST-6acef26978be8', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(5, 'ADV-TEST-6acef2cf6ae2a', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(6, 'ADV-TEST-6acef2d5e6a36', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(7, 'ADV-TEST-6acef2e855906', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(8, 'ADV-TEST-6acef3052fc9d', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(9, 'ADV-TEST-6acef81e15262', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(10, 'ADV-TEST-6acefef1576e9', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(11, 'ADV-TEST-6acf00acb1e91', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(12, 'ADV-TEST-6acf00b68a3fe', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(13, 'ADV-TEST-6acf01737d948', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(14, 'ADV-TEST-6acf03bc09b85', 1, '2026-10-04', 300000.00, 0.00, NULL, 'repaid', NULL, NULL, '2026-10-14 04:23:24', '2026-10-14 04:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `asset_number` varchar(255) DEFAULT NULL,
  `ownership_status` enum('owned','rented') NOT NULL DEFAULT 'owned',
  `model_spec` varchar(255) DEFAULT NULL,
  `status` enum('available','in_use','maintenance','breakdown') NOT NULL DEFAULT 'available',
  `operator_name` varchar(255) DEFAULT NULL,
  `fuel_type` varchar(255) NOT NULL DEFAULT 'Solar / Biosolar',
  `current_location` varchar(255) DEFAULT NULL,
  `daily_rental_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `hourly_rental_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `project_id`, `code`, `name`, `asset_number`, `ownership_status`, `model_spec`, `status`, `operator_name`, `fuel_type`, `current_location`, `daily_rental_rate`, `hourly_rental_rate`, `purchase_date`, `purchase_price`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 'EQP-CRANE25', 'Mobile Crane Kato 25 Ton', 'AST-CRN-001', 'owned', 'Kato CR-250, Boom 28m', 'available', 'Bambang Supriadi', 'Biosolar B35', 'Pool Alat Pulogadung', 4500000.00, 600000.00, NULL, 0.00, NULL, '2026-10-14 02:22:08', '2026-10-14 03:43:57'),
(2, NULL, 'EQP-EXCA200', 'Excavator Komatsu PC200-8', 'AST-EXC-002', 'owned', 'Komatsu PC200-8 Standard Bucket 0.9 m3', 'in_use', 'Hendro Prasetyo', 'Biosolar B35', 'Site BSD Lakewood', 3200000.00, 420000.00, NULL, 0.00, NULL, '2026-10-14 02:22:08', '2026-10-14 03:43:57'),
(3, NULL, 'EQP-GENSET50', 'Genset Silent Perkins 50 kVA', 'AST-GEN-003', 'owned', 'Perkins 1103A-33TG1 Stamford Alternator', 'available', 'Rahmat Hidayat', 'Solar Industri Dexlite', 'Gudang Pusat Cakung', 950000.00, 125000.00, NULL, 0.00, NULL, '2026-10-14 03:43:57', '2026-10-14 03:43:57'),
(4, NULL, 'EQP-PUMP-SEWA', 'Concrete Pump Long Boom 36 Meter', 'SEWA-CP-2026', 'rented', 'IHI Concrete Pump Boom 36m (Vendor: PT Pompa Beton Mandiri)', 'in_use', 'Kru Vendor Pompa', 'Solar Industri', 'Site BSD Lakewood', 6500000.00, 850000.00, NULL, 0.00, NULL, '2026-10-14 03:43:57', '2026-10-14 03:43:57');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_usages`
--

CREATE TABLE `equipment_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `usage_date` date NOT NULL,
  `hours_used` decimal(5,2) NOT NULL DEFAULT 0.00,
  `fuel_liters` decimal(8,2) NOT NULL DEFAULT 0.00,
  `fuel_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `operator_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('direct_cost','overhead','operational') NOT NULL DEFAULT 'direct_cost',
  `coa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_periods`
--

CREATE TABLE `fiscal_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fiscal_periods`
--

INSERT INTO `fiscal_periods` (`id`, `name`, `start_date`, `end_date`, `is_closed`, `closed_at`, `closed_by`, `created_at`, `updated_at`) VALUES
(1, 'Tahun Fiskal 2026', '2026-01-01', '2026-12-31', 0, NULL, NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `receipt_date` date NOT NULL,
  `delivery_note_number` varchar(255) DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('received','verified') NOT NULL DEFAULT 'received',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipts`
--

INSERT INTO `goods_receipts` (`id`, `receipt_number`, `purchase_order_id`, `warehouse_id`, `receipt_date`, `delivery_note_number`, `received_by`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'GR-202602-0001', 1, 1, '2026-02-15', 'SJ-KS-99882', 3, 'verified', 'Pemeriksaan dimensi WF lolos uji QC', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'GR-TEST-6acef0974e8b9', 2, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(3, 'GR-TEST-6acef170b6b34', 3, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(4, 'GR-TEST-6acef185b5816', 4, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(5, 'GR-TEST-6acef1a90f2e7', 5, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(6, 'GR-TEST-6acef26924078', 6, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(7, 'GR-TEST-6acef2cf3aa69', 7, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(8, 'GR-TEST-6acef2d5b62be', 8, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(9, 'GR-TEST-6acef2e7e0a31', 9, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(10, 'GR-TEST-6acef304c6b58', 10, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(11, 'GR-TEST-6acef81de4d99', 11, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(12, 'GR-TEST-6acefef0f314b', 12, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 04:02:56', '2026-10-14 04:02:56'),
(13, 'GR-TEST-6acf00ac6ac50', 13, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(14, 'GR-TEST-6acf00b6332e0', 14, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(15, 'GR-TEST-6acf017334d51', 15, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(16, 'GR-TEST-6acf03bb821fa', 16, 1, '2026-10-14', 'SJ-VENDOR-999', 1, 'verified', NULL, '2026-10-14 04:23:23', '2026-10-14 04:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_items`
--

CREATE TABLE `goods_receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_item_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `ordered_qty` decimal(12,4) NOT NULL,
  `received_qty` decimal(12,4) NOT NULL,
  `rejected_qty` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipt_items`
--

INSERT INTO `goods_receipt_items` (`id`, `goods_receipt_id`, `purchase_order_item_id`, `material_id`, `ordered_qty`, `received_qty`, `rejected_qty`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 50.0000, 50.0000, 0.0000, 'btg', NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 2, 2, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(3, 3, 3, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(4, 4, 4, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(5, 5, 5, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(6, 6, 6, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(7, 7, 7, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(8, 8, 8, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(9, 9, 9, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(10, 10, 10, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(11, 11, 11, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(12, 12, 12, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(13, 13, 13, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(14, 14, 14, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(15, 15, 15, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(16, 16, 16, 1, 10.0000, 10.0000, 0.0000, 'pcs', NULL, '2026-10-14 04:23:23', '2026-10-14 04:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_number` varchar(255) NOT NULL,
  `transaction_date` date NOT NULL,
  `type` enum('receipt','issuance_to_project','project_return','transfer','supplier_return','adjustment','scrap') NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `destination_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `transaction_number`, `transaction_date`, `type`, `warehouse_id`, `destination_warehouse_id`, `project_id`, `reference_type`, `reference_id`, `user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'STK-IN-202610-0001', '2026-02-15', 'receipt', 1, NULL, 1, 'goods_receipt', 1, 3, 'Penerimaan Material dari PO PO-202602-0001', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'STK-OUT-202610-0001', '2026-03-01', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran baja profil ke proyek Karawang', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 'STK-IN-202610-0002', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 2, 1, 'Penerimaan Material dari PO PO-TEST-6acef0974b36b', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(4, 'STK-OUT-202610-0002', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(5, 'STK-IN-202610-0003', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 3, 1, 'Penerimaan Material dari PO PO-TEST-6acef170b36f9', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(6, 'STK-OUT-202610-0003', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(7, 'STK-IN-202610-0004', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 4, 1, 'Penerimaan Material dari PO PO-TEST-6acef185b2ee8', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(8, 'STK-OUT-202610-0004', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(9, 'STK-IN-202610-0005', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 5, 1, 'Penerimaan Material dari PO PO-TEST-6acef1a90b7ed', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(10, 'STK-OUT-202610-0005', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(11, 'STK-IN-202610-0006', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 6, 1, 'Penerimaan Material dari PO PO-TEST-6acef26920af6', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(12, 'STK-OUT-202610-0006', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(13, 'STK-IN-202610-0007', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 7, 1, 'Penerimaan Material dari PO PO-TEST-6acef2cf37fc3', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(14, 'STK-OUT-202610-0007', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(15, 'STK-IN-202610-0008', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 8, 1, 'Penerimaan Material dari PO PO-TEST-6acef2d5b3a6a', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(16, 'STK-OUT-202610-0008', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(17, 'STK-IN-202610-0009', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 9, 1, 'Penerimaan Material dari PO PO-TEST-6acef2e7de253', '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(18, 'STK-OUT-202610-0009', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(19, 'STK-IN-202610-0010', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 10, 1, 'Penerimaan Material dari PO PO-TEST-6acef304c3b1f', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(20, 'STK-OUT-202610-0010', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(21, 'STK-IN-202610-0011', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 11, 1, 'Penerimaan Material dari PO PO-TEST-6acef81de2b64', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(22, 'STK-OUT-202610-0011', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(23, 'STK-IN-202610-0012', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 12, 1, 'Penerimaan Material dari PO PO-TEST-6acefef0ef2ed', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(24, 'STK-OUT-202610-0012', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(25, 'STK-IN-202610-0013', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 13, 1, 'Penerimaan Material dari PO PO-TEST-6acf00ac6636e', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(26, 'STK-OUT-202610-0013', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(27, 'STK-IN-202610-0014', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 14, 1, 'Penerimaan Material dari PO PO-TEST-6acf00b62d924', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(28, 'STK-OUT-202610-0014', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(29, 'STK-IN-202610-0015', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 15, 1, 'Penerimaan Material dari PO PO-TEST-6acf0173323d0', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(30, 'STK-OUT-202610-0015', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(31, 'STK-IN-202610-0016', '2026-10-14', 'receipt', 1, NULL, 1, 'goods_receipt', 16, 1, 'Penerimaan Material dari PO PO-TEST-6acf03bb7cde0', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(32, 'STK-OUT-202610-0016', '2026-10-14', 'issuance_to_project', 1, NULL, 1, 'project_issuance', NULL, 1, 'Pengeluaran material ke proyek ID 1', '2026-10-14 04:23:23', '2026-10-14 04:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transaction_items`
--

CREATE TABLE `inventory_transaction_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `qty` decimal(12,4) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transaction_items`
--

INSERT INTO `inventory_transaction_items` (`id`, `inventory_transaction_id`, `material_id`, `qty`, `unit`, `unit_cost`, `total_cost`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50.0000, 'btg', 3850000.00, 192500000.00, 'Penerimaan PO item ID 1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 2, 1, 20.0000, 'btg', 3850000.00, 77000000.00, 'Pemakaian kolom baja rangka portal 1-5', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 3, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 2', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(4, 4, 1, 2.0000, 'btg', 3291666.67, 6583333.34, 'Test Pemakaian', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(5, 5, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 3', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(6, 6, 1, 2.0000, 'btg', 2881127.45, 5762254.90, 'Test Pemakaian', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(7, 7, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 4', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(8, 8, 1, 2.0000, 'btg', 2567821.21, 5135642.42, 'Test Pemakaian', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(9, 9, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 5', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(10, 10, 1, 2.0000, 'btg', 2321652.02, 4643304.04, 'Test Pemakaian', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(11, 11, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 6', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(12, 12, 1, 2.0000, 'btg', 2123646.37, 4247292.74, 'Test Pemakaian', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(13, 13, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 7', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(14, 14, 1, 2.0000, 'btg', 1961281.73, 3922563.46, 'Test Pemakaian', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(15, 15, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 8', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(16, 16, 1, 2.0000, 'btg', 1825977.87, 3651955.74, 'Test Pemakaian', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(17, 17, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 9', '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(18, 18, 1, 2.0000, 'btg', 1711669.43, 3423338.86, 'Test Pemakaian', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(19, 19, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 10', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(20, 20, 1, 2.0000, 'btg', 1613954.15, 3227908.30, 'Test Pemakaian', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(21, 21, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 11', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(22, 22, 1, 2.0000, 'btg', 1529563.68, 3059127.36, 'Test Pemakaian', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(23, 23, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 12', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(24, 24, 1, 2.0000, 'btg', 2733333.33, 5466666.66, 'Test Pemakaian', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(25, 25, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 13', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(26, 26, 1, 2.0000, 'btg', 2145614.03, 4291228.06, 'Test Pemakaian', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(27, 27, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 14', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(28, 28, 1, 2.0000, 'btg', 1787871.85, 3575743.70, 'Test Pemakaian', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(29, 29, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 15', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(30, 30, 1, 2.0000, 'btg', 1549377.06, 3098754.12, 'Test Pemakaian', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(31, 31, 1, 10.0000, 'pcs', 500000.00, 5000000.00, 'Penerimaan PO item ID 16', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(32, 32, 1, 2.0000, 'btg', 1380122.70, 2760245.40, 'Test Pemakaian', '2026-10-14 04:23:23', '2026-10-14 04:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_number` varchar(255) NOT NULL,
  `entry_date` date NOT NULL,
  `posting_date` date DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `total_debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','posted','reversed') NOT NULL DEFAULT 'draft',
  `is_reversal` tinyint(1) NOT NULL DEFAULT 0,
  `reversed_entry_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `entry_number`, `entry_date`, `posting_date`, `reference_type`, `reference_id`, `project_id`, `description`, `total_debit`, `total_credit`, `status`, `is_reversal`, `reversed_entry_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'JE-202610-0001', '2026-02-15', '2026-02-15', 'goods_receipt', 1, 1, 'Penerimaan Material dari PO PO-202602-0001 (Surat Jalan: SJ-KS-99882)', 192500000.00, 192500000.00, 'posted', 0, NULL, NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'JE-202610-0002', '2026-03-01', '2026-03-01', 'inventory_transaction', 2, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0001', 77000000.00, 77000000.00, 'posted', 0, NULL, NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 'JE-202610-0003', '2026-02-05', '2026-02-05', 'customer_invoice', 1, 1, 'Penerbitan Invoice INV-202602-0001 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 854700000.00, 854700000.00, 'posted', 0, NULL, NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 'JE-202610-0004', '2026-02-18', '2026-02-18', 'customer_payment', 1, 1, 'Penerimaan Pembayaran Pelanggan: PAY-AR-202602-0001', 854700000.00, 854700000.00, 'posted', 0, NULL, NULL, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, 'JE-202610-0005', '2026-10-14', '2026-10-14', 'goods_receipt', 2, 1, 'Penerimaan Material dari PO PO-TEST-6acef0974b36b (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(6, 'JE-202610-0006', '2026-10-14', '2026-10-14', 'inventory_transaction', 4, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0002', 6583333.34, 6583333.34, 'posted', 0, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(7, 'JE-202610-0007', '2026-10-14', '2026-10-14', 'goods_receipt', 3, 1, 'Penerimaan Material dari PO PO-TEST-6acef170b36f9 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(8, 'JE-202610-0008', '2026-10-14', '2026-10-14', 'inventory_transaction', 6, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0003', 5762254.90, 5762254.90, 'posted', 0, NULL, NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(9, 'JE-202610-0009', '2026-10-14', '2026-10-14', 'goods_receipt', 4, 1, 'Penerimaan Material dari PO PO-TEST-6acef185b2ee8 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(10, 'JE-202610-0010', '2026-10-14', '2026-10-14', 'inventory_transaction', 8, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0004', 5135642.42, 5135642.42, 'posted', 0, NULL, NULL, '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(11, 'JE-202610-0011', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(12, 'REV-202610-0001', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0011: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 11, NULL, '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(13, 'JE-202610-0012', '2026-10-14', '2026-10-14', 'goods_receipt', 5, 1, 'Penerimaan Material dari PO PO-TEST-6acef1a90b7ed (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(14, 'JE-202610-0013', '2026-10-14', '2026-10-14', 'inventory_transaction', 10, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0005', 4643304.04, 4643304.04, 'posted', 0, NULL, NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(15, 'JE-202610-0014', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(16, 'REV-202610-0002', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0014: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 15, NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(17, 'JE-202610-0015', '2026-10-14', '2026-10-14', 'goods_receipt', 6, 1, 'Penerimaan Material dari PO PO-TEST-6acef26920af6 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(18, 'JE-202610-0016', '2026-10-14', '2026-10-14', 'inventory_transaction', 12, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0006', 4247292.74, 4247292.74, 'posted', 0, NULL, NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(19, 'JE-202610-0017', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(20, 'REV-202610-0003', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0017: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 19, NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(21, 'JE-202610-0018', '2026-10-14', '2026-10-14', 'customer_invoice', 2, 1, 'Penerbitan Invoice INV-CLI-202610-0001 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(22, 'JE-202610-0019', '2026-10-14', '2026-10-14', 'customer_payment', 2, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0001', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(23, 'JE-202610-0020', '2026-10-14', '2026-10-14', 'goods_receipt', 7, 1, 'Penerimaan Material dari PO PO-TEST-6acef2cf37fc3 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(24, 'JE-202610-0021', '2026-10-14', '2026-10-14', 'inventory_transaction', 14, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0007', 3922563.46, 3922563.46, 'posted', 0, NULL, NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(25, 'JE-202610-0022', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(26, 'REV-202610-0004', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0022: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 25, NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(27, 'JE-202610-0023', '2026-10-14', '2026-10-14', 'customer_invoice', 3, 1, 'Penerbitan Invoice INV-CLI-202610-0002 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(28, 'JE-202610-0024', '2026-10-14', '2026-10-14', 'customer_payment', 3, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0002', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(29, 'JE-202610-0025', '2026-10-14', '2026-10-14', 'supplier_payment', 2, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0001', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(30, 'JE-202610-0026', '2026-10-14', '2026-10-14', 'goods_receipt', 8, 1, 'Penerimaan Material dari PO PO-TEST-6acef2d5b3a6a (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(31, 'JE-202610-0027', '2026-10-14', '2026-10-14', 'inventory_transaction', 16, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0008', 3651955.74, 3651955.74, 'posted', 0, NULL, NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(32, 'JE-202610-0028', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(33, 'REV-202610-0005', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0028: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 32, NULL, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(34, 'JE-202610-0029', '2026-10-14', '2026-10-14', 'customer_invoice', 4, 1, 'Penerbitan Invoice INV-CLI-202610-0003 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(35, 'JE-202610-0030', '2026-10-14', '2026-10-14', 'customer_payment', 4, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0003', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(36, 'JE-202610-0031', '2026-10-14', '2026-10-14', 'supplier_payment', 3, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0002', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(37, 'JE-202610-0032', '2026-10-14', '2026-10-14', 'goods_receipt', 9, 1, 'Penerimaan Material dari PO PO-TEST-6acef2e7de253 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(38, 'JE-202610-0033', '2026-10-14', '2026-10-14', 'inventory_transaction', 18, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0009', 3423338.86, 3423338.86, 'posted', 0, NULL, NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(39, 'JE-202610-0034', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(40, 'REV-202610-0006', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0034: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 39, NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(41, 'JE-202610-0035', '2026-10-14', '2026-10-14', 'customer_invoice', 5, 1, 'Penerbitan Invoice INV-CLI-202610-0004 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(42, 'JE-202610-0036', '2026-10-14', '2026-10-14', 'customer_payment', 5, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0004', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(43, 'JE-202610-0037', '2026-10-14', '2026-10-14', 'supplier_payment', 4, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0003', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(44, 'JE-202610-0038', '2026-10-14', '2026-10-14', 'goods_receipt', 10, 1, 'Penerimaan Material dari PO PO-TEST-6acef304c3b1f (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(45, 'JE-202610-0039', '2026-10-14', '2026-10-14', 'inventory_transaction', 20, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0010', 3227908.30, 3227908.30, 'posted', 0, NULL, NULL, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(46, 'JE-202610-0040', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(47, 'REV-202610-0007', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0040: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 46, NULL, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(48, 'JE-202610-0041', '2026-10-14', '2026-10-14', 'customer_invoice', 6, 1, 'Penerbitan Invoice INV-CLI-202610-0005 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(49, 'JE-202610-0042', '2026-10-14', '2026-10-14', 'customer_payment', 6, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0005', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(50, 'JE-202610-0043', '2026-10-14', '2026-10-14', 'supplier_payment', 5, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0004', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(51, 'JE-202610-0044', '2026-10-14', '2026-10-14', 'goods_receipt', 11, 1, 'Penerimaan Material dari PO PO-TEST-6acef81de2b64 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(52, 'JE-202610-0045', '2026-10-14', '2026-10-14', 'inventory_transaction', 22, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0011', 3059127.36, 3059127.36, 'posted', 0, NULL, NULL, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(53, 'JE-202610-0046', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(54, 'REV-202610-0008', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0046: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 53, NULL, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(55, 'JE-202610-0047', '2026-10-14', '2026-10-14', 'customer_invoice', 7, 1, 'Penerbitan Invoice INV-CLI-202610-0006 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(56, 'JE-202610-0048', '2026-10-14', '2026-10-14', 'customer_payment', 7, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0006', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(57, 'JE-202610-0049', '2026-10-14', '2026-10-14', 'supplier_payment', 6, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0005', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(58, 'JE-202610-0050', '2026-10-14', '2026-10-14', 'goods_receipt', 12, 1, 'Penerimaan Material dari PO PO-TEST-6acefef0ef2ed (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(59, 'JE-202610-0051', '2026-10-14', '2026-10-14', 'inventory_transaction', 24, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0012', 5466666.66, 5466666.66, 'posted', 0, NULL, NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(60, 'JE-202610-0052', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(61, 'REV-202610-0009', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0052: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 60, NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(62, 'JE-202610-0053', '2026-10-14', '2026-10-14', 'customer_invoice', 8, 1, 'Penerbitan Invoice INV-CLI-202610-0007 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(63, 'JE-202610-0054', '2026-10-14', '2026-10-14', 'customer_payment', 8, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0007', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(64, 'JE-202610-0055', '2026-10-14', '2026-10-14', 'supplier_payment', 7, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0006', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(65, 'JE-202610-0056', '2026-10-14', '2026-10-14', 'goods_receipt', 13, 1, 'Penerimaan Material dari PO PO-TEST-6acf00ac6636e (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(66, 'JE-202610-0057', '2026-10-14', '2026-10-14', 'inventory_transaction', 26, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0013', 4291228.06, 4291228.06, 'posted', 0, NULL, NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(67, 'JE-202610-0058', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(68, 'REV-202610-0010', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0058: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 67, NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(69, 'JE-202610-0059', '2026-10-14', '2026-10-14', 'customer_invoice', 9, 1, 'Penerbitan Invoice INV-CLI-202610-0008 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(70, 'JE-202610-0060', '2026-10-14', '2026-10-14', 'customer_payment', 9, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0008', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(71, 'JE-202610-0061', '2026-10-14', '2026-10-14', 'supplier_payment', 8, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0007', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(72, 'JE-202610-0062', '2026-10-14', '2026-10-14', 'goods_receipt', 14, 1, 'Penerimaan Material dari PO PO-TEST-6acf00b62d924 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(73, 'JE-202610-0063', '2026-10-14', '2026-10-14', 'inventory_transaction', 28, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0014', 3575743.70, 3575743.70, 'posted', 0, NULL, NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(74, 'JE-202610-0064', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(75, 'REV-202610-0011', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0064: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 74, NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(76, 'JE-202610-0065', '2026-10-14', '2026-10-14', 'customer_invoice', 10, 1, 'Penerbitan Invoice INV-CLI-202610-0009 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(77, 'JE-202610-0066', '2026-10-14', '2026-10-14', 'customer_payment', 10, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0009', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(78, 'JE-202610-0067', '2026-10-14', '2026-10-14', 'supplier_payment', 9, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0008', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(79, 'JE-202610-0068', '2026-10-14', '2026-10-14', 'goods_receipt', 15, 1, 'Penerimaan Material dari PO PO-TEST-6acf0173323d0 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(80, 'JE-202610-0069', '2026-10-14', '2026-10-14', 'inventory_transaction', 30, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0015', 3098754.12, 3098754.12, 'posted', 0, NULL, NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(81, 'JE-202610-0070', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(82, 'REV-202610-0012', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0070: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 81, NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(83, 'JE-202610-0071', '2026-10-14', '2026-10-14', 'customer_invoice', 11, 1, 'Penerbitan Invoice INV-CLI-202610-0010 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(84, 'JE-202610-0072', '2026-10-14', '2026-10-14', 'customer_payment', 11, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0010', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(85, 'JE-202610-0073', '2026-10-14', '2026-10-14', 'supplier_payment', 10, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0009', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(86, 'JE-202610-0074', '2026-10-14', '2026-10-14', 'goods_receipt', 16, 1, 'Penerimaan Material dari PO PO-TEST-6acf03bb7cde0 (Surat Jalan: SJ-VENDOR-999)', 5000000.00, 5000000.00, 'posted', 0, NULL, NULL, '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(87, 'JE-202610-0075', '2026-10-14', '2026-10-14', 'inventory_transaction', 32, 1, 'Pengeluaran Material ke Proyek: STK-OUT-202610-0016', 2760245.40, 2760245.40, 'posted', 0, NULL, NULL, '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(88, 'JE-202610-0076', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Test Manual Journal Entry', 1500000.00, 1500000.00, 'reversed', 0, NULL, NULL, '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(89, 'REV-202610-0013', '2026-10-14', '2026-10-14', 'manual_entry', NULL, NULL, 'Pembalik untuk JE-202610-0076: Koreksi pengujian', 1500000.00, 1500000.00, 'posted', 1, 88, NULL, '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(90, 'JE-202610-0077', '2026-10-14', '2026-10-14', 'customer_invoice', 12, 1, 'Penerbitan Invoice INV-CLI-202610-0011 ke Pelanggan PT Bumi Serpong Damai Tbk (Sinarmas Land)', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(91, 'JE-202610-0078', '2026-10-14', '2026-10-14', 'customer_payment', 12, 1, 'Penerimaan Pembayaran Pelanggan: KWT-CLI-202610-0011', 10545000.00, 10545000.00, 'posted', 0, NULL, 1, '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(92, 'JE-202610-0079', '2026-10-14', '2026-10-14', 'supplier_payment', 11, 1, 'Pembayaran Hutang Supplier: KWT-SUP-202610-0010', 2220000.00, 2220000.00, 'posted', 0, NULL, 1, '2026-10-14 04:23:25', '2026-10-14 04:23:25');

-- --------------------------------------------------------

--
-- Table structure for table `journal_lines`
--

CREATE TABLE `journal_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) UNSIGNED NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_lines`
--

INSERT INTO `journal_lines` (`id`, `journal_entry_id`, `chart_of_account_id`, `debit`, `credit`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 192500000.00, 0.00, 'Penerimaan Material GR-202602-0001', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 1, 11, 0.00, 192500000.00, 'Hutang Supplier atas GR-202602-0001', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 2, 22, 77000000.00, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 2, 6, 0.00, 77000000.00, 'Pengeluaran stok material dari Gudang', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, 3, 4, 854700000.00, 0.00, 'Tagihan INV-202602-0001 (Uang Muka Kerja 20% Kontrak Karawang)', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(6, 3, 19, 0.00, 854700000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(7, 4, 2, 854700000.00, 0.00, 'Penerimaan Pembayaran ke PT Konstruksi Maju Nusantara', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(8, 4, 4, 0.00, 854700000.00, 'Pelunasan Piutang Invoice INV-202602-0001', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(9, 5, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef0974e8b9', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(10, 5, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef0974e8b9', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(11, 6, 22, 6583333.34, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(12, 6, 6, 0.00, 6583333.34, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(13, 7, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef170b6b34', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(14, 7, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef170b6b34', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(15, 8, 22, 5762254.90, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(16, 8, 6, 0.00, 5762254.90, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(17, 9, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef185b5816', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(18, 9, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef185b5816', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(19, 10, 22, 5135642.42, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(20, 10, 6, 0.00, 5135642.42, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(21, 11, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(22, 11, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(23, 12, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(24, 12, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(25, 13, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef1a90f2e7', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(26, 13, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef1a90f2e7', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(27, 14, 22, 4643304.04, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(28, 14, 6, 0.00, 4643304.04, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(29, 15, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(30, 15, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(31, 16, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(32, 16, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(33, 17, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef26924078', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(34, 17, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef26924078', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(35, 18, 22, 4247292.74, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(36, 18, 6, 0.00, 4247292.74, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(37, 19, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(38, 19, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(39, 20, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(40, 20, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(41, 21, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0001 (Test Termin 6acef269d4d61)', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(42, 21, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(43, 22, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(44, 22, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0001', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(45, 23, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef2cf3aa69', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(46, 23, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef2cf3aa69', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(47, 24, 22, 3922563.46, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(48, 24, 6, 0.00, 3922563.46, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(49, 25, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(50, 25, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(51, 26, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(52, 26, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(53, 27, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0002 (Test Termin 6acef2cf9f9f6)', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(54, 27, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(55, 28, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(56, 28, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0002', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(57, 29, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(58, 29, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(59, 30, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef2d5b62be', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(60, 30, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef2d5b62be', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(61, 31, 22, 3651955.74, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(62, 31, 6, 0.00, 3651955.74, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(63, 32, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(64, 32, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(65, 33, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(66, 33, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(67, 34, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0003 (Test Termin 6acef2d64bdbb)', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(68, 34, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(69, 35, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(70, 35, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0003', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(71, 36, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(72, 36, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(73, 37, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef2e7e0a31', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(74, 37, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef2e7e0a31', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(75, 38, 22, 3423338.86, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(76, 38, 6, 0.00, 3423338.86, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(77, 39, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(78, 39, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(79, 40, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(80, 40, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(81, 41, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0004 (Test Termin 6acef2e89ea82)', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(82, 41, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(83, 42, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(84, 42, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0004', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(85, 43, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(86, 43, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(87, 44, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef304c6b58', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(88, 44, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef304c6b58', '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(89, 45, 22, 3227908.30, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(90, 45, 6, 0.00, 3227908.30, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(91, 46, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(92, 46, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(93, 47, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(94, 47, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(95, 48, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0005 (Test Termin 6acef30592a9d)', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(96, 48, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(97, 49, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(98, 49, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0005', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(99, 50, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(100, 50, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(101, 51, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acef81de4d99', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(102, 51, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acef81de4d99', '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(103, 52, 22, 3059127.36, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(104, 52, 6, 0.00, 3059127.36, 'Pengeluaran stok material dari Gudang', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(105, 53, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(106, 53, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(107, 54, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(108, 54, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(109, 55, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0006 (Test Termin 6acef81e438b7)', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(110, 55, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(111, 56, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(112, 56, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0006', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(113, 57, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(114, 57, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(115, 58, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acefef0f314b', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(116, 58, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acefef0f314b', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(117, 59, 22, 5466666.66, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(118, 59, 6, 0.00, 5466666.66, 'Pengeluaran stok material dari Gudang', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(119, 60, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(120, 60, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(121, 61, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(122, 61, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(123, 62, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0007 (Test Termin 6acefef1e35dd)', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(124, 62, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(125, 63, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(126, 63, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0007', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(127, 64, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(128, 64, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(129, 65, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acf00ac6ac50', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(130, 65, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acf00ac6ac50', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(131, 66, 22, 4291228.06, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(132, 66, 6, 0.00, 4291228.06, 'Pengeluaran stok material dari Gudang', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(133, 67, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(134, 67, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(135, 68, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(136, 68, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(137, 69, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0008 (Test Termin 6acf00ad2228a)', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(138, 69, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(139, 70, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(140, 70, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0008', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(141, 71, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(142, 71, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(143, 72, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acf00b6332e0', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(144, 72, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acf00b6332e0', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(145, 73, 22, 3575743.70, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(146, 73, 6, 0.00, 3575743.70, 'Pengeluaran stok material dari Gudang', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(147, 74, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(148, 74, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(149, 75, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(150, 75, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(151, 76, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0009 (Test Termin 6acf00b6da23d)', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(152, 76, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(153, 77, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(154, 77, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0009', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(155, 78, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(156, 78, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(157, 79, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acf017334d51', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(158, 79, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acf017334d51', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(159, 80, 22, 3098754.12, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(160, 80, 6, 0.00, 3098754.12, 'Pengeluaran stok material dari Gudang', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(161, 81, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(162, 81, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(163, 82, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(164, 82, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(165, 83, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0010 (Test Termin 6acf0173d04a3)', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(166, 83, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(167, 84, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(168, 84, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0010', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(169, 85, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(170, 85, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(171, 86, 6, 5000000.00, 0.00, 'Penerimaan Material GR-TEST-6acf03bb821fa', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(172, 86, 11, 0.00, 5000000.00, 'Hutang Supplier atas GR-TEST-6acf03bb821fa', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(173, 87, 22, 2760245.40, 0.00, 'Pemakaian material ke Proyek', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(174, 87, 6, 0.00, 2760245.40, 'Pengeluaran stok material dari Gudang', '2026-10-14 04:23:23', '2026-10-14 04:23:23'),
(175, 88, 1, 1500000.00, 0.00, 'Test Debit', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(176, 88, 10, 0.00, 1500000.00, 'Test Credit', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(177, 89, 1, 0.00, 1500000.00, 'Pembalik: Test Debit', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(178, 89, 10, 1500000.00, 0.00, 'Pembalik: Test Credit', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(179, 90, 4, 10545000.00, 0.00, 'Tagihan INV-CLI-202610-0011 (Test Termin 6acf03bc82408)', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(180, 90, 19, 0.00, 10545000.00, 'Pengakuan Pendapatan Konstruksi (Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2)', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(181, 91, 1, 10545000.00, 0.00, 'Penerimaan Pembayaran ke Kas Operasional', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(182, 91, 4, 0.00, 10545000.00, 'Pelunasan Piutang Invoice INV-CLI-202610-0011', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(183, 92, 11, 2220000.00, 0.00, 'Pelunasan Hutang Supplier PT Krakatau Steel Baja Konstruksi', '2026-10-14 04:23:25', '2026-10-14 04:23:25'),
(184, 92, 1, 0.00, 2220000.00, 'Pengeluaran dari Kas Operasional', '2026-10-14 04:23:25', '2026-10-14 04:23:25');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `material_category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `spec` varchar(255) DEFAULT NULL,
  `last_purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `ref_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_stock` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `code`, `name`, `material_category_id`, `unit_id`, `spec`, `last_purchase_price`, `ref_price`, `min_stock`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MAT-WF200', 'Baja Profil WF 200x100x5.5x8 (12m)', 1, 4, 'Panjang 12 Meter, Berat ~256 kg/btg (Gunung Garuda / Krakatau Steel)', 500000.00, 4100000.00, 10.0000, 1, '2026-10-14 02:22:08', '2026-10-14 04:02:57', NULL),
(2, 'MAT-D16', 'Besi Beton Ulir D16 (12m)', 1, 4, 'Standar SNI BjTS 420B, Panjang 12 Meter', 188000.00, 205000.00, 50.0000, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(3, 'MAT-SEMEN50', 'Semen Portland Gresik 50 kg', 2, 6, 'Tipe I SNI 50kg', 72000.00, 78000.00, 100.0000, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(4, 'MAT-PASIR-COR', 'Pasir Cor Kasar Bangka', 3, 3, 'Kadar lumpur < 5%, butiran tajam', 320000.00, 350000.00, 20.0000, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(5, 'MAT-READYK300', 'Beton Ready Mix K-300 B-0 Non Fly-Ash', 2, 3, 'Slump 12 ± 2 cm, Truk Mixer Standar 7 m3', 920000.00, 990000.00, 0.0000, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(6, 'MAT-CAT-WEATH', 'Cat Eksterior Dulux Weathershield 20 Liter', 4, 9, 'Pail 20 Liter, Warna Brilliant White', 1850000.00, 2050000.00, 5.0000, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `material_categories`
--

CREATE TABLE `material_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_categories`
--

INSERT INTO `material_categories` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'CAT-STEEL', 'Baja Konstruksi & Besi Beton', 'Baja WF, H-Beam, Besi Beton Ulir, Plat', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'CAT-CEMENT', 'Semen, Mortar & ReadyMix', 'Semen Portland, Mortar Perekat, Readymix K-300', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 'CAT-AGGREGATE', 'Pasir, Batu & Agregat', 'Pasir Pasang, Pasir Cor, Split 1-2, Batu Kali', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 'CAT-FINISH', 'Finishing & Arsitektur', 'Granit, Keramik, Cat Weathercoat, Plafon Gypsum', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, 'CAT-MEP', 'Mekanikal, Elektrikal & Plumbing', 'Pipa PVC, Kabel NYM, Panel Listrik, Sanitary', '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `required_date` date NOT NULL,
  `status` enum('draft','pending_approval','approved','rejected','completed') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `request_number`, `project_id`, `requested_by`, `required_date`, `status`, `notes`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 'MR-202610-0001', 1, 1, '2026-10-17', 'pending_approval', NULL, NULL, NULL, '2026-10-14 03:32:36', '2026-10-14 03:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `material_request_items`
--

CREATE TABLE `material_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_request_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `requested_qty` decimal(12,4) NOT NULL,
  `approved_qty` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `ordered_qty` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_request_items`
--

INSERT INTO `material_request_items` (`id`, `material_request_id`, `material_id`, `requested_qty`, `approved_qty`, `ordered_qty`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1.0000, 1.0000, 0.0000, 'unit', NULL, '2026-10-14 03:32:36', '2026-10-14 03:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `material_stocks`
--

CREATE TABLE `material_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `current_stock` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `moving_avg_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_stocks`
--

INSERT INTO `material_stocks` (`id`, `warehouse_id`, `material_id`, `current_stock`, `moving_avg_cost`, `total_value`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 60.0000, 1380122.70, 82807362.00, '2026-10-14 02:22:08', '2026-10-14 04:23:23'),
(2, 1, 2, 100.0000, 188000.00, 18800000.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 1, 3, 200.0000, 72000.00, 14400000.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 1, 4, 40.0000, 320000.00, 12800000.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, 1, 5, 0.0000, 920000.00, 0.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(6, 1, 6, 10.0000, 1850000.00, 18500000.00, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_14_000001_create_roles_and_permissions_tables', 1),
(5, '2026_10_14_000002_create_master_data_tables', 1),
(6, '2026_10_14_000003_create_accounting_core_tables', 1),
(7, '2026_10_14_000004_create_projects_and_wbs_tables', 1),
(8, '2026_10_14_000005_create_budgets_and_rab_tables', 1),
(9, '2026_10_14_000006_create_procurement_and_inventory_tables', 1),
(10, '2026_10_14_000007_create_labor_and_payroll_tables', 1),
(11, '2026_10_14_000008_create_subcontractors_tables', 1),
(12, '2026_10_14_000009_create_finance_and_payments_tables', 1),
(13, '2026_10_14_000010_create_approvals_and_notifications_tables', 1),
(14, '2026_10_14_104117_align_and_enhance_erp_tables', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_period_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `work_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `overtime_hours` decimal(5,2) NOT NULL DEFAULT 0.00,
  `regular_earnings` decimal(15,2) NOT NULL DEFAULT 0.00,
  `overtime_earnings` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bonuses` decimal(15,2) NOT NULL DEFAULT 0.00,
  `gross_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(15,2) NOT NULL DEFAULT 0.00,
  `advance_deductions` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `payroll_period_id`, `employee_id`, `project_id`, `work_days`, `overtime_hours`, `regular_earnings`, `overtime_earnings`, `bonuses`, `gross_salary`, `deductions`, `advance_deductions`, `net_salary`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(2, 1, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(3, 1, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:05:21', '2026-10-14 03:05:21'),
(4, 2, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(5, 2, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(6, 2, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(7, 3, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(8, 3, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(9, 3, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(10, 4, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(11, 4, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(12, 4, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(13, 5, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(14, 5, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(15, 5, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(16, 6, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(17, 6, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(18, 6, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(19, 7, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(20, 7, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(21, 7, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(22, 8, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(23, 8, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(24, 8, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(25, 9, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(26, 9, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(27, 9, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(28, 10, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(29, 10, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(30, 10, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(31, 11, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(32, 11, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(33, 11, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(34, 12, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(35, 12, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(36, 12, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(37, 13, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(38, 13, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(39, 13, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(40, 14, 1, 1, 5.00, 10.00, 1100000.00, 450000.00, 0.00, 1550000.00, 0.00, 300000.00, 1250000.00, 'pending', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(41, 14, 2, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:23:24', '2026-10-14 04:23:24'),
(42, 14, 3, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', '2026-10-14 04:23:24', '2026-10-14 04:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_payments`
--

CREATE TABLE `payroll_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_periods`
--

CREATE TABLE `payroll_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('draft','calculated','approved','paid') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_periods`
--

INSERT INTO `payroll_periods` (`id`, `period_code`, `title`, `start_date`, `end_date`, `status`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 'PAY-TEST-6acef170ee8b3', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:21'),
(2, 'PAY-TEST-6acef186009f2', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:05:42', '2026-10-14 03:05:42'),
(3, 'PAY-TEST-6acef1a95f0ca', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(4, 'PAY-TEST-6acef2697c1ef', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(5, 'PAY-TEST-6acef2cf6becb', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(6, 'PAY-TEST-6acef2d5e7ca2', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(7, 'PAY-TEST-6acef2e857654', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(8, 'PAY-TEST-6acef30532ad1', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(9, 'PAY-TEST-6acef81e163ba', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(10, 'PAY-TEST-6acefef15a482', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 04:02:57', '2026-10-14 04:02:57'),
(11, 'PAY-TEST-6acf00acb36be', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(12, 'PAY-TEST-6acf00b68b8ac', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(13, 'PAY-TEST-6acf01737f5f9', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(14, 'PAY-TEST-6acf03bc0b4e6', 'Test Period', '2026-10-07', '2026-10-14', 'calculated', NULL, '2026-10-14 04:23:24', '2026-10-14 04:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `group`, `created_at`, `updated_at`) VALUES
(1, 'projects.view', 'Lihat Proyek', 'projects', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(2, 'projects.create', 'Buat Proyek Baru', 'projects', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(3, 'projects.update', 'Edit Proyek', 'projects', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(4, 'projects.approve', 'Persetujuan Proyek', 'projects', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(5, 'budgets.view', 'Lihat RAB', 'budgets', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(6, 'budgets.create', 'Buat RAB', 'budgets', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(7, 'budgets.approve', 'Persetujuan RAB', 'budgets', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(8, 'purchases.create', 'Buat Permintaan & PO', 'procurement', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(9, 'purchases.approve', 'Persetujuan PO', 'procurement', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(10, 'inventory.manage', 'Kelola Stok & Gudang', 'inventory', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(11, 'payroll.manage', 'Kelola Absensi & Gaji', 'payroll', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(12, 'finance.manage', 'Kelola Kas & Bank', 'finance', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(13, 'accounting.manage', 'Kelola Jurnal & COA', 'accounting', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(14, 'reports.view', 'Lihat Semua Laporan', 'reports', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(15, 'users.manage', 'Kelola Pengguna & Hak Akses', 'settings', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(16, 'settings.manage', 'Kelola Pengaturan Perusahaan', 'settings', '2026-10-14 02:22:06', '2026-10-14 02:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(22, 2, 1),
(21, 2, 4),
(19, 2, 5),
(18, 2, 7),
(23, 2, 9),
(20, 2, 12),
(17, 2, 13),
(24, 2, 14),
(29, 3, 1),
(27, 3, 2),
(28, 3, 3),
(26, 3, 5),
(25, 3, 6),
(31, 3, 8),
(30, 3, 9),
(32, 3, 14),
(36, 5, 1),
(34, 5, 5),
(35, 5, 12),
(33, 5, 13),
(37, 5, 14);

-- --------------------------------------------------------

--
-- Table structure for table `petty_cash_vouchers`
--

CREATE TABLE `petty_cash_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `voucher_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  `status` enum('pending','approved','paid','settled') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('residential','commercial','steel_construction','renovation','civil_works','others') NOT NULL DEFAULT 'residential',
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `location` text DEFAULT NULL,
  `coordinates` varchar(255) DEFAULT NULL,
  `project_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tender_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `deadline_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `contract_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `approved_budget_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `target_margin_percent` decimal(5,2) NOT NULL DEFAULT 15.00,
  `contract_method` enum('lump_sum','unit_price','cost_plus') NOT NULL DEFAULT 'lump_sum',
  `payment_system` varchar(255) NOT NULL DEFAULT 'termin_progres',
  `retention_percent` decimal(5,2) NOT NULL DEFAULT 5.00,
  `retention_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `down_payment_percent` decimal(5,2) NOT NULL DEFAULT 20.00,
  `down_payment_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','estimation','tender','pending_approval','contract','preparation','in_progress','on_hold','completed','cancelled') NOT NULL DEFAULT 'draft',
  `physical_progress_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_code`, `name`, `type`, `customer_id`, `location`, `coordinates`, `project_manager_id`, `site_manager_id`, `tender_date`, `start_date`, `deadline_date`, `actual_end_date`, `contract_amount`, `approved_budget_amount`, `target_margin_percent`, `contract_method`, `payment_system`, `retention_percent`, `retention_amount`, `down_payment_percent`, `down_payment_amount`, `status`, `physical_progress_percent`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PRJ-2026-001', 'Pembangunan Gudang Logistik & Struktur Baja Karawang 2.400 m2', 'steel_construction', 1, 'Kawasan Industri KIIC Lot C-4, Karawang Barat', '-6.3421, 107.2891', 3, NULL, '2026-01-15', '2026-02-01', '2026-11-30', NULL, 3850000000.00, 3200000000.00, 16.88, 'lump_sum', 'termin_progres', 5.00, 192500000.00, 20.00, 770000000.00, 'in_progress', 42.50, 'Gudang rangka portal baja WF 200/250, atap Zincalume 0.45mm, lantai beton K-300 floor hardener.', '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(2, 'PRJ-2026-002', 'Pembangunan Rumah Mewah Modern Tropis 2 Lantai BSD', 'residential', 2, 'Cluster Lakewood Blok AB-08, BSD City', NULL, 3, NULL, '2026-03-01', '2026-03-15', '2026-12-15', NULL, 2450000000.00, 2050000000.00, 16.32, 'lump_sum', 'termin_progres', 5.00, 122500000.00, 20.00, 490000000.00, 'in_progress', 28.00, 'Rumah tinggal mewah 2 lantai, struktur beton bertulang, marmer lokal & lantai parket jati.', '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_contracts`
--

CREATE TABLE `project_contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `contract_number` varchar(255) NOT NULL,
  `contract_date` date NOT NULL,
  `contract_amount` decimal(15,2) NOT NULL,
  `retention_percent` decimal(5,2) NOT NULL DEFAULT 5.00,
  `down_payment_percent` decimal(5,2) NOT NULL DEFAULT 20.00,
  `file_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_tasks`
--

CREATE TABLE `project_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `task_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `planned_volume` decimal(12,4) NOT NULL DEFAULT 1.0000,
  `actual_volume` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `unit` varchar(255) NOT NULL DEFAULT 'ls',
  `weight_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `progress_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assigned_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','in_progress','completed','delayed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_tasks`
--

INSERT INTO `project_tasks` (`id`, `project_id`, `parent_id`, `task_code`, `name`, `description`, `planned_volume`, `actual_volume`, `unit`, `weight_percent`, `start_date`, `end_date`, `progress_percent`, `assigned_user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'WBS-01', 'Pekerjaan Persiapan & Pondasi Tapak (Borepile / Pedestal)', NULL, 1.0000, 0.0000, 'ls', 25.00, '2026-02-01', '2026-04-15', 100.00, NULL, 'completed', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 1, NULL, 'WBS-02', 'Fabrikasi & Erection Kolom Baja WF & Rafter Kuda-Kuda', NULL, 1.0000, 0.0000, 'ls', 45.00, '2026-04-16', '2026-07-31', 50.00, NULL, 'in_progress', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 1, NULL, 'WBS-03', 'Pemasangan Gording C-Channel, Seng Spandek & Cladding Dinding', NULL, 1.0000, 0.0000, 'ls', 20.00, '2026-08-01', '2026-09-30', 0.00, NULL, 'pending', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 1, NULL, 'WBS-04', 'Pengecoran Lantai Gudang K-300 Tebal 15cm & Finishing', NULL, 1.0000, 0.0000, 'ls', 10.00, '2026-10-01', '2026-11-25', 0.00, NULL, 'pending', '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `project_task_updates`
--

CREATE TABLE `project_task_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_task_id` bigint(20) UNSIGNED NOT NULL,
  `update_date` date NOT NULL,
  `reported_by` bigint(20) UNSIGNED DEFAULT NULL,
  `incremental_progress` decimal(5,2) NOT NULL DEFAULT 0.00,
  `cumulative_progress` decimal(5,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `material_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `po_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_terms` varchar(255) NOT NULL DEFAULT 'Net 30',
  `status` enum('draft','pending_approval','approved','received_partial','received_full','cancelled') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `project_id`, `supplier_id`, `material_request_id`, `po_date`, `subtotal`, `discount_amount`, `tax_percent`, `tax_amount`, `shipping_fee`, `total_amount`, `payment_terms`, `status`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PO-202602-0001', 1, 1, NULL, '2026-02-10', 192500000.00, 0.00, 11.00, 21175000.00, 0.00, 213675000.00, 'Net 30', 'received_full', 1, '2026-02-11 03:00:00', 'Pengadaan 50 batang WF 200 untuk Proyek Karawang tahap 1', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'PO-TEST-6acef0974b36b', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(3, 'PO-TEST-6acef170b36f9', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(4, 'PO-TEST-6acef185b2ee8', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(5, 'PO-TEST-6acef1a90b7ed', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(6, 'PO-TEST-6acef26920af6', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(7, 'PO-TEST-6acef2cf37fc3', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(8, 'PO-TEST-6acef2d5b3a6a', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(9, 'PO-TEST-6acef2e7de253', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(10, 'PO-TEST-6acef304c3b1f', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(11, 'PO-TEST-6acef81de2b64', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(12, 'PO-TEST-6acefef0ef2ed', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 04:02:56', '2026-10-14 04:02:57'),
(13, 'PO-TEST-6acf00ac6636e', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(14, 'PO-TEST-6acf00b62d924', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(15, 'PO-TEST-6acf0173323d0', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(16, 'PO-TEST-6acf03bb7cde0', 1, 1, NULL, '2026-10-14', 5000000.00, 0.00, 11.00, 550000.00, 0.00, 5550000.00, 'Net 30', 'received_full', NULL, NULL, NULL, '2026-10-14 04:23:23', '2026-10-14 04:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `qty` decimal(12,4) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15,2) NOT NULL,
  `received_qty` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `material_id`, `qty`, `unit`, `unit_price`, `discount`, `total_price`, `received_qty`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50.0000, 'btg', 3850000.00, 0.00, 192500000.00, 100.0000, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 2, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:01:43', '2026-10-14 03:01:43'),
(3, 3, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:05:20', '2026-10-14 03:05:20'),
(4, 4, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:05:41', '2026-10-14 03:05:41'),
(5, 5, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:06:17', '2026-10-14 03:06:17'),
(6, 6, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:09:29', '2026-10-14 03:09:29'),
(7, 7, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(8, 8, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:11:17', '2026-10-14 03:11:17'),
(9, 9, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:11:35', '2026-10-14 03:11:35'),
(10, 10, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:12:04', '2026-10-14 03:12:04'),
(11, 11, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 03:33:49', '2026-10-14 03:33:49'),
(12, 12, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 04:02:56', '2026-10-14 04:02:57'),
(13, 13, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 04:10:20', '2026-10-14 04:10:20'),
(14, 14, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 04:10:30', '2026-10-14 04:10:30'),
(15, 15, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 04:13:39', '2026-10-14 04:13:39'),
(16, 16, 1, 10.0000, 'pcs', 500000.00, 0.00, 5000000.00, 10.0000, '2026-10-14 04:23:23', '2026-10-14 04:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'Super Administrator', 'Akses penuh ke seluruh sistem dan konfigurasi', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(2, 'director', 'Direktur / Pemilik', 'Eksekutif: approval proyek, approval RAB, laporan laba rugi & dashboard', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(3, 'project_manager', 'Project Manager', 'Pengelolaan proyek, WBS, approval progres, cost control', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(4, 'site_manager', 'Site Manager / Mandor', 'Operasional lapangan, absensi, request material, update progres', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(5, 'finance_accounting', 'Finance / Accounting', 'Kas, bank, invoice, pembayaran, posting jurnal & laporan keuangan', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(6, 'procurement', 'Procurement / Purchasing', 'Purchase order, negosiasi supplier, pembelian material', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(7, 'warehouse', 'Warehouse / Gudang', 'Penerimaan barang, stok gudang, pengeluaran material ke proyek', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(8, 'hr_payroll', 'HR / Payroll', 'Karyawan, absensi, kasbon, dan penggajian tenaga kerja', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(9, 'estimator', 'Estimator / RAB', 'Estimasi biaya, pembuatan dan revisi RAB', '2026-10-14 02:22:06', '2026-10-14 02:22:06'),
(10, 'auditor', 'Auditor / Viewer', 'Pemeriksaan audit trail, laporan keuangan, view-only', '2026-10-14 02:22:06', '2026-10-14 02:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcontractors`
--

CREATE TABLE `subcontractors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_account_holder` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcontractors`
--

INSERT INTO `subcontractors` (`id`, `code`, `name`, `company_name`, `specialty`, `contact_person`, `phone`, `email`, `address`, `npwp`, `bank_name`, `bank_account_no`, `bank_account_number`, `bank_account_name`, `bank_account_holder`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SUB-001', 'PT Erection Baja Perkasa', 'Erection Baja Perkasa', 'Erection & Fabrikasi Baja WF Berat', 'Bapak Suparno (Direktur Operasional)', '081299887711', 'admin@erectionbaja.co.id', NULL, NULL, 'Bank BCA', '604-019-3311', '604-019-3311', 'PT Erection Baja Perkasa', 'PT Erection Baja Perkasa', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcontractor_contracts`
--

CREATE TABLE `subcontractor_contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_number` varchar(255) NOT NULL,
  `subcontractor_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `scope_of_work` varchar(255) NOT NULL,
  `work_scope` text DEFAULT NULL,
  `contract_amount` decimal(15,2) NOT NULL,
  `retention_percent` decimal(5,2) NOT NULL DEFAULT 5.00,
  `down_payment_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('draft','active','completed','terminated') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcontractor_invoices`
--

CREATE TABLE `subcontractor_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `subcontractor_contract_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `progress_percent` decimal(5,2) NOT NULL,
  `invoice_amount` decimal(15,2) NOT NULL,
  `retention_deduction` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_invoice_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL,
  `status` enum('pending','approved','partially_paid','paid','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcontractor_payments`
--

CREATE TABLE `subcontractor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcontractor_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcontractor_progress`
--

CREATE TABLE `subcontractor_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `progress_number` varchar(255) DEFAULT NULL,
  `subcontractor_contract_id` bigint(20) UNSIGNED NOT NULL,
  `progress_date` date NOT NULL,
  `inspection_date` date DEFAULT NULL,
  `claimed_progress_percent` decimal(5,2) NOT NULL,
  `approved_progress_percent` decimal(5,2) NOT NULL,
  `progress_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `certified_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'certified',
  `bpa_number` varchar(255) NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `payment_terms_days` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `contact_person`, `address`, `phone`, `email`, `npwp`, `bank_name`, `bank_account_no`, `bank_account_name`, `payment_terms_days`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SUP-001', 'PT Krakatau Steel Baja Konstruksi', 'Bapak Danu (Sales Manager)', 'Jl. Industri No. 5, Cilegon, Banten', '+62 254 392 111', 'sales@krakatausteel.co.id', '01.000.123.4-081.000', 'Bank Mandiri', '137-00-1122334-4', 'PT Krakatau Steel Baja Konstruksi', 30, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL),
(2, 'SUP-002', 'PT Semen Indonesia Distributor Regional 2', 'Ibu Fitri', 'Jl. Pemuda No. 45, Jakarta Timur', '+62 21 475 2299', 'order@sidistributor.co.id', NULL, 'Bank BCA', '001-229-3344', 'PT Semen Indonesia Distributor', 14, 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_invoices`
--

CREATE TABLE `supplier_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `supplier_invoice_ref` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending_verification','verified','partially_paid','paid','cancelled') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_invoices`
--

INSERT INTO `supplier_invoices` (`id`, `invoice_number`, `supplier_invoice_ref`, `supplier_id`, `purchase_order_id`, `goods_receipt_id`, `invoice_date`, `due_date`, `subtotal`, `tax_amount`, `total_amount`, `paid_amount`, `remaining_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'INV-SUP-TEST-6acef1aa30390', 'REF-SUP-6acef1aa30392', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 0.00, 2220000.00, 'verified', '2026-10-14 03:06:18', '2026-10-14 03:06:18'),
(2, 'INV-SUP-TEST-6acef1dccb51c', 'REF-SUP-6acef1dccb51f', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 0.00, 2220000.00, 'verified', '2026-10-14 03:07:08', '2026-10-14 03:07:08'),
(3, 'INV-SUP-TEST-6acef2022991a', 'REF-SUP-6acef2022991c', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 0.00, 2220000.00, 'verified', '2026-10-14 03:07:46', '2026-10-14 03:07:46'),
(4, 'INV-SUP-TEST-6acef2101a675', 'REF-SUP-6acef2101a67b', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 0.00, 2220000.00, 'verified', '2026-10-14 03:08:00', '2026-10-14 03:08:00'),
(5, 'INV-SUP-TEST-6acef26a02c3b', 'REF-SUP-6acef26a02c3f', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 0.00, 2220000.00, 'verified', '2026-10-14 03:09:30', '2026-10-14 03:09:30'),
(6, 'INV-SUP-TEST-6acef2cfbc0b0', 'REF-SUP-6acef2cfbc0b3', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(7, 'INV-SUP-TEST-6acef2d68a815', 'REF-SUP-6acef2d68a819', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(8, 'INV-SUP-TEST-6acef2e8cf125', 'REF-SUP-6acef2e8cf129', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(9, 'INV-SUP-TEST-6acef305d7ab3', 'REF-SUP-6acef305d7ab8', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(10, 'INV-SUP-TEST-6acef81e6cae1', 'REF-SUP-6acef81e6cae5', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(11, 'INV-SUP-TEST-6acefef23e34a', 'REF-SUP-6acefef23e352', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(12, 'INV-SUP-TEST-6acf00ad5d5f8', 'REF-SUP-6acf00ad5d600', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(13, 'INV-SUP-TEST-6acf00b7150b8', 'REF-SUP-6acf00b7150bd', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(14, 'INV-SUP-TEST-6acf017411ab7', 'REF-SUP-6acf017411aba', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(15, 'INV-SUP-TEST-6acf03bcef348', 'REF-SUP-6acf03bcef352', 1, 1, NULL, '2026-10-14', '2026-11-13', 2000000.00, 220000.00, 2220000.00, 2220000.00, 0.00, 'paid', '2026-10-14 04:23:24', '2026-10-14 04:23:25');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_invoice_items`
--

CREATE TABLE `supplier_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `qty` decimal(12,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payments`
--

CREATE TABLE `supplier_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_number` varchar(255) NOT NULL,
  `supplier_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'transfer',
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_payments`
--

INSERT INTO `supplier_payments` (`id`, `payment_number`, `supplier_invoice_id`, `bank_account_id`, `payment_date`, `amount`, `payment_method`, `reference_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'KWT-SUP-202610-0001', 6, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 03:11:11', '2026-10-14 03:11:11'),
(3, 'KWT-SUP-202610-0002', 7, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 03:11:18', '2026-10-14 03:11:18'),
(4, 'KWT-SUP-202610-0003', 8, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 03:11:36', '2026-10-14 03:11:36'),
(5, 'KWT-SUP-202610-0004', 9, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 03:12:05', '2026-10-14 03:12:05'),
(6, 'KWT-SUP-202610-0005', 10, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 03:33:50', '2026-10-14 03:33:50'),
(7, 'KWT-SUP-202610-0006', 11, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 04:02:58', '2026-10-14 04:02:58'),
(8, 'KWT-SUP-202610-0007', 12, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 04:10:21', '2026-10-14 04:10:21'),
(9, 'KWT-SUP-202610-0008', 13, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 04:10:31', '2026-10-14 04:10:31'),
(10, 'KWT-SUP-202610-0009', 14, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 04:13:40', '2026-10-14 04:13:40'),
(11, 'KWT-SUP-202610-0010', 15, 1, '2026-10-14', 2220000.00, 'Transfer Bank', NULL, 'Pelunasan Vendor Test', 1, '2026-10-14 04:23:25', '2026-10-14 04:23:25');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `symbol`, `created_at`, `updated_at`) VALUES
(1, 'Meter', 'm', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'Meter Persegi', 'm2', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(3, 'Meter Kubik', 'm3', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(4, 'Batang', 'btg', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(5, 'Kilogram', 'kg', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(6, 'Zak / Sak', 'zak', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(7, 'Lump Sum', 'ls', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(8, 'Titik', 'ttk', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(9, 'Unit / Buah', 'unit', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(10, 'Trip / Rit', 'rit', '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(11, 'Jam Kerja', 'jam', '2026-10-14 02:22:08', '2026-10-14 02:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `phone`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator Utama', 'admin@konstruksimaju.co.id', NULL, '$2y$04$jzGkVFcTf2nrlcTMX73H3utwdGdnDGj2dvASzE1D43OK3hYKq1SP.', 1, '081234567890', 1, '2026-10-14 04:23:23', '6HxIsrMf90QxhLiKUFMgo4YDcxBMNt6OYHPO93gItAIRH1c8R53N8OLOFO5r', '2026-10-14 02:22:06', '2026-10-14 04:23:23', NULL),
(2, 'Ir. Hendra Gunawan (Direktur)', 'direktur@konstruksimaju.co.id', NULL, '$2y$12$Rx6Y2ul46mQsJGjyKCiegOnMguRt2IlnPBA53SG1Hv7KRC5sHGkYi', 2, '081234567891', 1, NULL, NULL, '2026-10-14 02:22:07', '2026-10-14 02:22:07', NULL),
(3, 'Budi Santoso, ST (Project Manager)', 'pm@konstruksimaju.co.id', NULL, '$2y$12$aXmAga/1mRtNYlBzzP81NOyceHfYho9S0imLgXDrZEuehMWaXEfga', 3, '081234567892', 1, NULL, NULL, '2026-10-14 02:22:07', '2026-10-14 02:22:07', NULL),
(4, 'Siti Rahmawati, SE (Finance & Akunting)', 'finance@konstruksimaju.co.id', NULL, '$2y$12$i5Xjn5xzGWAm8lGypNfOt.CWqI/vvPgKTDg82KViiiTQkYgaYby.C', 5, '081234567893', 1, NULL, NULL, '2026-10-14 02:22:07', '2026-10-14 02:22:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_project_assignments`
--

CREATE TABLE `user_project_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `role_in_project` varchar(255) NOT NULL DEFAULT 'member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_project_assignments`
--

INSERT INTO `user_project_assignments` (`id`, `user_id`, `project_id`, `role_in_project`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'project_manager', NULL, NULL),
(2, 1, 1, 'director', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variation_orders`
--

CREATE TABLE `variation_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vo_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `vo_date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `contract_amount_change` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budget_amount_change` decimal(15,2) NOT NULL DEFAULT 0.00,
  `schedule_days_change` int(11) NOT NULL DEFAULT 0,
  `customer_approval_ref` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending_approval','approved','rejected') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variation_order_items`
--

CREATE TABLE `variation_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `variation_order_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` enum('addition','deduction') NOT NULL DEFAULT 'addition',
  `volume` decimal(12,4) NOT NULL DEFAULT 1.0000,
  `unit` varchar(255) NOT NULL DEFAULT 'ls',
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `name`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WH-MAIN', 'Gudang Pusat Logistik Cakung', 'Kawasan Industri Pulogadung, Jakarta Timur', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08'),
(2, 'WH-PROJ-BSD', 'Gudang Lapangan Proyek BSD', 'Site Office Cluster Lakewood BSD, Tangerang Selatan', 1, '2026-10-14 02:22:08', '2026-10-14 02:22:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_mappings`
--
ALTER TABLE `account_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_mappings_mapping_key_unique` (`mapping_key`),
  ADD KEY `account_mappings_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_entity_type_index` (`entity_type`),
  ADD KEY `activity_logs_entity_id_index` (`entity_id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approvals_user_id_foreign` (`user_id`),
  ADD KEY `approvals_approvable_type_approvable_id_index` (`approvable_type`,`approvable_id`);

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendance_records_employee_id_date_unique` (`employee_id`,`date`),
  ADD KEY `attendance_records_project_id_foreign` (`project_id`),
  ADD KEY `attendance_records_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_accounts_code_unique` (`code`),
  ADD KEY `bank_accounts_coa_id_index` (`coa_id`);

--
-- Indexes for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_transfers_transfer_number_unique` (`transfer_number`),
  ADD KEY `bank_transfers_from_bank_account_id_foreign` (`from_bank_account_id`),
  ADD KEY `bank_transfers_to_bank_account_id_foreign` (`to_bank_account_id`),
  ADD KEY `bank_transfers_created_by_foreign` (`created_by`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `budgets_rab_number_unique` (`rab_number`),
  ADD KEY `budgets_project_id_foreign` (`project_id`),
  ADD KEY `budgets_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `budget_divisions`
--
ALTER TABLE `budget_divisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_divisions_budget_id_foreign` (`budget_id`);

--
-- Indexes for table `budget_items`
--
ALTER TABLE `budget_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_items_budget_division_id_foreign` (`budget_division_id`);

--
-- Indexes for table `budget_item_components`
--
ALTER TABLE `budget_item_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_item_components_budget_item_id_foreign` (`budget_item_id`),
  ADD KEY `budget_item_components_material_id_foreign` (`material_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cash_transactions_transaction_number_unique` (`transaction_number`),
  ADD KEY `cash_transactions_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `cash_transactions_category_id_foreign` (`category_id`),
  ADD KEY `cash_transactions_created_by_foreign` (`created_by`),
  ADD KEY `cash_transactions_project_id_index` (`project_id`),
  ADD KEY `cash_transactions_coa_id_index` (`coa_id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_code_unique` (`code`),
  ADD KEY `chart_of_accounts_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_code_unique` (`code`);

--
-- Indexes for table `customer_invoices`
--
ALTER TABLE `customer_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `customer_invoices_project_id_foreign` (`project_id`),
  ADD KEY `customer_invoices_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_payments_payment_number_unique` (`payment_number`),
  ADD KEY `customer_payments_customer_invoice_id_foreign` (`customer_invoice_id`),
  ADD KEY `customer_payments_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `customer_payments_created_by_foreign` (`created_by`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_nik_unique` (`nik`);

--
-- Indexes for table `employee_advances`
--
ALTER TABLE `employee_advances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_advances_advance_number_unique` (`advance_number`),
  ADD KEY `employee_advances_employee_id_foreign` (`employee_id`),
  ADD KEY `employee_advances_approved_by_foreign` (`approved_by`),
  ADD KEY `employee_advances_disbursed_bank_account_id_foreign` (`disbursed_bank_account_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `equipment_code_unique` (`code`),
  ADD KEY `equipment_project_id_foreign` (`project_id`);

--
-- Indexes for table `equipment_usages`
--
ALTER TABLE `equipment_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_usages_equipment_id_foreign` (`equipment_id`),
  ADD KEY `equipment_usages_project_id_foreign` (`project_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_categories_code_unique` (`code`),
  ADD KEY `expense_categories_coa_id_index` (`coa_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fiscal_periods_closed_by_foreign` (`closed_by`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_receipts_receipt_number_unique` (`receipt_number`),
  ADD KEY `goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_receipts_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `goods_receipts_received_by_foreign` (`received_by`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_items_goods_receipt_id_foreign` (`goods_receipt_id`),
  ADD KEY `goods_receipt_items_purchase_order_item_id_foreign` (`purchase_order_item_id`),
  ADD KEY `goods_receipt_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_transactions_transaction_number_unique` (`transaction_number`),
  ADD KEY `inventory_transactions_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `inventory_transactions_destination_warehouse_id_foreign` (`destination_warehouse_id`),
  ADD KEY `inventory_transactions_user_id_foreign` (`user_id`),
  ADD KEY `inventory_transactions_project_id_index` (`project_id`),
  ADD KEY `inventory_transactions_reference_type_index` (`reference_type`),
  ADD KEY `inventory_transactions_reference_id_index` (`reference_id`);

--
-- Indexes for table `inventory_transaction_items`
--
ALTER TABLE `inventory_transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_transaction_items_inventory_transaction_id_foreign` (`inventory_transaction_id`),
  ADD KEY `inventory_transaction_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_entry_number_unique` (`entry_number`),
  ADD KEY `journal_entries_reversed_entry_id_foreign` (`reversed_entry_id`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`),
  ADD KEY `journal_entries_reference_type_index` (`reference_type`),
  ADD KEY `journal_entries_reference_id_index` (`reference_id`),
  ADD KEY `journal_entries_project_id_index` (`project_id`);

--
-- Indexes for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_lines_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `journal_lines_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materials_code_unique` (`code`),
  ADD KEY `materials_material_category_id_foreign` (`material_category_id`),
  ADD KEY `materials_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `material_categories`
--
ALTER TABLE `material_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_categories_code_unique` (`code`);

--
-- Indexes for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_requests_request_number_unique` (`request_number`),
  ADD KEY `material_requests_project_id_foreign` (`project_id`),
  ADD KEY `material_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `material_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_request_items_material_request_id_foreign` (`material_request_id`),
  ADD KEY `material_request_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `material_stocks`
--
ALTER TABLE `material_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_stocks_warehouse_id_material_id_unique` (`warehouse_id`,`material_id`),
  ADD KEY `material_stocks_material_id_foreign` (`material_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payrolls_payroll_period_id_employee_id_unique` (`payroll_period_id`,`employee_id`),
  ADD KEY `payrolls_employee_id_foreign` (`employee_id`),
  ADD KEY `payrolls_project_id_foreign` (`project_id`);

--
-- Indexes for table `payroll_payments`
--
ALTER TABLE `payroll_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_payments_payroll_id_foreign` (`payroll_id`),
  ADD KEY `payroll_payments_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payroll_periods_period_code_unique` (`period_code`),
  ADD KEY `payroll_periods_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_role_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `petty_cash_vouchers`
--
ALTER TABLE `petty_cash_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `petty_cash_vouchers_voucher_number_unique` (`voucher_number`),
  ADD KEY `petty_cash_vouchers_requested_by_foreign` (`requested_by`),
  ADD KEY `petty_cash_vouchers_approved_by_foreign` (`approved_by`),
  ADD KEY `petty_cash_vouchers_project_id_index` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_code_unique` (`project_code`),
  ADD KEY `projects_customer_id_foreign` (`customer_id`),
  ADD KEY `projects_project_manager_id_foreign` (`project_manager_id`),
  ADD KEY `projects_site_manager_id_foreign` (`site_manager_id`);

--
-- Indexes for table `project_contracts`
--
ALTER TABLE `project_contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_contracts_contract_number_unique` (`contract_number`),
  ADD KEY `project_contracts_project_id_foreign` (`project_id`);

--
-- Indexes for table `project_tasks`
--
ALTER TABLE `project_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_tasks_project_id_foreign` (`project_id`),
  ADD KEY `project_tasks_parent_id_foreign` (`parent_id`),
  ADD KEY `project_tasks_assigned_user_id_foreign` (`assigned_user_id`);

--
-- Indexes for table `project_task_updates`
--
ALTER TABLE `project_task_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_task_updates_project_task_id_foreign` (`project_task_id`),
  ADD KEY `project_task_updates_reported_by_foreign` (`reported_by`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_project_id_foreign` (`project_id`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_material_request_id_foreign` (`material_request_id`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subcontractors`
--
ALTER TABLE `subcontractors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcontractors_code_unique` (`code`);

--
-- Indexes for table `subcontractor_contracts`
--
ALTER TABLE `subcontractor_contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcontractor_contracts_contract_number_unique` (`contract_number`),
  ADD KEY `subcontractor_contracts_subcontractor_id_foreign` (`subcontractor_id`),
  ADD KEY `subcontractor_contracts_project_id_foreign` (`project_id`);

--
-- Indexes for table `subcontractor_invoices`
--
ALTER TABLE `subcontractor_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcontractor_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `subcontractor_invoices_subcontractor_contract_id_foreign` (`subcontractor_contract_id`);

--
-- Indexes for table `subcontractor_payments`
--
ALTER TABLE `subcontractor_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcontractor_payments_subcontractor_invoice_id_foreign` (`subcontractor_invoice_id`),
  ADD KEY `subcontractor_payments_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `subcontractor_progress`
--
ALTER TABLE `subcontractor_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcontractor_progress_bpa_number_unique` (`bpa_number`),
  ADD KEY `subcontractor_progress_subcontractor_contract_id_foreign` (`subcontractor_contract_id`),
  ADD KEY `subcontractor_progress_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_code_unique` (`code`);

--
-- Indexes for table `supplier_invoices`
--
ALTER TABLE `supplier_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_invoices_supplier_id_supplier_invoice_ref_unique` (`supplier_id`,`supplier_invoice_ref`),
  ADD UNIQUE KEY `supplier_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `supplier_invoices_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `supplier_invoices_goods_receipt_id_foreign` (`goods_receipt_id`);

--
-- Indexes for table `supplier_invoice_items`
--
ALTER TABLE `supplier_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_invoice_items_supplier_invoice_id_foreign` (`supplier_invoice_id`),
  ADD KEY `supplier_invoice_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_payments_payment_number_unique` (`payment_number`),
  ADD KEY `supplier_payments_supplier_invoice_id_foreign` (`supplier_invoice_id`),
  ADD KEY `supplier_payments_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `supplier_payments_created_by_foreign` (`created_by`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_symbol_unique` (`symbol`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_project_assignments`
--
ALTER TABLE `user_project_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_project_assignments_user_id_project_id_unique` (`user_id`,`project_id`),
  ADD KEY `user_project_assignments_project_id_index` (`project_id`);

--
-- Indexes for table `variation_orders`
--
ALTER TABLE `variation_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variation_orders_vo_number_unique` (`vo_number`),
  ADD KEY `variation_orders_project_id_foreign` (`project_id`),
  ADD KEY `variation_orders_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `variation_order_items`
--
ALTER TABLE `variation_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_order_items_variation_order_id_foreign` (`variation_order_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_mappings`
--
ALTER TABLE `account_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `budget_divisions`
--
ALTER TABLE `budget_divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `budget_items`
--
ALTER TABLE `budget_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `budget_item_components`
--
ALTER TABLE `budget_item_components`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_invoices`
--
ALTER TABLE `customer_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_advances`
--
ALTER TABLE `employee_advances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `equipment_usages`
--
ALTER TABLE `equipment_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `inventory_transaction_items`
--
ALTER TABLE `inventory_transaction_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_categories`
--
ALTER TABLE `material_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `material_stocks`
--
ALTER TABLE `material_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `payroll_payments`
--
ALTER TABLE `payroll_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `petty_cash_vouchers`
--
ALTER TABLE `petty_cash_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_contracts`
--
ALTER TABLE `project_contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_tasks`
--
ALTER TABLE `project_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_task_updates`
--
ALTER TABLE `project_task_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subcontractors`
--
ALTER TABLE `subcontractors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcontractor_contracts`
--
ALTER TABLE `subcontractor_contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcontractor_invoices`
--
ALTER TABLE `subcontractor_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcontractor_payments`
--
ALTER TABLE `subcontractor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcontractor_progress`
--
ALTER TABLE `subcontractor_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier_invoices`
--
ALTER TABLE `supplier_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `supplier_invoice_items`
--
ALTER TABLE `supplier_invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_project_assignments`
--
ALTER TABLE `user_project_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `variation_orders`
--
ALTER TABLE `variation_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variation_order_items`
--
ALTER TABLE `variation_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_mappings`
--
ALTER TABLE `account_mappings`
  ADD CONSTRAINT `account_mappings_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_records_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendance_records_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  ADD CONSTRAINT `bank_transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_transfers_from_bank_account_id_foreign` FOREIGN KEY (`from_bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_transfers_to_bank_account_id_foreign` FOREIGN KEY (`to_bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budget_divisions`
--
ALTER TABLE `budget_divisions`
  ADD CONSTRAINT `budget_divisions_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budget_items`
--
ALTER TABLE `budget_items`
  ADD CONSTRAINT `budget_items_budget_division_id_foreign` FOREIGN KEY (`budget_division_id`) REFERENCES `budget_divisions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budget_item_components`
--
ALTER TABLE `budget_item_components`
  ADD CONSTRAINT `budget_item_components_budget_item_id_foreign` FOREIGN KEY (`budget_item_id`) REFERENCES `budget_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_item_components_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD CONSTRAINT `cash_transactions_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_invoices`
--
ALTER TABLE `customer_invoices`
  ADD CONSTRAINT `customer_invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD CONSTRAINT `customer_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_customer_invoice_id_foreign` FOREIGN KEY (`customer_invoice_id`) REFERENCES `customer_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_advances`
--
ALTER TABLE `employee_advances`
  ADD CONSTRAINT `employee_advances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_advances_disbursed_bank_account_id_foreign` FOREIGN KEY (`disbursed_bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_advances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `equipment_usages`
--
ALTER TABLE `equipment_usages`
  ADD CONSTRAINT `equipment_usages_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_usages_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD CONSTRAINT `fiscal_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipts_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_items_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_destination_warehouse_id_foreign` FOREIGN KEY (`destination_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_transaction_items`
--
ALTER TABLE `inventory_transaction_items`
  ADD CONSTRAINT `inventory_transaction_items_inventory_transaction_id_foreign` FOREIGN KEY (`inventory_transaction_id`) REFERENCES `inventory_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_transaction_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `journal_entries_reversed_entry_id_foreign` FOREIGN KEY (`reversed_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD CONSTRAINT `journal_lines_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journal_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_material_category_id_foreign` FOREIGN KEY (`material_category_id`) REFERENCES `material_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materials_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD CONSTRAINT `material_request_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_request_items_material_request_id_foreign` FOREIGN KEY (`material_request_id`) REFERENCES `material_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_stocks`
--
ALTER TABLE `material_stocks`
  ADD CONSTRAINT `material_stocks_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payrolls_payroll_period_id_foreign` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payrolls_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payroll_payments`
--
ALTER TABLE `payroll_payments`
  ADD CONSTRAINT `payroll_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_payments_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_periods`
--
ALTER TABLE `payroll_periods`
  ADD CONSTRAINT `payroll_periods_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `petty_cash_vouchers`
--
ALTER TABLE `petty_cash_vouchers`
  ADD CONSTRAINT `petty_cash_vouchers_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `petty_cash_vouchers_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_site_manager_id_foreign` FOREIGN KEY (`site_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_contracts`
--
ALTER TABLE `project_contracts`
  ADD CONSTRAINT `project_contracts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_tasks`
--
ALTER TABLE `project_tasks`
  ADD CONSTRAINT `project_tasks_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_tasks_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `project_tasks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `project_tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_task_updates`
--
ALTER TABLE `project_task_updates`
  ADD CONSTRAINT `project_task_updates_project_task_id_foreign` FOREIGN KEY (`project_task_id`) REFERENCES `project_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_task_updates_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_material_request_id_foreign` FOREIGN KEY (`material_request_id`) REFERENCES `material_requests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontractor_contracts`
--
ALTER TABLE `subcontractor_contracts`
  ADD CONSTRAINT `subcontractor_contracts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subcontractor_contracts_subcontractor_id_foreign` FOREIGN KEY (`subcontractor_id`) REFERENCES `subcontractors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontractor_invoices`
--
ALTER TABLE `subcontractor_invoices`
  ADD CONSTRAINT `subcontractor_invoices_subcontractor_contract_id_foreign` FOREIGN KEY (`subcontractor_contract_id`) REFERENCES `subcontractor_contracts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontractor_payments`
--
ALTER TABLE `subcontractor_payments`
  ADD CONSTRAINT `subcontractor_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subcontractor_payments_subcontractor_invoice_id_foreign` FOREIGN KEY (`subcontractor_invoice_id`) REFERENCES `subcontractor_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcontractor_progress`
--
ALTER TABLE `subcontractor_progress`
  ADD CONSTRAINT `subcontractor_progress_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subcontractor_progress_subcontractor_contract_id_foreign` FOREIGN KEY (`subcontractor_contract_id`) REFERENCES `subcontractor_contracts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_invoices`
--
ALTER TABLE `supplier_invoices`
  ADD CONSTRAINT `supplier_invoices_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supplier_invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_invoices_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_invoice_items`
--
ALTER TABLE `supplier_invoice_items`
  ADD CONSTRAINT `supplier_invoice_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_invoice_items_supplier_invoice_id_foreign` FOREIGN KEY (`supplier_invoice_id`) REFERENCES `supplier_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD CONSTRAINT `supplier_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payments_supplier_invoice_id_foreign` FOREIGN KEY (`supplier_invoice_id`) REFERENCES `supplier_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_project_assignments`
--
ALTER TABLE `user_project_assignments`
  ADD CONSTRAINT `user_project_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variation_orders`
--
ALTER TABLE `variation_orders`
  ADD CONSTRAINT `variation_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `variation_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variation_order_items`
--
ALTER TABLE `variation_order_items`
  ADD CONSTRAINT `variation_order_items_variation_order_id_foreign` FOREIGN KEY (`variation_order_id`) REFERENCES `variation_orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
