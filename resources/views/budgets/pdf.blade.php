<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RAB - {{ $budget->rab_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .company-sub {
            font-size: 9px;
            color: #64748b;
        }
        .doc-title {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #ea580c;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .meta-table td {
            padding: 3px 0;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .item-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px;
            border: 1px solid #0f172a;
        }
        .item-table td {
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
        }
        .division-row {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #0f172a;
        }
        .recap-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .recap-table td {
            padding: 4px 8px;
            font-size: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #0f172a;
            color: #ffffff;
        }
        .offered-row {
            font-weight: bold;
            background-color: #ea580c;
            color: #ffffff;
            font-size: 11px;
        }
        .sign-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .sign-table td {
            width: 33.33%;
            text-align: center;
            font-size: 10px;
        }
        .sign-space {
            height: 60px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="company-name">{{ $company->name }}</div>
                <div class="company-sub">{{ $company->address }}</div>
                <div class="company-sub">Telp: {{ $company->phone }} | Email: {{ $company->email }}</div>
                <div class="company-sub">NPWP: {{ $company->npwp }}</div>
            </td>
            <td style="width: 40%;" class="text-right">
                <div class="doc-title">RENCANA ANGGARAN BIAYA</div>
                <div style="font-size: 11px; font-weight: bold; color: #0f172a;">No. {{ $budget->rab_number }}</div>
                <div style="font-size: 9px; color: #64748b;">Versi Dokumen: {{ $budget->version }} | Tanggal: {{ date('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Meta Info -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">Nama Proyek</td>
            <td style="width: 35%;">: {{ $budget->project?->name }} ({{ $budget->project?->project_code }})</td>
            <td style="width: 15%; font-weight: bold;">Pemilik Proyek</td>
            <td style="width: 35%;">: {{ $budget->project?->customer?->name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Lokasi Proyek</td>
            <td>: {{ $budget->project?->location ?: '-' }}</td>
            <td style="font-weight: bold;">Metode Kontrak</td>
            <td>: {{ ucfirst(str_replace('_', ' ', $budget->project?->contract_method)) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Judul RAB</td>
            <td>: {{ $budget->title }}</td>
            <td style="font-weight: bold;">Status</td>
            <td>: {{ strtoupper($budget->status) }}</td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 7%;">No.</th>
                <th style="width: 43%;">Uraian Pekerjaan & Spesifikasi</th>
                <th style="width: 8%;" class="text-center">Vol</th>
                <th style="width: 7%;" class="text-center">Sat</th>
                <th style="width: 17%;" class="text-right">Harga Satuan (Rp)</th>
                <th style="width: 18%;" class="text-right">Total Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budget->divisions as $div)
                <tr class="division-row">
                    <td class="text-center">{{ $div->division_code }}</td>
                    <td colspan="4">{{ strtoupper($div->name) }}</td>
                    <td class="text-right">Rp {{ number_format($div->total_division_cost, 0, ',', '.') }}</td>
                </tr>
                @foreach($div->items as $item)
                    <tr>
                        <td class="text-center">{{ $item->item_code }}</td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->spec)
                                <br><span style="color: #64748b; font-size: 8px;">Spesifikasi: {{ $item->spec }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($item->volume, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $item->unit }}</td>
                        <td class="text-right">{{ number_format($item->direct_unit_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->total_direct_cost, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Recap Table -->
    <table class="recap-table">
        <tr>
            <td>Total Biaya Langsung (Direct Cost)</td>
            <td class="text-right">Rp {{ number_format($budget->direct_cost, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Overhead Kantor ({{ $budget->overhead_percent }}%)</td>
            <td class="text-right">Rp {{ number_format($budget->overhead_cost, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Cadangan Risiko / Contingency ({{ $budget->contingency_percent }}%)</td>
            <td class="text-right">Rp {{ number_format($budget->contingency_cost, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL PAGU ANGGARAN (RAB)</td>
            <td class="text-right">Rp {{ number_format($budget->total_rab_amount, 0, ',', '.') }}</td>
        </tr>
        <tr class="offered-row">
            <td>HARGA PENAWARAN (OFFERED)</td>
            <td class="text-right">Rp {{ number_format($budget->offered_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="sign-table">
        <tr>
            <td>
                Dibuat Oleh,<br><strong>Estimator / Quantity Surveyor</strong>
                <div class="sign-space"></div>
                ( .................................................. )
            </td>
            <td>
                Diperiksa & Disetujui,<br><strong>Project Manager</strong>
                <div class="sign-space"></div>
                ( {{ $budget->project?->projectManager?->name ?: '..................................................' }} )
            </td>
            <td>
                Disetujui Oleh,<br><strong>Direktur Utama</strong>
                <div class="sign-space"></div>
                ( {{ $budget->approvedByUser?->name ?: '..................................................' }} )
            </td>
        </tr>
    </table>

</body>
</html>
