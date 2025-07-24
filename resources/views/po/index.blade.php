@extends('layouts.vertical', ['title' => 'Daftar Purchase Order'])

@section('content')
<div class="row">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">📦 List Purchase Order</h4>
        </div>
        <div class="card-body">
            <!-- Header & Filters -->
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="mb-2">
                    <input type="text" name="search" id="search-input" class="form-control" placeholder="Cari Purchase Order..." value="{{ request()->get('search') }}">
                </div>
                <div class="mb-2 d-flex flex-wrap gap-2">
                    <a href="{{ route('purchase_orders.export') }}" class="btn btn-success">Export CSV</a>
                    <button class="btn btn-outline-secondary">Date range</button>
                    <button class="btn btn-outline-secondary">Status</button>
                    <a href="{{route('purchase_orders.create')}}" class="btn btn-primary">+ Create Order</a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>No PO</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Produk</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Tanggal PO</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        @forelse($orders as $po)
                            <tr>
                                <td><input type="checkbox" /></td>
                                <td>{{ $po->po_number }}</td>
                                <td>
                                    @foreach ($po->items as $item)
                                        <div>{{ $item->warehouseItem->name ?? '-' }} {{ $item->warehouseItem->type ?? '-' }} <br> ({{ $item->quantity }})</div>
                                    @endforeach
                                </td>
                                <td>{{ $po->items->count() }} produk</td>
                                <td>{{ $po->distributor->name ?? '-' }}</td>
                                <td>
                                    <span class="badge 
                                        @if($po->status === 'received') bg-success 
                                        @elseif($po->status === 'partial') bg-warning 
                                        @elseif($po->status === 'rejected') bg-danger 
                                        @else bg-secondary @endif">
                                        {{ ucfirst($po->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($po->due_date)->format('d M Y') }}</td>
                                <td>Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('purchase_orders.show', $po->id) }}" class="btn btn-soft-secondary btn-sm">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="{{route('purchase_orders.edit', $po->id)}}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $po->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">Data Purchase Order kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} data
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
    // Trigger an AJAX request on keyup event
        $('#search-input').on('keyup', function() {
            var search = $(this).val();  // Get the search input value
            var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

            $.ajax({
                url: "{{ route('purchase_orders.index') }}",  // Route for user list
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
                url: "{{ route('purchase_orders.index') }}",  // Route for user list
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
