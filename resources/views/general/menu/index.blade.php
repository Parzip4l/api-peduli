@extends('layouts.vertical', ['title' => 'Menu List'])

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
                            List Menu
                        </h4>

                        <a href="{{ route('menu.create') }}" class="btn btn-sm btn-soft-primary">
                            <i class="bx bx-plus me-1"></i>Create Menu
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
                                <th class="ps-3">Menu</th>
                                <th>Is Active</th>
                                <th>Menu Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach($menu as $data)
                                @if($data->parent_id == null)
                                    <!-- Parent Menu -->
                                    <tr>
                                        <td class="ps-3">
                                            <a href="">{{ $data->title }}</a>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="is_active" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                                                    data-id="{{ $data->id }}" {{ $data->is_active == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>{{$data->order}}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{route('menu.edit', $data->id)}}" class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $data->id }})">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Menampilkan Child Menu dengan Margin Kiri -->
                                    @foreach($menu->where('parent_id', $data->id) as $child)
                                        <tr>
                                            <td class="ps-3" style="padding-left: 40px!important;"> <!-- Memberikan margin kiri untuk child -->
                                                <a href="" class="text-secondary">{{ $child->title }}</a>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="is_active" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                                                        data-id="{{ $child->id }}" {{ $child->is_active == 1 ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>{{$child->order}}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{route('menu.edit', $child->id)}}" class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $child->id }})">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <tfoot>
                        <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
                            <div>
                                Showing {{ $menu->firstItem() }} to {{ $menu->lastItem() }} of {{ $menu->total() }} entries
                            </div>
                            <div class="">
                                {{ $menu->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
        // Trigger an AJAX request on keyup event
            $('#search-input').on('keyup', function() {
                var search = $(this).val();  // Get the search input value
                var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

                $.ajax({
                    url: "{{ route('menu.index') }}",  // Route for user list
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
                    url: "{{ route('menu.index') }}",  // Route for user list
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
        document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                let isActive = this.checked ? 1 : 0; // Tentukan nilai 1 jika checked, 0 jika unchecked
                let id = this.getAttribute('data-id'); // Ambil ID data yang akan diubah

                // Kirim request AJAX
                fetch('/update-status/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ is_active: isActive })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Status updated successfully');
                        // Reload halaman setelah status diperbarui
                        location.reload();
                    } else {
                        console.log('Error updating status', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
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
                    fetch('/menu/' + menuId, {
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
                                'The distributor has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete distributor. Please try again.', // Menampilkan pesan error dari server
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
