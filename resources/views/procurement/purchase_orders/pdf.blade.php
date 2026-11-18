<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .doc-title {
            font-size: 20px;
            font-weight: bold;
            color: #ea580c;
            text-align: right;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            text-align: left;
            padding: 8px 10px;
            text-transform: uppercase;
        }
        table.items td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px 8px;
            font-size: 10px;
        }
        .grand-total {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            border-top: 2px solid #cbd5e1;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div class="company-title">{{ $company->name ?? 'PT KONSTRUKSI MAJU NUSANTARA' }}</div>
                    <div style="color: #64748b; font-size: 10px; margin-top: 3px;">
                        {{ $company->address ?? 'Jl. Boulevard Konstruksi No. 88, BSD City, Tangerang' }}<br>
                        Telp: {{ $company->phone ?? '021-55667788' }} | Email: {{ $company->email ?? 'info@konstruksimaju.co.id' }}
                    </div>
                </td>
                <td style="width: 40%;">
                    <div class="doc-title">PURCHASE ORDER</div>
                    <div style="text-align: right; font-size: 11px; font-weight: bold; margin-top: 5px;">
                        NO: {{ $purchaseOrder->po_number }}
                    </div>
                    <div style="text-align: right; color: #64748b; font-size: 10px;">
                        Tanggal: {{ date('d/m/Y', strtotime($purchaseOrder->po_date)) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 48%;">
                <div class="box">
                    <strong style="color: #0f172a;">SUPPLIER / VENDOR:</strong><br>
                    <strong>{{ $purchaseOrder->supplier?->name }}</strong><br>
                    {{ $purchaseOrder->supplier?->address }}<br>
                    Kontak: {{ $purchaseOrder->supplier?->contact_person }} ({{ $purchaseOrder->supplier?->phone }})<br>
                    NPWP: {{ $purchaseOrder->supplier?->npwp ?? '-' }}
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%;">
                <div class="box">
                    <strong style="color: #0f172a;">KIRIM KE (PROYEK):</strong><br>
                    <strong>{{ $purchaseOrder->project?->name }}</strong><br>
                    Kode Proyek: {{ $purchaseOrder->project?->code }}<br>
                    Lokasi: {{ $purchaseOrder->project?->address }}<br>
                    Termin Pembayaran: <strong>{{ $purchaseOrder->payment_terms }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Deskripsi Material / Spesifikasi</th>
                <th style="width: 70px;" class="text-center">Qty</th>
                <th style="width: 50px;" class="text-center">Satuan</th>
                <th style="width: 100px;" class="text-right">Harga Satuan</th>
                <th style="width: 110px;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $item->material?->name }}</strong><br>
                        <span style="color: #64748b; font-size: 9px;">Kode: {{ $item->material?->code }}</span>
                    </td>
                    <td class="text-center font-bold">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal Item:</td>
            <td class="text-right">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>PPN ({{ $purchaseOrder->tax_percent }}%):</td>
            <td class="text-right">Rp {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Ongkos Kirim:</td>
            <td class="text-right">Rp {{ number_format($purchaseOrder->shipping_fee, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td>GRAND TOTAL:</td>
            <td class="text-right">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($purchaseOrder->notes)
        <div style="margin-top: 15px; font-size: 10px; color: #475569;">
            <strong>Catatan:</strong> {{ $purchaseOrder->notes }}
        </div>
    @endif

    <table class="signature-table">
        <tr>
            <td>
                Dibuat Oleh,<br>
                <div class="sign-space"></div>
                <strong>( Bagian Pengadaan )</strong>
            </td>
            <td>
                Disetujui Oleh,<br>
                <div class="sign-space"></div>
                <strong>( {{ $purchaseOrder->approvedByUser?->name ?? 'Project Manager' }} )</strong>
            </td>
            <td>
                Diterima Supplier,<br>
                <div class="sign-space"></div>
                <strong>( {{ $purchaseOrder->supplier?->contact_person ?? 'Supplier' }} )</strong>
            </td>
        </tr>
    </table>
</body>
</html>
