@extends('layouts.vertical', ['title' => 'Maintenance Item List'])

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
                            Maintenance Item Data
                        </h4>

                        <a href="{{ route('maintenance.item.create') }}" class="btn btn-sm btn-soft-primary">
                            <i class="bx bx-plus me-1"></i>Create Data
                        </a>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="" class="mb-2">Search Data</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search by item name" value="{{ request()->get('search') }}">
                        </div>
                    </div>
                </div>
                <!-- end card body -->
                 

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Parts</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach ($items as $item)
                            <tr>
                                <td class="ps-3">{{ $item->name }}</td>
                                <td><a href="#!" class="text-primary view-maintenance" data-id="{{$item->id}}">{{ $item->total_parts }} Parts</a></td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('maintenance.item.edit', $item->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <!-- <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $item->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a> -->
                                        <a href="{{ route('maintenance.items.download_qr', $item->id) }}" class="btn btn-soft-success btn-sm">
                                            <iconify-icon icon="solar:qr-code-line-duotone" class="align-middle fs-18"></iconify-icon>
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
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} entries
                            </div>
                            <div class="">
                                {{ $items->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                            </div>
                        </div>
                    </tfoot>
                </div>

            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="maintenanceDetailCanvas">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Detail Maintenance Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <div id="maintenance-detail-content">
                        <p>Loading...</p>
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
        // Trigger an AJAX request on keyup event for search input
        $('#search-input, #status').on('change keyup', function() {
            var search = $('#search-input').val();  // Get the search input value
            var status = $('#status').val();  // Get the selected status
            var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

            $.ajax({
                url: "{{ route('maintenance.item.index') }}",  // Route for user list
                method: 'GET',
                data: { 
                    search: search,  // Send the search query
                    status: status,  // Send the selected status
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
            var status = $('#status').val();  // Get the selected status

            $.ajax({
                url: "{{ route('maintenance.item.index') }}",  // Route for user list
                method: 'GET',
                data: { 
                    search: search,  // Send the search query
                    status: status,  // Send the selected status
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
    function confirmDelete(ItemID) {
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
                fetch('/maintenance/item/' + ItemID, {
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
                            data.message || 'Failed to delete Data. Please try again.', // Menampilkan pesan error dari server
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
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".view-maintenance").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                let maintenanceId = this.getAttribute("data-id");
                
                let detailContainer = document.getElementById("maintenance-detail-content");
                detailContainer.innerHTML = "<p>Loading...</p>";

                fetch(`/maintenance/item/${maintenanceId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    detailContainer.innerHTML = `
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Item:</strong> ${data.name}</p>
                        <p><strong>Parts:</strong></p>
                        <ul id="partsList">
                            ${data.parts.map(part => `
                                <li><strong>${part.name}</strong> - Backup Stock: ${part.backup_stock}</li>
                            `).join('')}
                        </ul>
                    `;
                })
                .catch(error => {
                    console.error("Error fetching maintenance data:", error);
                    detailContainer.innerHTML = `<p class='text-danger'>Gagal mengambil data! (${error.message})</p>`;
                });


                let maintenanceOffcanvas = new bootstrap.Offcanvas(document.getElementById("maintenanceDetailCanvas"));
                maintenanceOffcanvas.show();
            });
        });
    });
</script>

@endsection
