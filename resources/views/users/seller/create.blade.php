@extends('layouts.vertical', ['title' => 'Distributor Add'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css', 'node_modules/nouislider/dist/nouislider.min.css'])
@endsection

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
    <div class="col-xl-12 col-lg-12 ">
        <form action="{{ route('distributor.store') }}" method="POST">
        @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Distributor Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="distributor-name" class="form-label">Distributor Name</label>
                                <input type="text" id="distributor-name" name="name" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label for="seller-email" class="form-label">Email</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text fs-20"><iconify-icon icon="solar:letter-bold-duotone" class="fs-18"></iconify-icon></span>
                                <input type="email" name="email" id="seller-email" class="form-control" placeholder="Add Email">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <label for="seller-number" class="form-label">Phone Number</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text fs-20"><iconify-icon icon="solar:outgoing-call-rounded-bold-duotone" class="fs-20"></iconify-icon></span>
                                <input type="number" name="phone" id="seller-number" class="form-control" placeholder="Phone number">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <select id="provinsi" name="provinsi" class="form-control">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="kabupaten" class="form-label">Kabupaten</label>
                                <select id="kabupaten" name="kabupaten" class="form-control">
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="distributor-name" class="form-label">Alamat Details</label>
                                <input type="text" id="distributor-name" name="alamat" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 bg-light mb-3 rounded">
                <div class="row justify-content-end g-2">
                    <div class="col-lg-2">
                        <button class="btn btn-outline-secondary w-100" type="submit">Save Change</button>
                    </div>
                    <div class="col-lg-2">
                        <a href="#!" class="btn btn-primary w-100">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script-bottom')
@vite(['resources/js/pages/app-ecommerce-seller-add.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    // Memuat provinsi ke dropdown
    $.ajax({
        url: 'https://api.binderbyte.com/wilayah/provinsi?api_key=e7248fdc0d879d071b229e61a22d3c1d71beb8bbbc796efb173222a83b129238',
        method: 'GET',
        success: function(response) {
            if (response.code === "200") {  // Checking the response code
                var provinsiData = response.value;  // Accessing the 'value' array
                // Mengisi dropdown provinsi
                provinsiData.forEach(function(provinsi) {
                    // Append the name as the value
                    $('#provinsi').append(new Option(provinsi.name, provinsi.id));
                });
            } else {
                alert('Gagal memuat provinsi');
            }
        },
        error: function() {
            alert('Terjadi kesalahan dalam memuat data provinsi');
        }
    });

    // Ketika provinsi dipilih, memuat kabupaten yang terkait
    $('#provinsi').on('change', function() {
        var provinsiName = $(this).val(); // Mendapatkan nama provinsi yang dipilih
        if (provinsiName) {
            $.ajax({
                url: 'https://api.binderbyte.com/wilayah/kabupaten?api_key=e7248fdc0d879d071b229e61a22d3c1d71beb8bbbc796efb173222a83b129238&id_provinsi=' + provinsiName,
                method: 'GET',
                success: function(response) {
                    if (response.code === "200") { // Checking the response code
                        var kabupatenData = response.value; // Corrected to 'value'
                        $('#kabupaten').empty(); // Kosongkan dropdown kabupaten
                        $('#kabupaten').append(new Option('Pilih Kabupaten', '')); // Tambahkan opsi default
                        // Mengisi dropdown kabupaten
                        kabupatenData.forEach(function(kabupaten) {
                            // Append the kabupaten name as the value
                            $('#kabupaten').append(new Option(kabupaten.name, kabupaten.id));
                        });
                    } else {
                        alert('Gagal memuat kabupaten');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan dalam memuat data kabupaten');
                }
            });
        } else {
            $('#kabupaten').empty().append(new Option('Pilih Kabupaten', ''));
        }
    });
});
</script>

@endsection