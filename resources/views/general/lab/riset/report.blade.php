@extends('layouts.vertical', ['title' => 'Report Data '])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<a href="{{route('log-riset-grease.index')}}" class="btn btn-sm btn-primary mb-2"><iconify-icon icon="mynaui:chevron-left-solid" class="align-middle fs-18"></iconify-icon> Kembali</a>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3>Report Research</h3>
                <p>{{ $startDate->format('F Y') }} - {{ $endDate->format('F Y') }}</p>
            </div>
            <div class="card-body">
                @if ($risetDataWithDetails->isEmpty())
                    <p>Tidak ada riset dalam rentang tanggal ini.</p>
                @else
                    <p>Jumlah riset yang ditemukan: {{ $risetDataWithDetails->count() }}</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-dark">Nama Riset</th>
                                    <th class="text-dark">Tanggal Mulai</th>
                                    <th class="text-dark">Jumlah Histori Research</th>
                                    <th class="text-dark">Researcher</th>
                                    <th class="text-dark">Status Research</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($risetDataWithDetails as $riset)
                                    <tr>
                                        <td>{{ $riset->product_name }}</td>
                                        <td>{{ $riset->expected_start_date}}</td>
                                        <td>{{ $riset->details_count }} <button class="btn btn-link text-primary view-details" data-riset-id="{{ $riset->id }}">Lihat Details</button></td>
                                        <td>{{$riset->created_by}}</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr class="riset-details-row" id="details-{{ $riset->id }}" style="display: none;">
                                        <td colspan="5">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="bg-secondary">
                                                        <th class="text-white">Detail ID</th>
                                                        <th class="text-white">Tanggal</th>
                                                        <th class="text-white">Method</th>
                                                        <th class="text-white">Result</th>
                                                        <th class="text-white">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($riset->details as $detail)
                                                        <tr>
                                                            <td>{{ $detail->id }}</td>
                                                            <td>{{ $detail->created_at ? $detail->created_at->format('d M Y') : 'N/A' }}</td>
                                                            <td>{{ $detail->trial_method }}</td>
                                                            <td>{{ $detail->trial_result }}</td>
                                                            <td>{{ $detail->status }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-bottom')
@vite(['resources/js/pages/ecommerce-product-details.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Menangani klik pada tombol "Lihat Details"
    document.querySelectorAll('.view-details').forEach(function(button) {
        button.addEventListener('click', function() {
            var risetId = button.getAttribute('data-riset-id');
            var detailsRow = document.getElementById('details-' + risetId);

            // Toggle tampilkan/ sembunyikan detail riset
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        });
    });
</script>
@endsection
