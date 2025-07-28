<div class="row">
    <!-- Kartu Statistik -->
    <div class="col-md-3">
        <div class="card card-body bg-white text-white">
            <h6>Total Laporan</h6>
            <h3>{{ $totalReports ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body bg-white text-white">
            <h6>Belum Ditinjau</h6>
            <h3>{{ $ditinjau ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body bg-white text-white">
            <h6>Sudah Selesai</h6>
            <h3>{{ $followUpReports ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body bg-white text-white">
            <h6>Ditolak</h6>
            <h3>{{ $rejectedByQshe ?? 0 }}</h3>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Tabel Laporan QSHE -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Laporan Perlu Tindakan QSHE</div>
            <div class="card-body">
                <div id="tabel-laporan2" class="table-responsive"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Grafik Tren Bulanan -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Grafik Tren Laporan K3 Bulanan</div>
            <div class="card-body">
                <div id="chart-laporan-bulanan"></div>
            </div>
        </div>
    </div>

    <!-- Grafik Area Rawan Kecelakaan -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Area Rawan Kecelakaan</div>
            <div class="card-body">
                <div id="chart-kategori"></div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

<!-- ApexCharts & SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    @if($recentReports->isNotEmpty())
        const laporan2 = {!! json_encode(
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
            data: laporan2.map(item => [
                item.id,
                item.judul,
                item.pelapor,
                item.divisi,
                item.status,
                item.tanggal
            ]),
            pagination: { limit: 5 },
            search: true
        }).render(document.getElementById("tabel-laporan2"));
    @else
        document.getElementById("tabel-laporan2").innerHTML = `<div class="text-center text-muted">Tidak ada data laporan terbaru.</div>`;
    @endif

</script>

<script>
    function updateChartByYear(tahun) {
        window.location.href = "{{ route('dashboard.index') }}?tahun=" + tahun;
    }
</script>