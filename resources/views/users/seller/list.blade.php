@extends('layouts.vertical', ['title' => 'Distributor List'])

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
<div class="card">
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="mb-3 mt-3 mx-3">
                <label for="" class="mb-2">Search Data</label>
                <input type="text" id="search-input" class="form-control" placeholder="Search by distributor name" value="{{ request()->get('search') }}">
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="mb-3 mt-3 mx-3">
                <label for="" class="mb-2">Filter By Province</label>
                <select id="provinsi" name="provinsi" class="form-control">
                    <option value="">Pilih Provinsi</option>
                </select>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="mb-3 mt-3 mx-3">
                <label for="" class="mb-2">Filter By City</label>
                <select id="kabupaten" name="kabupaten" class="form-control">
                    <option value="">Pilih Kabupaten</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row" id="user-table-body">
    @foreach($distributor as $data)
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="position-relative bg-light p-2 rounded text-center">
                    <img src="/images/champortal.png" alt="" class="">
                </div>
                <div class="d-flex flex-wrap justify-content-between my-3">
                    <div>
                        <h4 class="mb-1">{{$data->name}}</h4>
                    </div>
                </div>
                <div class="">
                    <p class="d-flex align-items-center gap-2 mb-1"><iconify-icon icon="solar:point-on-map-bold-duotone" class="fs-18 text-primary"></iconify-icon>{{$data->address_details}}</p>
                    <p class="d-flex align-items-center gap-2 mb-1"><iconify-icon icon="solar:letter-bold-duotone" class="fs-18 text-primary"></iconify-icon>{{$data->email}}</p>
                    <p class="d-flex align-items-center gap-2 mb-0"><iconify-icon icon="solar:outgoing-call-rounded-bold-duotone" class="fs-20 text-primary"></iconify-icon>{{$data->phone}}</p>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 mb-1">
                    <p class="mb-0 fs-15 fw-medium text-dark">Total Purchase</p>
                    <div>
                        <p class="mb-0 fs-15 fw-medium text-dark">Rp. 200M <span class="ms-1"><iconify-icon icon="solar:course-up-outline" class="text-success"></iconify-icon></span></p>
                    </div>
                </div>
                <div class="progress progress-soft progress-md">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="p-2 pb-0 mx-n3 mt-2">
                    <div class="row text-center g-2">
                        <div class="col-lg-12 col-6">
                            <h5 class="mb-1">+4.5k</h5>
                            <p class="text-muted mb-0">Total Point</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top gap-1 hstack">
                <a href="{{route('distributor.show', $data->id)}}" class="btn btn-primary w-100">View</a>
                <a href="{{route('distributor.edit', $data->id)}}" class="btn btn-light w-100">Edit</a>
                <a href="#!" class="btn btn-danger w-100" onclick="confirmDelete({{ $data->id }})">Delete</a>
            </div>
        </div>
    </div>
    @endforeach
    <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
        <div>
            Showing {{ $distributor->firstItem() }} to {{ $distributor->lastItem() }} of {{ $distributor->total() }} entries
        </div>
        <div class="">
            {{ $distributor->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
        </div>
    </div>
</div>

@endsection

@section('script-bottom')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
        // Function to reload data with filters
        function loadDistributors() {
            var search = $('#search-input').val();  // Get the search input value
            var province = $('#provinsi').val();   // Get the selected province value
            var city = $('#kabupaten').val();      // Get the selected city value
            var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

            $.ajax({
                url: "{{ route('distributor.index') }}",  // Route for distributor list
                method: 'GET',
                data: { 
                    search: search,     // Send search query
                    provinsi: province, // Send selected province
                    kabupaten: city,    // Send selected city
                    page: page          // Send the current page number
                },
                success: function(response) {
                    $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                    $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                },
                error: function() {
                    alert('Gagal memuat data distributor.');  // Handle errors
                }
            });
        }

        // Trigger AJAX reload on keyup for search input
        $('#search-input').on('keyup', function() {
            loadDistributors();  // Reload data with current filters
        });

        // Trigger AJAX reload on province dropdown change
        $('#provinsi').on('change', function() {
            loadDistributors();  // Reload data with current filters
        });

        // Trigger AJAX reload on city dropdown change
        $('#kabupaten').on('change', function() {
            loadDistributors();  // Reload data with current filters
        });

        // Handle pagination click
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];  // Extract the page number from the link
            $('#page').val(page);  // Set the page value
            loadDistributors();  // Reload data with current filters
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
                    fetch('/distributor/' + menuId, {
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
                                'The menu has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete the menu. Please try again.', // Menampilkan pesan error dari server
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
        // Fungsi untuk memuat provinsi
        function loadProvinsi() {
            // Cek apakah data provinsi sudah ada di localStorage
            if (localStorage.getItem('provinsiData')) {
                var provinsiData = JSON.parse(localStorage.getItem('provinsiData')); // Ambil data dari localStorage
                populateProvinsiDropdown(provinsiData); // Isi dropdown dengan data dari localStorage
            } else {
                // Jika belum ada di localStorage, lakukan request API
                $.ajax({
                    url: 'https://api.binderbyte.com/wilayah/provinsi?api_key=e7248fdc0d879d071b229e61a22d3c1d71beb8bbbc796efb173222a83b129238',
                    method: 'GET',
                    success: function(response) {
                        if (response.code === "200") {
                            var provinsiData = response.value;
                            localStorage.setItem('provinsiData', JSON.stringify(provinsiData)); // Simpan ke localStorage
                            populateProvinsiDropdown(provinsiData); // Isi dropdown
                        } else {
                            alert('Gagal memuat provinsi');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan dalam memuat data provinsi');
                    }
                });
            }
        }

        // Fungsi untuk mengisi dropdown provinsi
        function populateProvinsiDropdown(provinsiData) {
            $('#provinsi').empty(); // Kosongkan dropdown
            $('#provinsi').append(new Option('Pilih Provinsi', '')); // Tambahkan opsi default
            provinsiData.forEach(function(provinsi) {
                $('#provinsi').append(new Option(provinsi.name, provinsi.id));
            });
        }

        // Fungsi untuk memuat kabupaten
        function loadKabupaten(provinsiId) {
            // Cek apakah data kabupaten untuk provinsi ini sudah ada di localStorage
            var kabupatenKey = `kabupatenData_${provinsiId}`;
            if (localStorage.getItem(kabupatenKey)) {
                var kabupatenData = JSON.parse(localStorage.getItem(kabupatenKey)); // Ambil data dari localStorage
                populateKabupatenDropdown(kabupatenData); // Isi dropdown kabupaten
            } else {
                // Jika belum ada di localStorage, lakukan request API
                $.ajax({
                    url: 'https://api.binderbyte.com/wilayah/kabupaten?api_key=e7248fdc0d879d071b229e61a22d3c1d71beb8bbbc796efb173222a83b129238&id_provinsi=' + provinsiId,
                    method: 'GET',
                    success: function(response) {
                        if (response.code === "200") {
                            var kabupatenData = response.value;
                            localStorage.setItem(kabupatenKey, JSON.stringify(kabupatenData)); // Simpan ke localStorage
                            populateKabupatenDropdown(kabupatenData); // Isi dropdown
                        } else {
                            alert('Gagal memuat kabupaten');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan dalam memuat data kabupaten');
                    }
                });
            }
        }

        // Fungsi untuk mengisi dropdown kabupaten
        function populateKabupatenDropdown(kabupatenData) {
            $('#kabupaten').empty(); // Kosongkan dropdown
            $('#kabupaten').append(new Option('Pilih Kabupaten', '')); // Tambahkan opsi default
            kabupatenData.forEach(function(kabupaten) {
                $('#kabupaten').append(new Option(kabupaten.name, kabupaten.id));
            });
        }

        // Event handler ketika provinsi dipilih
        $('#provinsi').on('change', function() {
            var provinsiId = $(this).val(); // Dapatkan ID provinsi yang dipilih
            if (provinsiId) {
                loadKabupaten(provinsiId); // Muat data kabupaten
            } else {
                $('#kabupaten').empty().append(new Option('Pilih Kabupaten', '')); // Kosongkan dropdown jika tidak ada provinsi dipilih
            }
        });

        // Panggil fungsi untuk memuat provinsi saat halaman dimuat
        loadProvinsi();
    });

    </script>
@endsection