@extends('layouts.vertical', ['title' => 'Production Batch Data'])

@section('content')

<div class="row">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row mb-2">
    <div class="col-md-6">
        <a href="{{route('dashboard.production.trend_by_product')}}" class="btn btn-primary w-100" target="_blank">Buka Dashboard Produksi</a>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="" data-bs-toggle="modal" data-bs-target="#forecastmodal" class="btn btn-primary btn-sm">Create Forecast</a>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('production_batches.create')}}" class="btn btn-primary btn-sm">Create Production</a>
                    </div>
                </div>
            </div>
            <!-- end card body -->

            <!-- Search Input -->
            <div class="mb-3 mx-3">
                <label for="" class="mb-2">Production Batch Data</label>
                <input type="text" id="search-input" class="form-control" placeholder="Search by menu name" value="{{ request()->get('search') }}">
            </div>
            <!-- Hamburger untuk toggle filter -->
            <div class="d-flex justify-content-start align-items-center mb-2 px-3">
                <button id="toggleColumnFilter" class="btn btn-outline-secondary">
                    ☰ Filter Kolom
                </button>
            </div>

            <!-- Panel Filter Kolom -->
            <div id="columnFilterPanel" class="bg-light border rounded p-3 mb-3 mx-3 d-none">
                <label class="fw-bold d-block mb-2">Tampilkan Kolom:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input toggle-column" type="checkbox" value="0" id="colTanggal" checked>
                    <label class="form-check-label" for="colTanggal">Tanggal</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input toggle-column" type="checkbox" value="1" id="colBatchCode" checked>
                    <label class="form-check-label" for="colBatchCode">Batch Code</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input toggle-column" type="checkbox" value="2" id="colProduk" checked>
                    <label class="form-check-label" for="colProduk">Produk</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input toggle-column" type="checkbox" value="3" id="colStatus" checked>
                    <label class="form-check-label" for="colStatus">Status</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input toggle-column" type="checkbox" value="4" id="colHasil" checked>
                    <label class="form-check-label" for="colHasil">Hasil</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input toggle-column" type="checkbox" value="5" id="colAction" checked>
                    <label class="form-check-label" for="colAction">Action</label>
                </div>
            </div>
            <!-- Table -->
            <div id="table-search" class="table-responsive">
                <table class="table mb-0" id="myTable">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Batch Code</th>
                            <th>Produk</th>
                            <th>Status Produksi</th>
                            <th>Hasil Produksi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        @foreach($batches as $data)
                            <tr>
                                <td class="ps-3"> {{ $data->created_at }} </td>
                                <td> {{ $data->batch_code }} </td>
                                <td> {{ $data->produk }} </td>
                                <td> {{ $data->status }} </td>
                                <td> {{ $data->hasil_status }} </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('production_batches.edit', $data->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        @if($data->status == 'Open')
                                        <a href="#" class="btn btn-soft-success btn-sm" data-bs-toggle="modal" data-bs-target="#finishProductionModal" data-batch-id="{{ $data->id }}">
                                            <iconify-icon icon="solar:clipboard-check-linear" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $data->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        @endif
                                        <a href="{{ route('production_batches.show', $data->id) }}" class="btn btn-soft-primary btn-sm">
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
                            Showing {{ $batches->firstItem() }} to {{ $batches->lastItem() }} of {{ $batches->total() }} entries
                        </div>
                        <div class="">
                            {{ $batches->links('pagination::bootstrap-4') }} 
                        </div>
                    </div>
                </tfoot>
            </div>

        </div>
        <!-- Modal Forecast -->
        <div class="modal fade" id="forecastmodal" tabindex="-1" aria-labelledby="forecastmodalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forecastmodalLabel">Buat Forecast</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="forecastform" method="POST" action="{{ route('production_batches.forecast') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="produk" class="form-label">Produk</label>
                                <select name="produk" id="produk" class="form-select" required>
                                    <option value="">-- Pilih Produk --</option>
                                    <option value="Multipurpose">Multipurpose</option>
                                    <option value="Xtreme">Xtreme</option>
                                    <option value="Heavy Loader">Heavy Loader</option>
                                    <option value="Supreme">Supreme</option>
                                    <option value="F300">F300</option>
                                    <option value="Super">Super</option>
                                    <option value="Optima">Optima</option>
                                    <option value="Wheel Power">Wheel Power</option>
                                    <option value="Wheel Active">Wheel Active</option>
                                </select>
                            </div>

                            <div id="packaging-container">
                                <div class="row mb-3 packaging-item">
                                    <div class="col-md-4">
                                        <label class="form-label">Packaging</label>
                                        <select name="packagings[]" class="form-select" required>
                                            <option value="">-- Pilih Packaging --</option>
                                            <option value="drum">Drum</option>
                                            <option value="pail">Pail</option>
                                            <option value="pot">Pot</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Size (Kg)</label>
                                        <input type="number" name="sizes[]" class="form-control" min="0" step="0.01" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" name="quantities[]" class="form-control" min="1" required>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-packaging">X</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" id="add-packaging" class="btn btn-secondary btn-sm">+ Tambah Packaging</button>
                            </div>

                            <button type="submit" class="btn btn-primary">Buat Forecast</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal untuk Menyelesaikan Produksi -->
        <div class="modal fade" id="finishProductionModal" tabindex="-1" aria-labelledby="finishProductionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Selesaikan Produksi - Batch ID: <span id="batchId"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="finishProductionForm" method="POST" action="{{ route('production_batches.finish') }}">
                            @csrf
                            <input type="hidden" id="finish_batch_id" name="batch_id">

                            <div class="mb-3">
                                <label for="hasil_status" class="form-label">Hasil Produksi</label>
                                <select class="form-select" id="hasil_status" name="hasil_status">
                                    <option value="OK">Ok</option>
                                    <option value="BS">BS</option>
                                </select>
                            </div>

                            <div id="packaging-container2">
                                <div class="packaging-group2 row g-2 mb-2">
                                    <div class="col-md-4">
                                        <select class="form-select" name="packaging[]">
                                            <option value="drum">Drum</option>
                                            <option value="pail">Pail</option>
                                            <option value="pot">Pot</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="size[]" placeholder="Size (Kg)" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="quantity[]" placeholder="Qty" required>
                                    </div>
                                    <div class="col-md-2 d-grid">
                                        <button type="button" class="btn btn-danger remove-packaging2">×</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-secondary" id="addPackaging2">+ Tambah Packaging</button>
                            </div>

                            <button type="submit" class="btn btn-primary">Selesaikan Produksi</button>
                        </form>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
        // Trigger an AJAX request on keyup event
            $('#search-input').on('keyup', function() {
                var search = $(this).val();  // Get the search input value
                var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

                $.ajax({
                    url: "{{ route('production_batches.index') }}",  // Route for user list
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
                    url: "{{ route('production_batches.index') }}",  // Route for user list
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
        function confirmDelete(BatchId) {
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
                    fetch('/production_batches/' + BatchId, {
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
                                data.message || 'Failed to delete data. Please try again.', // Menampilkan pesan error dari server
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
            const toggleTableButton = document.getElementById("toggle-table-btn");
            const tableSearch = document.getElementById("table-search");

            // Atur event listener untuk tombol
            toggleTableButton.addEventListener("click", function () {
                // Toggle visibility
                if (tableSearch.style.display === "none") {
                    tableSearch.style.display = "block"; // Show table
                    toggleTableButton.textContent = "Hide Table"; // Update button text
                } else {
                    tableSearch.style.display = "none"; // Hide table
                    toggleTableButton.textContent = "Show Table"; // Update button text
                }
            });
        });
    </script>
    <script>
        function openFinishProductionModal(batchId) {
            // Temukan batch berdasarkan ID
            var batchData = batchesData.find(batch => batch.id === batchId);

            if (batchData) {
                // Isi form dengan data batch yang sesuai
                document.getElementById('finish_batch_id').value = batchData.id;
                document.getElementById('packaging').value = batchData.packaging || 'drum';  // Default packaging
                document.getElementById('size').value = batchData.size || '';  // Default size
                document.getElementById('quantity').value = batchData.quantity || 1;  // Default quantity

                // Tampilkan modal
                var myModal = new bootstrap.Modal(document.getElementById('finishProductionModal'));
                myModal.show();
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('finishProductionModal');
            var batchIdElement = document.getElementById('batchId');
            var finishBatchIdInput = document.getElementById('finish_batch_id');

            // Ketika tombol untuk membuka modal diklik
            modal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Tombol yang diklik
                var batchId = button.getAttribute('data-batch-id'); // Ambil batch_id dari atribut data

                // Perbarui judul modal dengan batch ID
                batchIdElement.textContent = batchId;
                finishBatchIdInput.value = batchId; // Set batch_id ke dalam input hidden
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.getElementById('toggleColumnFilter');
            const filterPanel = document.getElementById('columnFilterPanel');
            const checkboxes = document.querySelectorAll('.toggle-column');
            const table = document.getElementById('myTable');

            // Toggle Panel
            toggleButton.addEventListener('click', () => {
                filterPanel.classList.toggle('d-none');
            });

            // Load saved column visibility from localStorage
            checkboxes.forEach((checkbox) => {
                const colIndex = checkbox.value;
                const stored = localStorage.getItem('showCol_' + colIndex);
                if (stored !== null) {
                    checkbox.checked = stored === 'true';
                }
                toggleColumn(colIndex, checkbox.checked);
            });

            // Handle checkbox toggle
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    const colIndex = this.value;
                    const isChecked = this.checked;
                    localStorage.setItem('showCol_' + colIndex, isChecked);
                    toggleColumn(colIndex, isChecked);
                });
            });

            // Function to show/hide column
            function toggleColumn(index, show) {
                const rows = table.rows;
                for (let i = 0; i < rows.length; i++) {
                    const cell = rows[i].cells[index];
                    if (cell) {
                        cell.style.display = show ? '' : 'none';
                    }
                }
            }

            // Optional: Show panel by default on desktop
            if (window.innerWidth >= 768) {
                filterPanel.classList.remove('d-none');
            }
        });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('packaging-container');
                const addBtn = document.getElementById('addPackaging');

                addBtn.addEventListener('click', function () {
                    const newRow = document.createElement('div');
                    newRow.classList.add('packaging-group', 'row', 'g-2', 'mb-2');
                    newRow.innerHTML = `
                        <div class="col-md-4">
                            <select class="form-select" name="packaging[]">
                                <option value="drum">Drum</option>
                                <option value="pail">Pail</option>
                                <option value="pot">Pot</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="size[]" placeholder="Size (Kg)" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="quantity[]" placeholder="Qty" required>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="button" class="btn btn-danger remove-packaging">×</button>
                        </div>
                    `;
                    container.appendChild(newRow);
                });

                container.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-packaging')) {
                        e.target.closest('.packaging-group').remove();
                    }
                });
            });
        </script>
        <script>
            document.getElementById('add-packaging').addEventListener('click', function() {
                const container = document.getElementById('packaging-container');
                const item = document.querySelector('.packaging-item').cloneNode(true);

                item.querySelectorAll('input, select').forEach(input => {
                    input.value = '';
                });

                container.appendChild(item);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-packaging')) {
                    const container = document.getElementById('packaging-container');
                    if (container.querySelectorAll('.packaging-item').length > 1) {
                        e.target.closest('.packaging-item').remove();
                    }
                }
            });
        </script>

        <!-- Finish Prod -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('packaging-container2');
                const addBtn = document.getElementById('addPackaging2');

                addBtn.addEventListener('click', function () {
                    const newRow = document.createElement('div');
                    newRow.classList.add('packaging-group2', 'row', 'g-2', 'mb-2');
                    newRow.innerHTML = `
                        <div class="col-md-4">
                            <select class="form-select" name="packaging[]">
                                <option value="drum">Drum</option>
                                <option value="pail">Pail</option>
                                <option value="pot">Pot</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="size[]" placeholder="Size (Kg)" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="quantity[]" placeholder="Qty" required>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="button" class="btn btn-danger remove-packaging2">×</button>
                        </div>
                    `;
                    container.appendChild(newRow);
                });

                container.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-packaging2')) {
                        e.target.closest('.packaging-group2').remove();
                    }
                });
            });
        </script>
        <script>
            document.getElementById('add-packaging2').addEventListener('click', function() {
                const container = document.getElementById('packaging-container2');
                const item = document.querySelector('.packaging-item2').cloneNode(true);

                item.querySelectorAll('input, select').forEach(input => {
                    input.value = '';
                });

                container.appendChild(item);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-packaging2')) {
                    const container = document.getElementById('packaging-container2');
                    if (container.querySelectorAll('.packaging-item2').length > 1) {
                        e.target.closest('.packaging-item2').remove();
                    }
                }
            });
        </script>
@endsection
