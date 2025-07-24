@extends('layouts.vertical', ['title' => 'Log Data Research Grease'])

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" id="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            Reminder Schedule Next Research
                        </h4>
                        <a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm" id="toggle-table-btn">Hide Table</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered" id="reminder-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Improvement Ideas</th>
                                    <th scope="col">Improvement Schedule</th>
                                    <th scope="col">Formulator</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $no = 1;
                                @endphp
                                @foreach($reminder as $dataR)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$dataR->improvement_ideas}}</td>
                                    <td>{{$dataR->improvement_schedule}}</td>
                                    <td>{{$dataR->created_by}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            Research Data
                        </h4>

                        <div class="data-button d-flex">
                            <a href="javascript:void(0);" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                                <i class="bx bx-file me-1"></i>Generate Report
                            </a>
                            <a href="{{route('log-riset-grease.create')}}" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i>Buat Data Riset
                            </a>
                        </div>

                        <!-- Modal Report -->
                        <div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="generateReportModalLabel">Pilih Rentang Waktu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="generateReportForm" action="{{ route('generate.report') }}" method="POST" target="_blank">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="monthRange" class="form-label">Pilih Rentang Bulan</label>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <select id="startMonth" class="form-control me-2" name="startMonth">
                                                            <option value="">Bulan Mulai</option>
                                                            @foreach(range(1, 12) as $month)
                                                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <select id="startYear" class="form-control me-2" name="startYear">
                                                            <option value="">Tahun Mulai</option>
                                                            @foreach(range(date('Y') - 5, date('Y')) as $year)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <select id="endMonth" class="form-control me-2" name="endMonth">
                                                            <option value="">Bulan Akhir</option>
                                                            @foreach(range(1, 12) as $month)
                                                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select id="endYear" class="form-control" name="endYear">
                                                            <option value="">Tahun Akhir</option>
                                                            @foreach(range(date('Y') - 5, date('Y')) as $year)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-success w-100">Generate Report</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end card body -->

                <!-- Search Input -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 mx-3">
                            <label for="" class="mb-2">Search Data</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search by product name or batch number" value="{{ request()->get('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 mx-3">
                            <label for="" class="mb-2">Month</label>
                            <select id="month-filter" class="form-control">
                                <option value="">-- Select Month --</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ request()->get('month') == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 mx-3">
                            <label for="" class="mb-2">Year</label>
                            <select id="year-filter" class="form-control">
                                <option value="">-- Select Year --</option>
                                @foreach(range(date('Y') - 5, date('Y')) as $year)
                                    <option value="{{ $year }}" {{ request()->get('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div id="table-search" class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">Batch Code</th>
                                <th>Tanggal</th>
                                <th>Produk Name</th>
                                <th>Expected Date Research</th>
                                <th>Created  By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="riset-table-body">
                        @if($data->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">No research data in your selected date</td>
                            </tr>
                        @else
                            @foreach($data as $data)
                                <tr>
                                    <td class="ps-3" style="text-transform:uppercase">{{$data->batch_code}}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d F Y') }}</td>
                                    <td>{{$data->product_name}}</td>
                                    <td>{{$data->expected_end_date}}</td>
                                    <td>{{$data->created_by}}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{route('log-riset-grease.edit', $data->id)}}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="{{route('log-riset-grease.show', $data->id)}}" class="btn btn-soft-secondary btn-sm">
                                                <iconify-icon icon="mdi:eye" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $data->id }})">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <tfoot>
                        
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Trigger an AJAX request on keyup event for search input
            $('#search-input').on('keyup', function() {
                var search = $(this).val();
                var month = $('#month-filter').val();
                var year = $('#year-filter').val();
                var page = $('.pagination .active a').text() || 1;

                $.ajax({
                    url: "{{ route('log-riset-grease.index') }}",
                    method: 'GET',
                    data: { 
                        search: search,
                        month: month,
                        year: year,
                        page: page
                    },
                    success: function(response) {
                        $('#riset-table-body').html($(response).find('#riset-table-body').html());
                        $('.pagination').html($(response).find('.pagination').html());
                    }
                });
            });

            // Handle month and year filter change
            $('#month-filter, #year-filter').on('change', function() {
                var search = $('#search-input').val();
                var month = $('#month-filter').val();
                var year = $('#year-filter').val();
                var page = 1;

                $.ajax({
                    url: "{{ route('log-riset-grease.index') }}",
                    method: 'GET',
                    data: { 
                        search: search,
                        month: month,
                        year: year,
                        page: page
                    },
                    success: function(response) {
                        $('#riset-table-body').html($(response).find('#riset-table-body').html());
                        $('.pagination').html($(response).find('.pagination').html());
                    }
                });
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();

                var page = $(this).attr('href').split('page=')[1];
                var search = $('#search-input').val();
                var month = $('#month-filter').val();
                var year = $('#year-filter').val();

                $.ajax({
                    url: "{{ route('log-riset-grease.index') }}",
                    method: 'GET',
                    data: { 
                        search: search,
                        month: month,
                        year: year,
                        page: page
                    },
                    success: function(response) {
                        $('#riset-table-body').html($(response).find('#riset-table-body').html());
                        $('.pagination').html($(response).find('.pagination').html());
                    }
                });
            });
        });
    </script>
    <script>
        function confirmDelete(RisetId) {
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
                    fetch('/log-riset-grease/' + RisetId, {
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
                                'The Riset has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete Riset. Please try again.', // Menampilkan pesan error dari server
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
    </script>
    <script>
       $(document).ready(function() {
            // Set up initial visibility and height of the table container
            $('#reminder-table').css({
                'transition': 'height 0.5s ease, opacity 0.5s ease',
                'height': 'auto',
                'opacity': 1,
                'visibility': 'visible',
                'overflow': 'hidden'
            });

            // Toggle table visibility and collapse with accordion effect
            $('#toggle-table-btn').on('click', function() {
                var table = $('#reminder-table'); // Get the table element
                var cardBody = $('#card-body'); // Get the card body that holds the table
                var button = $(this); // Get the button element

                if (table.css('opacity') == 1) {
                    // Collapse the table and hide it with a smooth transition
                    cardBody.css({
                        'height': '80px', // Only the header area remains visible
                        'overflow': 'hidden'
                    });
                    table.css({
                        'opacity': 0,
                        'visibility': 'hidden'
                    });

                    button.text('Show Table'); // Change the button text to "Show Table"
                } else {
                    // Expand the table and make it visible with a smooth transition
                    cardBody.css({
                        'height': 'auto', // Reset to the natural height of the content
                        'overflow': 'visible'
                    });
                    table.css({
                        'opacity': 1,
                        'visibility': 'visible'
                    });

                    button.text('Hide Table'); // Change the button text to "Hide Table"
                }
            });
        });
    </script>
@endsection
