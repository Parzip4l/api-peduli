@extends('layouts.vertical', ['title' => 'Maintenance Logs Data'])

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
                            Maintenance Logs Data
                        </h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="" class="mb-2">Search Data</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search by item and part" value="{{ request()->get('search') }}">
                        </div>
                    </div>
                </div>
                <!-- end card body -->
                 

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">Parent Item</th>
                                <th>Parts Name</th>
                                <th>Maintenance Date</th>
                                <th>Maintenence By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="maintenance-logs-body">
                            @php
                                $lastItemName = null; // Menyimpan item terakhir yang ditampilkan
                            @endphp
                            @foreach ($maintenanceLogs as $log)
                                @if ($log->item->name !== $lastItemName)
                                    <tr class="table-primary">
                                        <td class="ps-3" colspan="5"><strong>{{ $log->item->name ?? 'No Parent Item' }}</strong></td>
                                    </tr>
                                    @php
                                        $lastItemName = $log->item->name; // Update item terakhir yang ditampilkan
                                    @endphp
                                @endif
                                <tr>
                                    <td class="ps-3"></td> {{-- Kosongkan Parent Item karena sudah ditampilkan di atas --}}
                                    <td>{{ $log->part->name ?? 'No Part' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->performed_at)->format('d M Y') }}</td>
                                    <td>{{ $log->maintenanceBy->name ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-soft-info btn-sm view-maintenance" data-id="{{$log->id}}">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
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
                                Showing {{ $maintenanceLogs->firstItem() }} to {{ $maintenanceLogs->lastItem() }} of {{ $maintenanceLogs->total() }} entries
                            </div>
                            <div class="">
                                {{ $maintenanceLogs->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                            </div>
                        </div>
                    </tfoot>
                </div>

            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="maintenanceDetailCanvas">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Detail Maintenance</h5>
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
                url: "{{ route('maintenance.logs') }}",  // Route for user list
                method: 'GET',
                data: { 
                    search: search,  // Send the search query
                    status: status,  // Send the selected status
                    page: page       // Send the current page number
                },
                success: function(response) {
                    $('#maintenance-logs-body').html($(response).find('#maintenance-logs-body').html());  // Replace table body with filtered data
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
                url: "{{ route('maintenance.logs') }}",  // Route for user list
                method: 'GET',
                data: { 
                    search: search,  // Send the search query
                    status: status,  // Send the selected status
                    page: page       // Send the page number
                },
                success: function(response) {
                    $('#maintenance-logs-body').html($(response).find('#maintenance-logs-body').html());  // Replace table body with filtered data
                    $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".view-maintenance").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                let maintenanceId = this.getAttribute("data-id");
                
                let detailContainer = document.getElementById("maintenance-detail-content");
                detailContainer.innerHTML = "<p>Loading...</p>";

                fetch(`/maintenance/detail/${maintenanceId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    detailContainer.innerHTML = `
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Item:</strong> ${data.item_name}</p>
                        <p><strong>Part:</strong> ${data.part_name}</p>
                        <p><strong>Maintenance Check:</strong> ${data.checklist_item}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Catatan:</strong> ${data.notes}</p>
                        <p><strong>Dikerjakan oleh:</strong> ${data.maintenance_by}</p>
                        <p><strong>Waktu:</strong> ${data.performed_at}</p>
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
