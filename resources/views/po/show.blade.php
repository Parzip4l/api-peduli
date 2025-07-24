@extends('layouts.vertical', ['title' => 'Details Purchase Order'])
<style>
    .hr-striped {
        border: 0;
        height: 1px;
        background-image: linear-gradient(to right, #ccc 33%, rgba(255, 255, 255, 0) 0%);
        background-position: top;
        background-size: 10px 1px;
        background-repeat: repeat-x;
        margin: 1rem 0;
    }
    .hr-short {
        width: 65%;
        margin-left: 0;
    }
</style>
@section('content')
<div class="row">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                {{-- Header: Judul dan Tombol Aksi --}}
                <div class="row mb-3">
                    <div class="col-md-8 mb-2">
                        <h4 class="mb-0">Detail Purchase Order: {{ $purchaseOrder->po_number }}</h4>
                        <small>dibuat oleh: {{ $purchaseOrder->creator->name }} - {{ $purchaseOrder->created_at->format('d M Y H:i') }}</small>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary mb-1">← Kembali</a>
                        @if($purchaseOrder->status != 'received')
                        <button class="btn btn-success mb-1" data-bs-toggle="modal" data-bs-target="#receiveModal">Received</button>
                        @endif
                        @if($purchaseOrder->status === 'partial' || $purchaseOrder->status === 'received')
                            <button class="btn btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#returnModal">Return</button>
                        @endif
                        <a href="{{ route('purchase_orders.edit', $purchaseOrder->id) }}" class="btn btn-warning mb-1">Edit</a>
                        <a href="{{ route('purchase_orders.print', $purchaseOrder->id) }}" class="btn btn-outline-dark mb-1" target="_blank">Print PDF</a>
                    </div>
                </div>

                {{-- Navigasi Sebelumnya / Berikutnya --}}
                <div class="row">
                    <div class="col-6">
                        @if($prev)
                            <a href="{{ route('purchase_orders.show', $prev->id) }}" class="btn btn-outline-secondary w-100">← PO Sebelumnya</a>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        @if($next)
                            <a href="{{ route('purchase_orders.show', $next->id) }}" class="btn btn-outline-secondary w-100">PO Berikutnya →</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Received -->
        <div class="modal fade" id="receiveModal" tabindex="-1" aria-labelledby="receiveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('purchase_orders.received', $purchaseOrder->id) }}">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="receiveModalLabel">Terima Barang PO #{{ $purchaseOrder->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Qty Order</th>
                                        <th>Sudah Diterima</th>
                                        <th>Sisa</th>
                                        <th>Qty Diterima Sekarang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($purchaseOrder->items as $item)
                                    @php
                                    $received = $item->received_quantity ?? 0;
                                    $remaining = $item->quantity - $received;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->warehouseItem->name ?? 'Barang tidak ditemukan' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $received }}</td>
                                        <td>{{ $remaining }}</td>
                                        <td>
                                            <input type="number"
                                                name="received[{{ $item->id }}]"
                                                value="0"
                                                min="0"
                                                max="{{ $remaining }}"
                                                class="form-control"
                                                placeholder="Maks: {{ $remaining }}"
                                                {{ $remaining <= 0 ? 'disabled' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Penerimaan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Model Retun -->
        <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('purchase_orders.return', $purchaseOrder->id) }}">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Return Barang PO #{{ $purchaseOrder->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="return_date">Tanggal Retur</label>
                                <input type="date" class="form-control" name="return_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="note">Catatan Umum</label>
                                <input type="text" class="form-control" name="note" placeholder="Opsional">
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Diterima</th>
                                        <th>Qty Retur</th>
                                        <th>Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder->items as $i => $item)
                                        @php
                                            $received = $item->received_quantity ?? 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $item->warehouseItem->name ?? 'Barang tidak ditemukan' }}
                                                <input type="hidden" name="items[{{ $i }}][purchase_order_item_id]" value="{{ $item->id }}">
                                                <input type="hidden" name="items[{{ $i }}][warehouse_item_id]" value="{{ $item->warehouse_item_id }}">
                                            </td>
                                            <td>{{ $received }}</td>
                                            <td>
                                                <input type="number" name="items[{{ $i }}][quantity]" min="0" max="{{ $received }}" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{ $i }}][reason]" class="form-control" placeholder="Opsional...">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Simpan Return</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">🔖 Informasi PO</h5>
                    </div>
                    <div class="card-body">
                        {{-- Baris 1 --}}
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <strong>No PO:</strong><br>
                                {{ $purchaseOrder->po_number }}
                               
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Supplier:</strong><br>
                                {{ $purchaseOrder->distributor->name ?? '-' }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Status:</strong><br>
                                <span class="badge 
                                    @if($purchaseOrder->status === 'received') bg-success 
                                    @elseif($purchaseOrder->status === 'partial') bg-warning 
                                    @elseif($purchaseOrder->status === 'returned') bg-danger 
                                    @else bg-secondary @endif">
                                    {{ ucfirst($purchaseOrder->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>

                        {{-- Baris 2 --}}
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <strong>Tanggal PO:</strong><br>
                                {{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d M Y') }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Jatuh Tempo:</strong><br>
                                {{ \Carbon\Carbon::parse($purchaseOrder->due_date)->format('d M Y') }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Terms Of Payment:</strong><br>
                                {{ $purchaseOrder->top ?? '-' }}
                            </div>
                        </div>

                        {{-- Garis Strip --}}
                        <hr class="hr-striped">

                        {{-- Diskon, Pajak, Subtotal --}}
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <strong>Diskon:</strong><br>
                                {{ number_format($purchaseOrder->discount ?? 0) }}%
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Pajak:</strong><br>
                                {{ number_format($purchaseOrder->tax ?? 0) }}%
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Subtotal:</strong><br>
                                Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Garis Strip Khusus Total --}}
                        <hr class="hr-striped hr-short">

                        {{-- Total dan Metode Pembayaran --}}
                        <div class="row align-items-center mb-2">
                            <div class="col-md-6">
                                <strong>Total:</strong><br>
                                <h4 class="text-primary mb-0">Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</h4>
                            </div>
                            <div class="col-md-6">
                                <strong>Payment Method:</strong><br>
                                {{ ucfirst($purchaseOrder->payment_method) ?? '-' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Log -->
            <div class="col-md-4 mb-4">
                <div class="card  h-100">
                    <div class="card-header">
                        <h5 class="mb-0">🕘 Riwayat Perubahan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 300px; overflow-y: auto;" class="p-3">
                            @if($purchaseOrder->activities->isEmpty())
                                <p class="text-muted mb-0">Belum ada riwayat perubahan.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach($purchaseOrder->activities as $activity)
                                        <li class="list-group-item px-0">
                                            <div class="mb-1">
                                                <strong>{{ $activity->created_at->format('d M Y H:i') }}</strong> —
                                                {{ $activity->causer->name ?? 'System' }} melakukan
                                                <span class="text-primary">{{ $activity->description }}</span>
                                            </div>

                                            @php
                                                $changes = $activity->properties['attributes'] ?? [];
                                                $old = $activity->properties['old'] ?? [];
                                            @endphp

                                            @if(count($changes))
                                                <ul class="ps-3 mb-0">
                                                    @foreach($changes as $key => $value)
                                                        @if(isset($old[$key]) && $old[$key] != $value)
                                                            <li>
                                                                <small>
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>: 
                                                                    <span class="text-danger">{{ $old[$key] }}</span> → 
                                                                    <span class="text-success">{{ $value }}</span>
                                                                </small>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📦 Daftar Produk </h5>
            </div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->warehouseItem->name ?? '-' }} {{ $item->warehouseItem->type ?? '-' }}</td>
                                    <td>{{ number_format($item->quantity) }}</td>
                                    <td>{{ $item->warehouseItem->unit ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    
</div>
@endsection
