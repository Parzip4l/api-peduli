@extends('layouts.vertical', ['title' => 'Production Forecast'])

@section('content')
    <div class="mt-4">      
        <h3 class="mb-4">
        📦 Prediksi Kebutuhan Produksi 
            @if($adaKekurangan ?? false)
                <span class="badge bg-danger ms-2 p-1 blink" title="Ada bahan yang stoknya kurang">
                ⚠️ Stok Bahan Baku di Warehouse Kurang !
                </span>
            @endif
        </h3>
        <div class="card">
            <div class="card-header">
                <h4>🔖 Informasi Produk</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Packaging</th>
                                <th>Size (Kg)</th>
                                <th>Quantity</th>
                                <th>Total Kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packagingInfo as $info)
                                <tr>
                                    <td>{{ $produk }}</td>
                                    <td>{{ $info['packaging'] }}</td>
                                    <td>{{ $info['size'] }} Kg</td>
                                    <td>{{ number_format($info['quantity']) }}</td>
                                    <td>{{ number_format($info['total_kg']) }} Kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
               </div>
            </div>
        </div>

        @if($predictedMaterials->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h4>⛽ Prediksi Penggunaan Bahan Bakar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tangki</th>
                                <th>Prediksi Jumlah (liter)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tangki Masak</td>
                                <td>{{ number_format($predictedFuelMasak, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Tangki Olah</td>
                                <td>{{ number_format($predictedFuelOlah, 0) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Kebutuhan Solar</strong></td>
                                <td><strong>{{ number_format($totalPredictedFuel, 0) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Stok Solar di Gudang</strong></td>
                                <td><strong>{{ number_format($solarStock, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status Solar</strong></td>
                                <td>
                                    @if ($solarKekurangan > 0)
                                        <span class="text-danger"><strong>Kurang {{ number_format($solarKekurangan, 2) }} liter</strong></span>
                                    @else
                                        <span class="text-success">Cukup</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @endif

        @if(!empty($predictedMaterials))
            <div class="row">

                <!-- Tabel -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4>📦 Prediksi Kebutuhan Bahan Baku</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama Bahan</th>
                                        <th scope="col">Tipe</th>
                                        <th scope="col">Jenis</th>
                                        <th scope="col">Prediksi Jumlah</th>
                                        <th scope="col">Stok Gudang</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($predictedMaterials as $item)
                                        <tr>
                                            <td>{{ $item['kategori'] }}</td>
                                            <td>{{ $item['tipe'] }}</td>
                                            <td>{{ $item['jenis'] }}</td>
                                            <td>{{ number_format($item['predicted_qty'], 0) }} Kg</td>
                                            <td>{{ number_format($item['stok_gudang'], 2) }} Kg</td>
                                            <td>
                                                @if ($item['kekurangan'] > 0)
                                                    <span class="text-danger">Kurang {{ number_format($item['kekurangan'], 2) }} Kg</span>
                                                @else
                                                    <span class="text-success">Cukup</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <strong>Info!</strong> Tidak ada data prediksi untuk produk ini.
            </div>
        @endif
        <a href="{{ route('production_batches.index') }}" class="btn btn-primary btn-sm mb-2">
            <iconify-icon icon="solar:alt-arrow-left-bold" class="align-middle fs-18"></iconify-icon> Kembali
        </a>
    </div>
@endsection
@section('script')
<style>
  .blink {
    animation: blink-animation 1.5s infinite;
  }
  @keyframes blink-animation {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
  }
</style>
@endsection

