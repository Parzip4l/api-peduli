@extends('layouts.vertical', ['title' => 'Penerimaan Oli Bulan Ini'])

@section('content')

<div class="row">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="col-xxl-5">
        <div class="row">
            
            <div class="col-12">
                <div class="tombol d-flex">
                    <a href="#" class="btn btn-primary mb-2 me-2" data-bs-toggle="modal" data-bs-target="#modalSettingOli">Setting Harga Oli</a>
                    <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalReportOli">Download Report</a>
                </div>
            </div>

            <!-- Modal Report Oli -->
            <div class="modal fade" id="modalReportOli" tabindex="-1" aria-labelledby="modalSettingOliLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSettingOliLabel">Pilih Bulan dan Tahun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('download.report') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-control" required>
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <input type="number" class="form-control" name="tahun" id="tahun" required min="2020" max="2099" value="{{ date('Y') }}">
                                </div>
                                <button type="submit" class="btn btn-success">Download Report</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalSettingOli" tabindex="-1" aria-labelledby="modalOli" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalOli">Setting Harga Oli</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('oli.update_all') }}" method="POST">
                                @csrf
                                @foreach($hargaOli as $dataOli)
                                <div class="form-group mb-2">
                                    <label for="oli_{{ $dataOli->id }}" class="form-label">{{ $dataOli->jenis_oli }}</label>
                                    <input type="number" name="oli[{{ $dataOli->id }}]" class="form-control" value="{{ $dataOli->harga }}" id="oli_{{ $dataOli->id }}">
                                    <p>Last Update: <span class="text-danger">{{ $dataOli->updated_at }}</span> By <b><i>{{ $dataOli->updated_by }}</i></b></p>
                                </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Update Harga Oli</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="avatar-md bg-soft-primary rounded">
                                    <iconify-icon icon="solar:fuel-outline"
                                                    class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-7 text-end">
                                <p class="text-muted mb-0 text-truncate">Oli Bahan</p>
                                <h3 class="text-dark mt-1 mb-0">{{ $totalOliCurrent['Bahan'] ?? 0 }} Drum</h3>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div> <!-- end card body -->
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                @php
                                    $change = $percentChange['Bahan'] ?? 0;
                                    $changeIcon = $change >= 0 ? 'bx bxs-up-arrow' : 'bx bxs-down-arrow';
                                    $changeColor = $change >= 0 ? 'text-success' : 'text-danger';
                                @endphp
                                <span class="{{ $changeColor }}"> 
                                    <i class="{{ $changeIcon }} fs-12"></i> {{ abs(round($change, 1)) }}%
                                </span>
                                <span class="text-muted ms-1 fs-12">Last Month</span>
                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
            <div class="col-md-6">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:fuel-bold"
                                class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0 text-truncate">Oli Trafo</p>
                                <h3 class="text-dark mt-1 mb-0">{{ $totalOliCurrent['Trafo'] ?? 0 }} Drum</h3>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div> <!-- end card body -->
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                            @php
                                $change = $percentChange['Trafo'] ?? 0;
                                $changeIcon = $change >= 0 ? 'bx bxs-up-arrow' : 'bx bxs-down-arrow';
                                $changeColor = $change >= 0 ? 'text-success' : 'text-danger';
                            @endphp
                            <div>
                                <span class="{{ $changeColor }}"> 
                                    <i class="{{ $changeIcon }} fs-12"></i> {{ abs(round($change, 1)) }}%
                                </span>
                                <span class="text-muted ms-1 fs-12">Last Month</span>
                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
            <div class="col-md-6">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:fuel-bold-duotone"
                                class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0 text-truncate">Oli Service</p>
                                <h3 class="text-dark mt-1 mb-0">{{ $totalOliCurrent['Service'] ?? 0 }} Drum</h3>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div> <!-- end card body -->
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                             @php
                                $change = $percentChange['Service'] ?? 0;
                                $changeIcon = $change >= 0 ? 'bx bxs-up-arrow' : 'bx bxs-down-arrow';
                                $changeColor = $change >= 0 ? 'text-success' : 'text-danger';
                            @endphp
                            <div>
                                <span class="{{ $changeColor }}"> 
                                    <i class="{{ $changeIcon }} fs-12"></i> {{ abs(round($change, 1)) }}%
                                </span>
                                <span class="text-muted ms-1 fs-12">Last Month</span>
                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
            <div class="col-md-6">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-md bg-soft-primary rounded">
                                    <iconify-icon icon="solar:fuel-broken"
                                    class="avatar-title fs-32 text-primary"></iconify-icon>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-6 text-end">
                                <p class="text-muted mb-0 text-truncate">Minarex</p>
                                <h3 class="text-dark mt-1 mb-0">{{ $totalOliCurrent['Minarex'] ?? 0 }} Drum</h3>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div> <!-- end card body -->
                    <div class="card-footer py-2 bg-light bg-opacity-50">
                        <div class="d-flex align-items-center justify-content-between">
                        @php
                                $change = $percentChange['Minarex'] ?? 0;
                                $changeIcon = $change >= 0 ? 'bx bxs-up-arrow' : 'bx bxs-down-arrow';
                                $changeColor = $change >= 0 ? 'text-success' : 'text-danger';
                            @endphp
                            <div>
                                <span class="{{ $changeColor }}"> 
                                    <i class="{{ $changeIcon }} fs-12"></i> {{ abs(round($change, 1)) }}%
                                </span>
                                <span class="text-muted ms-1 fs-12">Last Month</span>
                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end col -->

    <div class="col-xxl-7">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Received Oli Demographic</h4>
                    <div>
                        <a href="{{ route('oli.index', array_merge(request()->all(), ['filter' => '1M'])) }}" type="button" class="btn btn-sm btn-outline-light {{ $filter == '1M' ? 'active' : '' }}">1M</a>
                        <a href="{{ route('oli.index', array_merge(request()->all(), ['filter' => '6M'])) }}" type="button" class="btn btn-sm btn-outline-light {{ $filter == '6M' ? 'active' : '' }}">6M</a>
                        <a href="{{ route('oli.index', array_merge(request()->all(), ['filter' => '1Y'])) }}" type="button" class="btn btn-sm btn-outline-light {{ $filter == '1Y' ? 'active' : '' }}">1Y</a>
                    </div>
                </div> <!-- end card-title-->

                <div dir="ltr">
                    <div id="dash-performance-chart" class="apex-charts"></div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

</div> <!-- end row -->

<!-- Card Total -->
<div class="row">
    @foreach (['Trafo', 'Bahan', 'Service', 'Minarex'] as $jenis)
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="hugeicons:dollar-send-02"
                                              class="avatar-title fs-32 text-primary"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-8 text-end">
                            <p class="text-muted mb-0 text-truncate">Oli {{ $jenis }}</p>
                            <h3 id="total-{{ $jenis }}" class="text-dark mt-1 mb-0">Rp 0</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2 bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between">
                        @php
                            $change = $percentChange[$jenis] ?? 0;
                            $changeIcon = $change >= 0 ? 'bx bxs-up-arrow' : 'bx bxs-down-arrow';
                            $changeColor = $change >= 0 ? 'text-success' : 'text-danger';
                        @endphp
                        <span class="{{ $changeColor }}">
                            <i class="{{ $changeIcon }} fs-12"></i> {{ abs(round($change, 1)) }}%
                        </span>
                        <span class="text-muted ms-1 fs-12">Last Month</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">
                        Oli Data
                    </h4>
                    <div class="d-flex justify-content-end mx-3 mb-3">
                        <button id="toggle-table-btn" class="btn btn-primary btn-sm">Hide Table</button>
                    </div>
                </div>
            </div>
            <!-- end card body -->

            <!-- Search Input -->
            <div class="mb-3 mx-3">
                <label for="" class="mb-2">Search Data</label>
                <input type="text" id="search-input" class="form-control" placeholder="Search by menu name" value="{{ request()->get('search') }}">
            </div>

            <!-- Table -->
            <div id="table-search">
                <table class="table mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Pengirim</th>
                            <th>Jenis Oli</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        @foreach($oli as $data)
                            <tr>
                                <td class="ps-3"> {{ $data->tanggal }} </td>
                                <td> {{ $data->pengirim }} </td>
                                <td> {{ $data->jenis_oli }} </td>
                                <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                                <td> {{ $data->jumlah }} </td>
                                <td>Rp {{ number_format($data->total, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{route('oli.edit', $data->id)}}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $data->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <tfoot>
                    <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
                        <div>
                            Showing {{ $oli->firstItem() }} to {{ $oli->lastItem() }} of {{ $oli->total() }} entries
                        </div>
                        <div class="">
                            {{ $oli->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                        </div>
                    </div>
                </tfoot>
            </div>

        </div>

        <!-- end card -->
    </div>
    <!-- end col -->
</div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pass PHP array to JavaScript
        const chartData = @json($chartData);

        // Function to format date as 'YYYY-MM'
        const formatDateToMonth = (date) => {
            const d = new Date(date);
            const month = (d.getMonth() + 1).toString().padStart(2, '0');
            const year = d.getFullYear();
            return `${year}-${month}`;
        };

        // Format categories to group by month, but retain full date
        const formattedCategories = chartData.categories.map(date => new Date(date).toLocaleDateString('en-CA'));

        // Update series data without filtering by month
        const formattedSeries = chartData.series.map(series => ({
            ...series,
            data: series.data.map((value, index) => ({
                x: new Date(chartData.categories[index]).toLocaleDateString('en-CA'),
                y: value
            }))
        }));

        // Initial chart options
        const options = {
            series: formattedSeries,  // Use the formatted series data
            chart: {
                height: 313,
                type: "bar",
                stacked: true,
                toolbar: { show: false },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "30%",
                    barHeight: "70%",
                    borderRadius: 3,
                },
            },
            fill: {
                opacity: [1, 1, 1, 1],
                type: ['solid', 'solid', 'solid', 'solid'],
            },
            stroke: { width: 0 },
            xaxis: {
                categories: formattedCategories,  // Use the formatted categories (with full date)
                axisTicks: { show: false },
                axisBorder: { show: false },
            },
            yaxis: {
                min: 0,
                axisBorder: { show: false },
            },
            grid: {
                show: true,
                strokeDashArray: 3,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: -2, bottom: 0, left: 10 },
            },
            legend: {
                show: true,
                horizontalAlign: "center",
                offsetX: 0,
                offsetY: 5,
                markers: { width: 9, height: 9, radius: 6 },
                itemMargin: { horizontal: 10, vertical: 0 },
            },
            colors: ["#ff6c2f", "#22c55e", "#ffc512", "074799"],
            tooltip: {
                shared: true,
                intersect: false,
                y: [
                    { formatter: (y) => (typeof y !== "undefined" ? parseFloat(y.toFixed(1)) + " Drum" : y) },
                    { formatter: (y) => (typeof y !== "undefined" ? parseFloat(y.toFixed(1)) + " Drum" : y) },
                    { formatter: (y) => (typeof y !== "undefined" ? parseFloat(y.toFixed(1)) + " Drum" : y) },
                ],
            },
        };

        // Initialize the chart
        const chart = new ApexCharts(document.querySelector("#dash-performance-chart"), options);
        chart.render();
    </script>
    <script>
        $(document).ready(function() {
        // Trigger an AJAX request on keyup event
            $('#search-input').on('keyup', function() {
                var search = $(this).val();  // Get the search input value
                var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

                $.ajax({
                    url: "{{ route('oli.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the current page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                        $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                    }
                });
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                
                var page = $(this).attr('href').split('page=')[1];  // Extract the page number from the link
                var search = $('#search-input').val();  // Get the search input value

                $.ajax({
                    url: "{{ route('oli.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                        $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                    }
                });
            });
        });
    </script>

    <script>
        function confirmDelete(oliId) {
            // Tampilkan SweetAlert konfirmasi
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim permintaan AJAX untuk menghapus menu
                    fetch('/pencatatan-oli/' + oliId, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'Data has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete data. Please try again.', // Menampilkan pesan error dari server
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            error.message || 'An error occurred. Please try again.', // Menampilkan pesan error dari exception
                            'error'
                        );
                    });
                }
            });
        }
    </script><script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleTableButton = document.getElementById("toggle-table-btn");
        const tableSearch = document.getElementById("table-search");

        // Atur event listener untuk tombol
        toggleTableButton.addEventListener("click", function () {
            // Toggle visibility
            if (tableSearch.style.display === "none") {
                tableSearch.style.display = "block"; // Show table
                toggleTableButton.textContent = "Hide Table"; // Update button text
            } else {
                tableSearch.style.display = "none"; // Hide table
                toggleTableButton.textContent = "Show Table"; // Update button text
            }
        });
    });
</script>
<script>
    // Data dari backend (dikonversi ke JSON)
    const totals = @json($totalsThisMonth);

    // Fungsi untuk format rupiah singkat
    function formatRupiahSingkat(number) {
        if (number >= 1000000000) {
            return (number / 1000000000).toFixed(1) + ' Miliar';
        } else if (number >= 1000000) {
            return (number / 1000000).toFixed(1) + ' Juta';
        } else if (number >= 1000) {
            return (number / 1000).toFixed(1) + ' Ribu';
        } else {
            return number.toLocaleString('id-ID');
        }
    }

    // Update nilai di setiap card
    Object.keys(totals).forEach(jenis => {
        const element = document.getElementById(`total-${jenis}`);
        if (element) {
            element.textContent = `Rp ${formatRupiahSingkat(totals[jenis])}`;
        }
    });
</script>
@endsection
