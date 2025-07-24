@extends('layouts.vertical', ['title' => 'Data Lokasi'])

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
                            Data Lokasi
                        </h4>

                        <a href="#" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#ModalLokasi">
                            <i class="bx bx-plus me-1"></i>Buat Lokasi
                        </a>
                    </div>
                </div>
                <!-- end card body -->

                <!-- Search Input -->
                <div class="mb-3 mx-3">
                    <label for="" class="mb-2">Cari Data</label>
                    <input type="text" id="search-input" class="form-control" placeholder="Cari berdasarkan nama" value="{{ request()->get('search') }}">
                </div>

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Nama Lokasi</th>
                                <th>Kode Lokasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @php 
                                $no = 1;
                            @endphp
                            @foreach($lokasi as $data)
                            <tr>
                                <td class="ps-3">{{$no++}}</td>
                                <td>{{$data->nama_lokasi}}</td>
                                <td>{{$data->kode}}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-soft-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalLokasiUpdate{{ $data->id }}">
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
                                Showing {{ $lokasi->firstItem() }} to {{ $lokasi->lastItem() }} of {{ $lokasi->total() }} entries
                            </div>
                            <div class="">
                                {{ $lokasi->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                            </div>
                        </div>
                    </tfoot>
                </div>

            </div>

            <!-- Modal Tambah Data -->
            <div class="modal fade" id="ModalLokasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Lokasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('lokasi.store')}}" method="POST" id="ModalLokasi">
                                @csrf

                                <div class="mb-2">
                                    <label for="" class="form-label">Kode Lokasi</label>
                                    <input type="text" name="kode" class="form-control">
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Nama Lokasi</label>
                                    <input type="text" name="nama_lokasi" class="form-control">
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Simpan Data</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Data -->
            @foreach($lokasi as $data)
            <div class="modal fade" id="ModalLokasiUpdate{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update Data Lokasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('lokasi.update', $data->id)}}" method="POST" id="ModalLokasiUpdate{{$data->id}}">
                                @csrf
                                @method('PUT')

                                <div class="mb-2">
                                    <label for="" class="form-label">Kode Lokasi</label>
                                    <input type="text" name="kode" class="form-control" value="{{$data->kode}}">
                                </div>


                                <div class="mb-2">
                                    <label for="" class="form-label">Nama Lokasi</label>
                                    <input type="text" name="nama_lokasi" class="form-control" value="{{$data->nama_lokasi}}">
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Update Data</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
                    url: "{{ route('lokasi.index') }}",  // Route for user list
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
                    url: "{{ route('lokasi.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html()); 
                        $('.pagination').html($(response).find('.pagination').html()); 
                    }
                });
            });
        });
    </script>
    <script>
        function confirmDelete(menuId) {
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
                    fetch('/lokasi/' + menuId, {
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
                                'Data lokasi berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete Divisi. Please try again.', // Menampilkan pesan error dari server
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

@endsection
