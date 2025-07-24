@extends('layouts.vertical', ['title' => 'Detail Produksi'])

@section('content')
<div class="py-4">
    <a href="{{ route('production_batches.index') }}" class="btn btn-primary btn-sm mb-2">
        <iconify-icon icon="solar:alt-arrow-left-bold" class="align-middle fs-18"></iconify-icon> Kembali
    </a>
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">🧪 Detail Produksi</h3>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="border rounded p-3 h-100 bg-light">
                        <h6 class="text-secondary">🔖 Informasi Umum</h6>
                        <p class="mb-1"><strong>Batch ID:</strong> #{{ $batch->batch_code }}</p>
                        <p class="mb-1"><strong>Produk:</strong> {{ $batch->produk }}</p>
                        <p class="mb-1">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $batch->status === 'Closed' ? 'success' : 'warning' }}">
                                {{ $batch->status }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="border rounded p-3 h-100 bg-light">
                        <h6 class="text-secondary">⛽ Penggunaan Bahan Bakar</h6>
                        <p class="mb-1">
                            <strong>Tangki Masak:</strong> Tangki {{ $batch->tangki_masak }} 
                            <span class="text-muted">({{ number_format($batch->qty_bahan_bakar_masak, 0) }} L)</span>
                        </p>
                        <p class="mb-0">
                            <strong>Tangki Olah:</strong> Tangki {{ $batch->tangki_olah }} 
                            <span class="text-muted">({{ number_format($batch->qty_bahan_bakar_olah, 0) }} L)</span>
                        </p>
                    </div>
                </div>
            </div>


            <hr>

            <h4 class="mb-3 text-primary">📦 Bahan Baku</h4>
            @foreach ($groupedMaterials as $step => $kategoriGroup)
                <div class="mb-4">
                    <span class="badge bg-success">{{ $step }}</span>
                    @foreach ($kategoriGroup as $kategori => $items)
                        <div class="mb-3">
                            <div class="fw-semibold mb-2">Kategori: {{ $kategori }}</div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped table-sm align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 30%">Jenis</th>
                                            <th style="width: 30%">Tipe</th>
                                            <th style="width: 20%">Qty</th>
                                            <th style="width: 20%">Satuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $item->jenis ?? '-' }}</td>
                                                <td>{{ $item->tipe ?? '-' }}</td>
                                                <td>{{ number_format($item['qty'], 0) }}</td>
                                                <td>Kg</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            @if ($hasilProduksi)
                <hr>
                <h4 class="mb-3 text-success">✅ Hasil Produksi</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle text-center">
                        <thead class="table-success">
                            <tr>
                                <th style="width: 30%">Packaging</th>
                                <th style="width: 20%">Size (Kg)</th>
                                <th style="width: 25%">Quantity</th>
                                <th style="width: 25%">Total (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasilProduksi as $hasil)
                                <tr>
                                    <td>{{ ucfirst($hasil['packaging']) }}</td>
                                    <td>{{ $hasil['size'] }}</td>
                                    <td>{{ number_format($hasil['quantity'], 0) }}</td>
                                    <td>{{ number_format($hasil['total_kg'], 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
