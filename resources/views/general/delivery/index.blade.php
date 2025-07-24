@extends('layouts.vertical', ['title' => 'Delivery Order List'])

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
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Delivery Success</h4>
                        <p id="oliBahanTotal" class="text-muted fw-medium fs-22 mb-0">{{ $deliverySuccessCount }}</p> <!-- Display Delivery Success count -->
                    </div>
                    <div>
                        <div class="avatar-md bg-success bg-opacity-10 rounded">
                            <iconify-icon icon="solar:check-square-bold" class="fs-32 text-success avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Delivery On Process</h4>
                        <p id="oliServiceTotal" class="text-muted fw-medium fs-22 mb-0">{{ $onProcessCount }}</p> <!-- Display On Process count -->
                    </div>
                    <div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <iconify-icon icon="solar:delivery-bold" class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Delivery Delayed</h4>
                        <p id="oliTrafoTotal" class="text-muted fw-medium fs-22 mb-0">{{ $delayedCount }}</p> <!-- Display Delayed count -->
                    </div>
                    <div>
                        <div class="avatar-md bg-danger bg-opacity-10 rounded">
                            <iconify-icon icon="solar:clock-circle-bold" class="fs-32 text-danger avatar-title"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-2">Not Delivered</h4>
                        <p id="oliTrafoTotal" class="text-muted fw-medium fs-22 mb-0">{{ $notDeliveredCount }}</p> <!-- Display Not Delivered count -->
                    </div>
                    <div>
                        <div class="avatar-md bg-secondary bg-opacity-10 rounded">
                            <iconify-icon icon="solar:bag-cross-bold" class="fs-32 text-secondary avatar-title"></iconify-icon>
                        </div>
                    </div>
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
                            Delivery Order Data
                        </h4>

                        <a href="{{ route('delivery-order.create') }}" class="btn btn-sm btn-soft-primary">
                            <i class="bx bx-plus me-1"></i>Create Data
                        </a>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="" class="mb-2">Search Data</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search by menu customer" value="{{ request()->get('search') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="mb-2">Filter By Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Choose a Status</option>
                                <option value="Delivered" {{ request()->get('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Delayed" {{ request()->get('status') == 'Delayed' ? 'selected' : '' }}>Delayed</option>
                                <option value="On Process" {{ request()->get('status') == 'On Process' ? 'selected' : '' }}>On Process</option>
                                <option value="Cancel" {{ request()->get('status') == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
                 

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">Distributor / Customer</th>
                                <th>Produk</th>
                                <th>Total Order</th>
                                <th>Tanggal Kirim</th>
                                <th>Total Kirim</th>
                                <th>Ekspedisi</th>
                                <th>Sisa Kiriman</th>
                                <th>Status</th>
                                <th>Sales</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach($listorder as $order)
                                @php $previousDistributor = null; @endphp <!-- Initialize a variable to track the previous distributor -->
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <!-- Check if the distributor is the same as the previous one -->
                                        <td class="ps-3 text-primary" style="padding-left: {{ $previousDistributor === $order->distributor->name ? '30px' : '0px' }}">
                                            @if($previousDistributor !== $order->distributor->name)
                                                {{ $order->distributor->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->total_order }}</td>
                                        <td>{{ $item->tanggal_kirim ? \Carbon\Carbon::parse($item->tanggal_kirim)->format('d M Y') : 'N/A' }}</td>
                                        <td>{{ $item->jumlah_kirim }}</td>
                                        <td>{{ $order->ekspedisi ?? 'N/A' }}</td>
                                        <td>{{ $item->sisa_belum_kirim ?? '0' }}</td>
                                        <td>{{ $order->status ?? 'N/A' }}</td>
                                        <td>{{ $item->sales ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('delivery-order.edit', $order->id) }}" class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $order->id }})">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $previousDistributor = $order->distributor->name; @endphp <!-- Update previous distributor after each iteration -->
                                @endforeach
                            @endforeach
                        </tbody>

                    </table>
                    <tfoot>
                        <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
                            <div>
                                Showing {{ $listorder->firstItem() }} to {{ $listorder->lastItem() }} of {{ $listorder->total() }} entries
                            </div>
                            <div class="">
                                {{ $listorder->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
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
        // Trigger an AJAX request on keyup event for search input
        $('#search-input, #status').on('change keyup', function() {
            var search = $('#search-input').val();  // Get the search input value
            var status = $('#status').val();  // Get the selected status
            var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

            $.ajax({
                url: "{{ route('delivery-order.index') }}",  // Route for user list
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
                url: "{{ route('delivery-order.index') }}",  // Route for user list
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
                    fetch('/delivery-order/' + menuId, {
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

@endsection
