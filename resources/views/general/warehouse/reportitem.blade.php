<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Mutasi Item</title>
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
            <h2>Ringkasan Mutasi Item {{$item}}</h2>
            <p><small>Periode: {{ $start ?? '-' }} s.d {{ $end ?? '-' }}</small></p>
        </div>
    </div>

    <!-- Tabel Breakdown -->
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Qty Sebelumnya</th>
                <th>Qty Setelahnya</th>
                <th>Source</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($mutations as $mutation)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mutation->created_at)->format('d-M-Y') }}</td>
                    <td>{{ $mutation->type }}</td>
                    <td>{{ number_format($mutation->quantity_before) }}</td>
                    <td>{{ number_format($mutation->quantity_after) }}</td>
                    <td>{{ $mutation->source }}</td>
                    <td>{{ $mutation->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
