@extends('layouts.vertical', ['title' => 'Dashboard Production'])

@section('content')
<div class="row">
    <div class="col-xxl-12">
        <div class="row">

            <div class="col-md-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-md bg-soft-primary rounded">
                                    <iconify-icon icon="solar:inbox-in-broken" class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> 
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0 text-truncate">Produksi Hari Ini</p>
                                {{-- Tampilkan data dari controller --}}
                                <h3 class="text-dark mt-1 mb-0">{{ number_format($produksiHariIni, 2) }} Ton</h3>
                            </div> 
                        </div> 
                    </div> 
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                {{-- Logika untuk menampilkan panah & warna --}}
                                @if($persentasePerubahanHarian > 0)
                                    <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> {{ number_format($persentasePerubahanHarian, 1) }}%</span>
                                @elseif($persentasePerubahanHarian < 0)
                                    <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> {{ number_format(abs($persentasePerubahanHarian), 1) }}%</span>
                                @else
                                    <span class="text-muted"> <i class="bx bx-minus fs-12"></i> 0%</span>
                                @endif
                                <span class="text-muted ms-1 fs-12">Dari Hari Kemarin</span>
                            </div>
                            <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="avatar-md bg-soft-primary rounded">
                                    <iconify-icon icon="solar:inbox-in-broken" class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> 
                            <div class="col-9 text-end">
                                <p class="text-muted mb-0 text-truncate">Produksi Bulan Ini</p>
                                {{-- Tampilkan data dari controller --}}
                                <h3 class="text-dark mt-1 mb-0">{{ number_format($produksiBulanIni, 2) }} Ton</h3>
                            </div> 
                        </div> 
                    </div> 
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                {{-- Logika untuk menampilkan panah & warna --}}
                                @if($persentasePerubahanBulanan > 0)
                                    <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> {{ number_format($persentasePerubahanBulanan, 1) }}%</span>
                                @elseif($persentasePerubahanBulanan < 0)
                                    <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> {{ number_format(abs($persentasePerubahanBulanan), 1) }}%</span>
                                @else
                                    <span class="text-muted"> <i class="bx bx-minus fs-12"></i> 0%</span>
                                @endif
                                <span class="text-muted ms-1 fs-12">Dari Bulan Lalu</span>
                            </div>
                            <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-md bg-soft-primary rounded">
                                    <iconify-icon icon="solar:inbox-in-broken" class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0 text-truncate">Total Kapasitas Produksi</p>
                                <h3 class="text-dark mt-1 mb-0">499,4 ton</h3>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div> <!-- end card body -->
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#!" class="text-reset fw-semibold fs-12">Total Kapasitas Produksi 3 Tangki Dalam 1 Bulan</a>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-md-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-md bg-soft-primary rounded">
                                    <iconify-icon icon="solar:inbox-in-broken" class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0 text-truncate">Total Kapasitas Produksi</p>
                                <h3 class="text-dark mt-1 mb-0">22,7 ton</h3>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div> <!-- end card body -->
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#!" class="text-reset fw-semibold fs-12">Total Kapasitas Produksi 3 Tangki Dalam 1 hari</a>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div> <!-- end col -->


        </div> <!-- end row -->
    </div> <!-- end col -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Grafik Produksi Harian (30 Hari Terakhir)</h4>
                </div>
                <div class="card-body">
                    <canvas id="productionLineChart" style="width:100%; height:350px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Tren Produksi Produk (Ton)</h4>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="produk" class="form-label">Produk</label>
                        <select name="produk" id="produk" class="form-select">
                            <option value="">Semua Produk</option>
                            @foreach ($produkOptions as $produk)
                                <option value="{{ $produk }}" {{ $produk == $selectedProduk ? 'selected' : '' }}>
                                    {{ $produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="from" class="form-label">Dari Tanggal</label>
                        <input type="date" name="from" id="from" class="form-control" value="{{ $from }}">
                    </div>

                    <div class="col-md-3">
                        <label for="to" class="form-label">Sampai Tanggal</label>
                        <input type="date" name="to" id="to" class="form-control" value="{{ $to }}">
                    </div>

                    <div class="col-md-3">
                        <label for="group_by" class="form-label">Group By</label>
                        <select name="group_by" id="group_by" class="form-select">
                            <option value="monthly" {{ $groupBy == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="daily" {{ $groupBy == 'daily' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <a href="{{ route('dashboard.production.trend_by_product') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#compareModal">
                            <i class="bi bi-bar-chart-steps me-1"></i> Bandingkan Data
                        </button>
                    </div>
                    

                    <div class="col-md-3 d-flex align-items-end">
                        <a href="{{ route('produksi.export.pdf') . '?' . http_build_query(request()->query()) }}"
                        class="btn btn-success w-100">Download PDF</a>
                    </div>
                </form>
                <canvas id="trendByProductChart" height="100"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Ukuran (kg)</th>
                                <th>Kemasan</th>
                                <th>Jumlah Unit</th>
                                <th>Total Ton</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($breakdownData as $row)
                                <tr>
                                    <td>{{ $row->produk }}</td>
                                    <td>{{ $row->size }}</td>
                                    <td>{{ $row->kemasan }}</td>
                                    <td>{{ $row->total_unit }}</td>
                                    <td>{{ number_format($row->total_ton, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bandingkan -->
<div class="modal fade" id="compareModal" tabindex="-1" aria-labelledby="compareModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form method="GET" action="{{ route('dashboard.production.trend_by_product') }}">
        <div class="modal-header">
          <h5 class="modal-title" id="compareModalLabel">Bandingkan Tren Produksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Jenis Perbandingan</label>
              <select name="compare_mode" id="compare_mode" class="form-select" required>
                <option value="">Pilih...</option>
                <option value="product">Produk Lain</option>
                <option value="date">Periode Lain</option>
              </select>
            </div>

            <div class="col-md-6 compare-field compare-product d-none">
              <label class="form-label">Produk Pembanding</label>
              <select name="produk_compare" class="form-select">
                @foreach ($produkOptions as $produk)
                  @if($produk !== $selectedProduk)
                  <option value="{{ $produk }}">{{ $produk }}</option>
                  @endif
                @endforeach
              </select>
            </div>

            <div class="col-md-6 compare-field compare-date d-none">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" name="compare_from" class="form-control">
            </div>
            <div class="col-md-6 compare-field compare-date d-none">
              <label class="form-label">Tanggal Selesai</label>
              <input type="date" name="compare_to" class="form-control">
            </div>

            <!-- Hidden original filter values -->
            <input type="hidden" name="produk" value="{{ $selectedProduk }}">
            <input type="hidden" name="from" value="{{ $from }}">
            <input type="hidden" name="to" value="{{ $to }}">
            <input type="hidden" name="group_by" value="{{ $groupBy }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Terapkan Perbandingan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    const ctx = document.getElementById('trendByProductChart').getContext('2d');

    const mainDatasets = @json($datasets);
    const compareDatasets = @json($compareDatasets ?? []);
    const allDatasets = mainDatasets.concat(compareDatasets);

    // Definisikan warna statis (ubah atau tambah sesuai kebutuhan)
    const staticColors = [
        '#FF6384', // merah muda
        '#36A2EB', // biru
        '#FFCE56', // kuning
        '#4BC0C0', // cyan
        '#9966FF', // ungu
        '#FF9F40', // oranye
        '#8B0000', // merah gelap
        '#008000', // hijau
        '#000080', // biru navy
        '#A52A2A'  // coklat
    ];

    // Tambahkan warna ke setiap dataset
    allDatasets.forEach((dataset, index) => {
        const color = staticColors[index % staticColors.length]; // loop jika dataset > warna
        dataset.borderColor = color;
        dataset.backgroundColor = color + '43'; // 33 = transparansi ~20%
        dataset.fill = false; // agar tidak full fill area di bawah garis
        dataset.tension = 0.3; // buat garis agak melengkung (opsional)
        dataset.pointRadius = 3; // titik data
    });

    const trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: allDatasets
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Ton'
                    },
                    beginAtZero: true
                },
                x: {
                    title: {
                        display: true,
                        text: 'Periode'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Tren Produksi per Produk ({{ ucfirst($groupBy) }})'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>


@if (!empty($compareMode))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartContainer = document.getElementById('trendByProductChart').parentElement;
        const note = document.createElement('div');
        note.classList.add('alert', 'alert-info', 'mt-3');
        note.innerHTML = '<strong>Keterangan:</strong> {{ $compareLabel ?? "Data Pembanding" }}';
        chartContainer.appendChild(note);
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const compareSelect = document.getElementById('compare_mode');
        const fields = document.querySelectorAll('.compare-field');

        compareSelect.addEventListener('change', function () {
            fields.forEach(el => el.classList.add('d-none'));
            if (this.value === 'product') {
                document.querySelectorAll('.compare-product').forEach(el => el.classList.remove('d-none'));
            } else if (this.value === 'date') {
                document.querySelectorAll('.compare-date').forEach(el => el.classList.remove('d-none'));
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 2. Ambil data dari controller (di-render oleh Blade)
        const labels = @json($chartLabelskapasitas);
        const data = @json($chartDatakapasitasTon);
        const upperLimit = @json($nilaiBatasAtasKg);
        const dataPersen = @json($chartDataketercapaian);
        const upperLimitPercent = @json($persentaseBatasAtas);
        const MaxProd = @json($maxCapacity);

        const ctx = document.getElementById('productionLineChart').getContext('2d');
        
        // 3. Buat chart
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: `Kapasitas Maksimal (Ton)`,
                        data: Array(labels.length).fill(MaxProd), // Buat array dengan nilai yang sama
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderDash: [10, 5], // Buat garis putus-putus
                        pointRadius: 0, // Sembunyikan titik pada garis batas
                        fill: false,
                    },
                    {
                        label: 'Produksi Aktual (Ton)',
                        data: data,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        tension: 0.1,
                        fill: true,
                    },
                    {
                        label: `Batas Atas (${upperLimitPercent}%)`,
                        data: Array(labels.length).fill(upperLimit), // Buat array dengan nilai yang sama
                        borderColor: '#fdcb6e',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderDash: [10, 5], // Buat garis putus-putus
                        pointRadius: 0, // Sembunyikan titik pada garis batas
                        fill: false,
                    },
                    
                    {
                        label: 'Persentase Produksi (%)',
                        data: dataPersen,
                        type: 'line',
                        borderColor: '#55efc4',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        yAxisID: 'persen',
                        tension: 0.3,
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'dd MMM yyyy'
                        },
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Produksi (Kg)'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    });
</script>
@endsection

