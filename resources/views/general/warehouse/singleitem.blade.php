@extends('layouts.vertical', ['title' => 'Detail Item'])

@section('content')

<div class="row">
    <div class="col-lg-12">
        <a href="{{ route('warehouse.index') }}" class="btn btn-primary btn-sm mb-2">
            <iconify-icon icon="solar:alt-arrow-left-bold" class="align-middle fs-18"></iconify-icon> Kembali
        </a>
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-3 border-end">
                        <div class="">
                            <h2 class="mb-1">{{ $item->name }} ({{ $item->code }}) </h2>
                            @if($item->stocks->sum('quantity') < $item->minimum_qty)
                                <div class="text-danger small mb-2 text-start">Stok kurang dari minimum!</div>
                            @endif
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tipe</span>
                                    <strong>{{ $item->type ?? '-' }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Kategori</span>
                                    <strong>{{ $item->category }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>UoM</span>
                                    <strong>{{ $item->unit }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Stok Barang</span>
                                    <strong class="{{ $item->stocks->sum('quantity') < $item->minimum_qty ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($item->stocks->sum('quantity')) }} {{ $item->unit }}
                                    </strong>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <span>Stok Minimum</span>
                                    <strong>{{ number_format($item->minimum_qty) }} {{ $item->unit }}</strong>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('warehouse.items.edit', $item->id) }}" class="btn btn-primary w-100">Edit Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="ps-lg-4">
                            <h4 class="card-title">5 Data Mutasi Terakhir</h4>
                            @if($mutations->isEmpty())
                                <p>Tidak ada data mutasi.</p>
                            @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tipe</th>
                                            <th>Quantity</th>
                                            <th>Qty Sebelumnya</th>
                                            <th>Qty Setelahnya</th>
                                            <th>Source</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mutations as $mutation)
                                            <tr @class([
                                                'table-success' => $mutation->type === 'in',
                                                'table-danger' => $mutation->type === 'out',
                                                'table-warning' => $mutation->type === 'adjustment',
                                            ])>
                                                <td>{{ $mutation->created_at->format('d-m-Y H:i') }}</td>
                                                <td>{{ ucfirst($mutation->type) }}</td>
                                                <td>{{ number_format($mutation->quantity )}}</td>
                                                <td>{{ number_format($mutation->quantity_before )}}</td>
                                                <td>{{ number_format($mutation->quantity_after) }}</td>
                                                <td>{{ $mutation->source }}</td>
                                                <td>{{ $mutation->note }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="mt-1">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadReportModal">
                                    Download Report Mutasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- Modal Pilih Rentang Tanggal -->
    <div class="modal fade" id="downloadReportModal" tabindex="-1" aria-labelledby="downloadReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('warehouse.items.mutations.download')}}" method="GET" target="_blank">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="downloadReportLabel">Pilih Rentang Tanggal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="end_date" required>
                            <input type="hidden" name="item" value="{{ $item->name }} {{ $item->type }}">
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Download PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->
</div>




<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h3 class="d-flex align-items-center gap-2">Grafik Mutasi Barang </h3>
                    <!-- <p class="mb-0 {{ $stokawal < $minimumStock ? 'text-danger' : 'text-success' }}">
                        {{ $stockStatus }}
                    </p> -->
                </div>
                <div class="avatar-md bg-light bg-opacity-50 rounded">
                    <iconify-icon icon="solar:chart-2-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                </div>
            </div>
            
            <div class="apex-charts">
                <div id="stockChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@php
    $labels = $chartMutations->pluck('created_at')->map(fn($d) => $d->format('d M Y H:i'))->toArray();
    $dataQty = $chartMutations->pluck('quantity_after')->toArray();
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = {!! json_encode($labels) !!};
        const dataQty = {!! json_encode($dataQty) !!};
        const minStock = {{ $minimumStock }};
        const unit = "{{ $unit }}";

        const options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            series: [{
                name: 'Stok Barang',
                data: dataQty
            }],
            xaxis: {
                categories: labels,
                title: { text: 'Tanggal' }
            },
            yaxis: {
                title: { text: 'Qty' },
                min: Math.min(...dataQty, minStock) - 10,
                max: Math.max(...dataQty, minStock) + 10,
                labels: {
                    formatter: function (val) {
                        return Number(val).toLocaleString('id-ID', { maximumFractionDigits: 0 });
                    }
                }
            },
            markers: {
                size: 6,
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 8 },
                discrete: dataQty.map((val, idx) => ({
                    seriesIndex: 0,
                    dataPointIndex: idx,
                    fillColor: val > minStock ? '#28a745' : '#dc3545',
                    strokeColor: '#fff',
                    size: 6
                }))
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return Number(val).toLocaleString('id-ID', { maximumFractionDigits: 0 }) + " " + unit;
                    }
                }
            },
            annotations: {
                yaxis: [{
                    y: minStock,
                    borderColor: '#ef5f5f',
                    strokeDashArray: 5,
                    label: {
                        borderColor: '#f1c40f',
                        style: {
                            color: '#fff',
                            background: '#f1c40f',
                            fontSize: '12px',
                            fontWeight: 600
                        },
                        text: 'Minimum Stok: ' + Number(minStock).toLocaleString('id-ID', { maximumFractionDigits: 0 }) + ' ' + unit,
                        position: 'right',
                        offsetY: 0
                    }
                }],
                regions: [{
                    y: minStock,
                    y2: Math.min(...dataQty),
                    fillColor: 'rgba(239, 95, 95, 0.15)', // kuning transparan
                    opacity: 0.5
                }]
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#FFC512']
            },
            legend: {
                show: true,
                labels: {
                    colors: '#333'
                },
                markers: {
                    fillColors: ['#28a745', '#dc3545']
                },
                customLegendItems: ['Stok > Minimum', 'Stok <= Minimum'],
            }
        };

        const chart = new ApexCharts(document.querySelector("#stockChart"), options);
        chart.render();
    });
</script>


@endsection