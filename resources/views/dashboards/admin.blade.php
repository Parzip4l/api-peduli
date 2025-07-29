{{-- Statistik ringkas --}}
<div class="col-md-3">
    <div class="card text-center">
        <div class="card-body">
            <h6 class="text-muted">Total Laporan</h6>   
            <h3>{{ $totalReports ?? 0 }}</h3>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card text-center">
        <div class="card-body">
            <h6 class="text-muted">Selesai</h6>
            <h3>{{ $closedReports ?? 0 }}</h3>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card text-center">
        <div class="card-body">
            <h6 class="text-muted">Belum Ditindaklanjuti</h6>
            <h3>{{ $pendingReports ?? 0 }}</h3>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card text-center">
        <div class="card-body">
            <h6 class="text-muted">Sedang Diproses</h6>
            <h3>{{ $onProgressReports ?? 0 }}</h3>
        </div>
    </div>
</div>
{{-- Grafik tren laporan per bulan --}}
<div class="mb-3">
    <label for="filter-tahun" class="form-label">Filter Tahun</label>
    <select id="filter-tahun" class="form-select" onchange="updateChartByYear(this.value)">
        @foreach ($availableYears as $year)
            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            Grafik Tren Laporan per Bulan
        </div>
        <div class="card-body">
            <div id="chart-laporan-bulanan" style="height: 300px;"></div>
        </div>
    </div>
</div>

{{-- Pie chart kategori --}}
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            Laporan Berdasarkan Lokasi
        </div>
        <div class="card-body">
            <div id="chart-kategori" style="height: 300px;"></div>
        </div>
    </div>
</div>

{{-- Tabel laporan terbaru --}}
<div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span class="align-self-center">Daftar Laporan Terbaru</span>
            <div>
                <button class="btn btn-sm btn-success">Export Excel</button>
                <button class="btn btn-sm btn-danger">Export PDF</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div id="tabel-laporan"></div>
        </div>
    </div>
</div>