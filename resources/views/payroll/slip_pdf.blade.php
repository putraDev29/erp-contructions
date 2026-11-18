<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $payroll->employee?->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .company-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #ea580c;
            text-align: right;
            text-transform: uppercase;
        }
        table.meta {
            width: 100%;
            margin-bottom: 12px;
        }
        table.meta td {
            vertical-align: top;
            padding: 2px 0;
            font-size: 10px;
        }
        table.breakdown {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.breakdown th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            text-align: left;
            padding: 6px 8px;
            text-transform: uppercase;
        }
        table.breakdown td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-box {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px;
            margin-bottom: 15px;
        }
        .signature-table {
            width: 100%;
            margin-top: 15px;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9px;
        }
        .sign-space {
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div class="company-title">{{ $company->name ?? 'PT KONSTRUKSI MAJU NUSANTARA' }}</div>
                    <div style="color: #64748b; font-size: 9px;">Kontraktor Pembangunan & Konstruksi Baja</div>
                </td>
                <td style="width: 40%;">
                    <div class="doc-title">SLIP PEMBAYARAN UPAH</div>
                    <div style="text-align: right; font-size: 9px; color: #64748b;">
                        Periode: {{ $payroll->payrollPeriod?->period_code }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta">
        <tr>
            <td style="width: 50%;">
                <strong>Nama Pekerja:</strong> {{ $payroll->employee?->name }}<br>
                <strong>NIK:</strong> {{ $payroll->employee?->employee_number }}<br>
                <strong>Jabatan:</strong> {{ $payroll->employee?->role_title }}
            </td>
            <td style="width: 50%;">
                <strong>Keterangan Periode:</strong> {{ $payroll->payrollPeriod?->title }}<br>
                <strong>Penempatan Proyek:</strong> {{ $payroll->project?->name ?? 'Head Office / Operasional' }}<br>
                <strong>Rekening:</strong> {{ $payroll->employee?->bank_name ?? 'Tunai' }} {{ $payroll->employee?->bank_account_number }}
            </td>
        </tr>
    </table>

    <table class="breakdown">
        <thead>
            <tr>
                <th>Komponen Pendapatan</th>
                <th class="text-center">Kuantitas</th>
                <th class="text-right">Tarif Satuan</th>
                <th class="text-right">Total (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Upah Hari Kerja Pokok</td>
                <td class="text-center">{{ $payroll->work_days }} Hari</td>
                <td class="text-right">Rp {{ number_format($payroll->employee?->daily_rate, 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($payroll->regular_earnings, 0, ',', '.') }}</td>
            </tr>
            @if($payroll->overtime_hours > 0)
                <tr>
                    <td>Upah Lembur</td>
                    <td class="text-center">{{ $payroll->overtime_hours }} Jam</td>
                    <td class="text-right">-</td>
                    <td class="text-right font-bold">Rp {{ number_format($payroll->overtime_earnings, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($payroll->bonuses > 0)
                <tr>
                    <td>Bonus / Insentif Lapangan</td>
                    <td class="text-center">-</td>
                    <td class="text-right">-</td>
                    <td class="text-right font-bold">Rp {{ number_format($payroll->bonuses, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr style="background-color: #f8fafc;">
                <td colspan="3" style="font-weight: bold;">TOTAL PENDAPATAN KOTOR (BRUTO)</td>
                <td class="text-right font-bold">Rp {{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
            </tr>
            @if($payroll->advance_deductions > 0)
                <tr>
                    <td colspan="3" style="color: #b91c1c;">Potongan Kasbon / Pinjaman Karyawan</td>
                    <td class="text-right font-bold" style="color: #b91c1c;">- Rp {{ number_format($payroll->advance_deductions, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="total-box">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 11px; font-weight: bold; color: #0f172a;">
                    UPAH BERSIH DITERIMA (TAKE HOME PAY):
                </td>
                <td style="text-align: right; font-size: 13px; font-weight: bold; color: #ea580c;">
                    Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Finance / HRD,<br>
                <div class="sign-space"></div>
                <strong>( Bagian Penggajian )</strong>
            </td>
            <td>
                Penerima Upah,<br>
                <div class="sign-space"></div>
                <strong>( {{ $payroll->employee?->name }} )</strong>
            </td>
        </tr>
    </table>
</body>
</html>
