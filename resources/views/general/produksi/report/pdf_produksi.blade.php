<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Produksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            height: 60px;
            margin-right: 15px;
        }

        .header-title {
            flex-grow: 1;
            text-align: center;
        }

        h2 {
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .summary {
            margin-top: 20px;
            font-size: 13px;
        }

        .right {
            text-align: right;
        }

        .signature {
            margin-top: 80px;
            width: 100%;
            text-align: center;
        }

        .signature p {
            margin: 6px 0;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/champortal.png') }}" alt="Logo">
        <div class="header-title">
            <h2>Ringkasan Produksi per Produk dan Kemasan</h2>
            <p><small>Periode: {{ $from ?? '-' }} s.d {{ $to ?? '-' }}</small></p>
        </div>
    </div>

    <!-- Tabel Breakdown -->
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kemasan</th>
                <th>Ukuran (kg)</th>
                <th>Jumlah Unit</th>
                <th>Total Ton</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($breakdownData as $row)
                <tr>
                    <td>{{ $row->produk }}</td>
                    <td>{{ $row->kemasan }}</td>
                    <td>{{ $row->size }}</td>
                    <td>{{ number_format($row->total_unit) }}</td>
                    <td>{{ number_format($row->total_ton, 2) }}</td>
                </tr>
                @php $grandTotal += $row->total_ton; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="right">Total Ton</th>
                <th>{{ number_format($grandTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- Ringkasan -->
    <div class="summary">
        @if($selectedProduk)
            <p><strong>Produk:</strong> {{ $selectedProduk }}</p>
        @endif
    </div>

    <!-- Halaman Tanda Tangan -->
    <div class="page-break"></div>
    <div class="signature">
        <p>Mengetahui,</p>
        <p><strong>Kepala Produksi</strong></p>
        <br><br><br>
        <p><u>(Gana Dwiaji A)</u></p>
    </div>
</body>
</html>
