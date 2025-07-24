@extends('layouts.vertical', ['title' => 'Warehouse Item List'])

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
<div class="mb-3">
    <a href="{{route('warehouse.items.create')}}" class="btn btn-primary">Create Data</a>
</div>
<div class="card">
    <form method="GET" id="filterForm">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="mb-3 mt-3 mx-3">
                    <label for="location" class="mb-2">Filter By Warehouse Location</label>
                    <select id="location" name="location" class="form-control">
                        <option value="">Pilih Warehouse Location</option>
                        @foreach(App\Models\Warehouse\WarehouseLocation::all() as $loc)
                            <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="mb-3 mt-3 mx-3">
                    <label for="category" class="mb-2">Filter By Category</label>
                    <select id="category" name="category" class="form-control">
                        <option value="">Pilih Kategori</option>
                        <option value="RMA" {{ request('category') == 'RMA' ? 'selected' : '' }}>Raw Material</option>
                        <option value="PCK" {{ request('category') == 'PCK' ? 'selected' : '' }}>Packaging</option>
                        <option value="FNG" {{ request('category') == 'FNG' ? 'selected' : '' }}>Finish Goods</option>
                    </select>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="mb-3 mt-3 mx-3">
                    <label for="search" class="mb-2">Cari Nama Item</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Cari item..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 text-end px-4 mb-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('warehouse.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>
<div class="row" id="user-table-body">
    @foreach($items as $data)
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="position-relative bg-light p-2 rounded text-center">
                    <img src="/images/champortal.png" alt="" class="img-fluid" style="max-height: 100px;">
                </div>
                <div class="d-flex flex-wrap justify-content-between my-3">
                    <div>
                        <h5 class="mb-1">{{ $data->name }} ({{ $data->code }}) </h5>
                        @if($data->stocks->sum('quantity') < $data->minimum_qty)
                            <div class="text-danger small mb-2 text-end">Stok kurang dari minimum!</div>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Name</span>
                        <strong>{{ $data->name ?? '-' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Type</span>
                        <strong>{{ $data->type ?? '-' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Kategori</span>
                        <strong>{{ $data->category }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>UoM</span>
                        <strong>{{ $data->unit }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Stok Barang</span>
                        <strong class="{{ $data->stocks->sum('quantity') < $data->minimum_qty ? 'text-danger' : '' }}">
                            {{ $data->stocks->sum('quantity') }} {{ $data->unit }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Minimum Stock</span>
                        <strong>{{ $data->minimum_qty }} {{ $data->unit }}</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top gap-1 hstack">
                <a href="{{ route('warehouse.items.show', $data->id) }}" class="btn btn-primary w-100">View</a>
                <a href="{{ route('warehouse.items.edit', $data->id) }}" class="btn btn-light w-100">Edit</a>
            </div>
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
        <div>
            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} entries
        </div>
        <div class="">
            {{ $items->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection

@section('script-bottom')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('change', '#filterForm select, #filterForm input', function () {
            $('#filterForm').submit();
        });

        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            let url = $(this).attr('action') || window.location.href;
            $.get(url, $(this).serialize(), function (data) {
                $('#locationsList').html(data);
            });
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            $.get(url, $('#filterForm').serialize(), function (data) {
                $('#locationsList').html(data);
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
@endsection