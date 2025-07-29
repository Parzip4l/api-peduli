@extends('layouts.vertical', ['title' => 'Dashboard'])
@section('css')
<!-- DataTables CSS -->
    <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="alert alert-info mb-3">
            Welcome back, <strong>{{ auth()->check() ? auth()->user()->username : 'Guest' }}</strong>!
        </div>
    </div>

    {{-- DASHBOARD ADMIN --}}
    @if (strtolower(auth()->user()->role) === 'admin')
        @include('dashboards.admin')

    {{-- DASHBOARD QSHE --}}
    @elseif (strtolower(auth()->user()->role) === 'qshe')
        @include('dashboards.qshe')

    {{-- DASHBOARD PIC --}}
    @elseif (strtolower(auth()->user()->is_pic) === '1')
        @include('dashboards.pic')

    {{-- DEFAULT / LAINNYA --}}
    @else
        <div class="col-12">
            <div class="alert alert-warning">
                Anda belum memiliki dashboard khusus.
            </div>
        </div>
    @endif
</div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

    <!-- ApexCharts & SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (strtolower(auth()->user()->role) === 'admin')
    <script>
        var laporanBulananChart = new ApexCharts(document.querySelector("#chart-laporan-bulanan"), {
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Laporan',
                data: {!! json_encode($monthlyReportData ?? []) !!}
            }],
            xaxis: {
                categories: {!! json_encode($monthlyLabels ?? []) !!}
            }
        });
        laporanBulananChart.render();

        var kategoriChart = new ApexCharts(document.querySelector("#chart-kategori"), {
            chart: { type: 'pie', height: 300 },
            series: {!! json_encode(array_values($kategoriData ?? [])) !!},
            labels: {!! json_encode(array_keys($kategoriData ?? [])) !!},
            legend: {
                position: 'bottom'
            }
        });
        kategoriChart.render();
    </script>
    <!-- Data Table -->
    <script>
        @if($recentReports->isNotEmpty())
            const laporan = {!! json_encode(
                $recentReports->map(function ($r) {
                    return [
                        'id' => hashid_encode($r->id),
                        'judul' => $r->judul,
                        'pelapor' => $r->pelapor->name ?? '-',
                        'divisi' => $r->division->name ?? '-',
                        'status' => [
                            'label' => ucwords(str_replace('_', ' ', $r->status)),
                            'color' => match($r->status) {
                                'closed' => 'success',
                                'open' => 'warning',
                                'rejected_by_qshe', 'rejected_by_pic', 'follow_up_rejected' => 'danger',
                                'assigned_to_division', 'under_review_by_qshe', 'follow_up_submitted' => 'info',
                                default => 'secondary'
                            }
                        ],
                        'tanggal' => $r->created_at->format('d/m/Y'),
                    ];
                })
            ) !!};

            new gridjs.Grid({
                columns: [
                    { name: "ID", hidden: true },
                    "Judul",
                    "Pelapor",
                    "Divisi",
                    {
                        name: "Status",
                        formatter: (cell) =>
                            gridjs.html(`<span class="badge bg-${cell.color}">${cell.label}</span>`)
                    },
                    "Tanggal",
                    {
                        name: "Tindakan",
                        formatter: (_, row) =>
                            gridjs.html(`<a href="/laporan/${row.cells[0].data}" class="btn btn-sm btn-outline-danger">Lihat</a>`)
                    }
                ],
                data: laporan.map(item => [
                    item.id,
                    item.judul,
                    item.pelapor,
                    item.divisi,
                    item.status,
                    item.tanggal
                ]),
                pagination: { limit: 5 },
                search: true
            }).render(document.getElementById("tabel-laporan"));
        @else
            document.getElementById("tabel-laporan").innerHTML = `<div class="text-center text-muted">Tidak ada data laporan terbaru.</div>`;
        @endif

    </script>

    <script>
        function updateChartByYear(tahun) {
            window.location.href = "{{ route('dashboard.index') }}?tahun=" + tahun;
        }
    </script>
    @endif
@endsection
