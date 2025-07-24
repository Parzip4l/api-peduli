@extends('layouts.vertical', ['title' => 'Penetrasi Data'])

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
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            Penetrasi Data
                        </h4>

                        <a href="#" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="bx bx-plus me-1"></i>Create Penetrasi
                        </a>
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
                                <th class="ps-3">Batch Number</th>
                                <th>Product</th>
                                <th>Penetrasi in Process</th>
                                <th>Keterangan</th>
                                <th>Penetrasi FNG</th>
                                <th>Keterangan FNG</th>
                                <th>Checker</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach($penetrasi as $data)
                                <tr>
                                    <td class="ps-3">{{$data->batch}}</td>
                                    <td>{{$data->product}}</td>
                                    <td>{{$data->p_process}}</td>
                                    <td>{{$data->k_process}}</td>
                                    <td>{{$data->p_fng}}</td>
                                    <td>{{$data->k_fng}}</td>
                                    <td>{{$data->checker}}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm">
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
                                Showing {{ $penetrasi->firstItem() }} to {{ $penetrasi->lastItem() }} of {{ $penetrasi->total() }} entries
                            </div>
                            <div class="">
                                {{ $penetrasi->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                            </div>
                        </div>
                    </tfoot>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penetrasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('rnd-check.store')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Batch Code</label>
                                            <input type="text" class="form-control" name="batch" required>    
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Produk</label>
                                            <select name="product" id="" class="form-control">
                                                <option value="Xtreme">Xtreme</option>
                                                <option value="Multi Purpose">Multi Purpose</option>
                                                <option value="Heavy Loader">Heavy Loader</option>
                                                <option value="Supreme">Supreme</option>
                                                <option value="Super">Super</option>
                                                <option value="Optima">Optima</option>
                                                <option value="Power">Power</option>
                                                <option value="Wheel">Wheel</option>
                                                <option value="Activ">Activ</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Penetrasi Proses</label>
                                            <input type="number" class="form-control" name="p_process" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Keterangan Penetrasi Proses</label>
                                            <input type="text" class="form-control" name="k_process" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Penetrasi Finish Goods</label>
                                            <input type="number" class="form-control" name="p_fng" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Keterangan Finish Goods</label>
                                            <input type="text" class="form-control" name="k_fng" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="" class="form-label">Checker</label>
                                            <select name="checker" class="form-control" id="">
                                                <option value="Sabdono Hadi">Sabdono Hadi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
        // Trigger an AJAX request on keyup event
            $('#search-input').on('keyup', function() {
                var search = $(this).val();  // Get the search input value
                var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

                $.ajax({
                    url: "{{ route('rnd-check.index') }}",  // Route for user list
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
                    url: "{{ route('rnd-check.index') }}",  // Route for user list
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

@endsection
