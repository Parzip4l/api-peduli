<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Purchase Order {{ $purchase_order->po_number }}</title>
    <style>
        /* Reset dan font */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0 30px 30px 30px;
            line-height: 1.5;
        }

        h1, h2, h3, h4, h5 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 0.5rem;
        }

        h4 {
            font-size: 18px;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid #aaa;
            padding-bottom: 4px;
        }

        /* Header flex container */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .header-table td {
            vertical-align: middle; /* vertikal center semua cell */
            padding: 0;
        }

        /* Kolom logo */
        .company-info {
            width: 50%;
            padding-right: 20px;
            white-space: nowrap;
        }

        .company-logo {
            max-height: 60px;
            width: auto;
            vertical-align: middle;
            display: inline-block;
            margin-right: 15px;
        }

        .company-address {
            font-size: 12px;
            color: #555;
            line-height: 1.3;
            display: inline-block;
            vertical-align: middle;
        }

        /* Kolom info PO */
        .po-title-info {
            width: 50%;
            text-align: right;
            font-size: 14px;
            color: #333;
            padding-left: 20px;
        }

        .po-title-info h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
            line-height: 1;
        }

        .header-info {
            font-size: 12px;
            color: #777;
            line-height: 1.4;
        }


        /* Tabel untuk info PO dan ringkasan pembayaran */
        .info-payment-table > table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-payment-table td {
            vertical-align: top;
            padding: 0 1px;
            width: 50%;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .inner-table td {
            padding: 5px 8px;
            vertical-align: top;
        }

        .inner-table tr td:first-child {
            width: 40%;
            font-weight: 600;
            color: #34495e;
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 10px 0;
        }

        .total {
            font-size: 16px;
            color: #2980b9;
            font-weight: 700;
        }

        /* Tabel produk */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.items-table th, table.items-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 13px;
        }

        table.items-table th {
            background-color: #aaa;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .text-right {
            text-align: right;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<table class="header-table">
        <tr>
            <td class="company-info">
                <img src="{{ public_path('images/champ-black.png') }}" alt="Logo CHAMPOIL" class="company-logo" /><br>
                <div class="company-address">
                    Jl. Kapuk Kencana No.36A, RT.2/RW.3, Kapuk Muara, Kec. Penjaringan, <br>Jkt Utara, Daerah Khusus Ibukota Jakarta 14460<br />
                    Telp: 081-1983-159<br />
                    Email: info@champoil.co.id
                </div>
            </td>

            <td class="po-title-info">
                <h1>Purchase Order</h1>
                <div class="header-info">
                    PO Number: {{ $purchase_order->po_number }}<br />
                    Created by: {{ $purchase_order->creator->name ?? '-' }}<br />
                    Created at: {{ $purchase_order->created_at->format('d M Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="info-payment-table">
        <table>
            <tbody>
                <tr>
                    <td>
                        <h4>Informasi PO</h4>
                        <table class="inner-table">
                            <tbody>
                                <tr><td><strong>Supplier</strong></td><td>: {{ $purchase_order->distributor->name ?? '-' }}</td></tr>
                                <tr><td><strong>Status</strong></td><td>: {{ ucfirst($purchase_order->status ?? 'pending') }}</td></tr>
                                <tr><td><strong>Tanggal PO</strong></td><td>: {{ \Carbon\Carbon::parse($purchase_order->po_date)->format('d M Y') }}</td></tr>
                                <tr><td><strong>Jatuh Tempo</strong></td><td>: {{ \Carbon\Carbon::parse($purchase_order->due_date)->format('d M Y') }}</td></tr>
                                <tr><td><strong>Terms of Payment</strong></td><td>: {{ $purchase_order->top ?? '-' }}</td></tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <h4>Ringkasan Pembayaran</h4>
                        <table class="inner-table">
                            <tbody>
                                <tr><td><strong>Diskon</strong></td><td>: {{ number_format($purchase_order->discount ?? 0) }}%</td></tr>
                                <tr><td><strong>Pajak</strong></td><td>: {{ number_format($purchase_order->tax ?? 0) }}%</td></tr>
                                <tr><td><strong>Subtotal</strong></td><td>: Rp {{ number_format($purchase_order->subtotal, 0, ',', '.') }}</td></tr>
                                <tr><td colspan="2"><hr></td></tr>
                                <tr><td class="total" colspan="2"><strong>Total</strong>: Rp {{ number_format($purchase_order->total, 0, ',', '.') }}</td></tr>
                                <tr><td><strong>Payment Method</strong></td><td>: {{ ucfirst($purchase_order->payment_method) ?? '-' }}</td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>Daftar Produk</h4>
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th class="text-right">Qty</th>
                <th>Satuan</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase_order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->warehouseItem->name ?? '-' }} {{ $item->warehouseItem->type ?? '' }}</td>
                <td class="text-right">{{ number_format($item->quantity) }}</td>
                <td>{{ $item->warehouseItem->unit ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated by CHAMPOIL INDONESIA &copy; {{ date('Y') }}<br>
        Purchase Order generated on {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>
