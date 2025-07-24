@extends('layouts.vertical', ['title' => 'Role List'])

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
                            List Role
                        </h4>

                        <a href="{{ route('role.create') }}" class="btn btn-sm btn-soft-primary">
                            <i class="bx bx-plus me-1"></i>Create Role
                        </a>
                    </div>
                </div>
                <!-- end card body -->

                <!-- Search Input -->
                <div class="mb-3 mx-3">
                    <label for="" class="mb-2">Search Data</label>
                    <input type="text" id="search-input" class="form-control" placeholder="Search by role name" value="{{ request()->get('search') }}">
                </div>

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach($role as $data)
                                <tr>
                                    <td class="ps-3">{{$data->name}}</td>
                                    <td>
                                        <a href="{{route('role.edit', $data->id)}}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $data->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    
                    <tfoot>
                        <div class="d-flex justify-content-between mx-3 mt-2 mb-2 ">
                            <div>
                                Showing {{ $role->firstItem() }} to {{ $role->lastItem() }} of {{ $role->total() }} entries
                            </div>
                            <div class="">
                            {{ $role->links('pagination::bootstrap-4') }}
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

            $.ajax({
                url: "{{ route('role.index') }}",  // Route for user list
                method: 'GET',
                data: { search: search },  // Send the search query
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
    function confirmDelete(roleId) {
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
                fetch('/role/' + roleId, {
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
                            'The role has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); // Muat ulang halaman untuk melihat perubahan
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to delete the role. Please try again.', // Menampilkan pesan error dari server
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
