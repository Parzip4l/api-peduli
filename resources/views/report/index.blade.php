@extends('layouts.vertical', ['title' => 'List Data Laporan'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
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
@php
    $statusColors = [
        'open' => 'bg-warning',
        'progress' => 'bg-primary',
        'rejected_qshe' => 'bg-danger',
        'confirmation_qshe' => 'bg-info',
        'closed' => 'bg-success',
    ];
    $user = auth()->user();
@endphp
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            Data Laporan
                        </h4>

                        <a href="{{ route('laporan.create') }}" class="btn btn-sm btn-soft-danger">
                            <i class="bx bx-plus me-1"></i>Buat Laporan
                        </a>
                        
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="" class="mb-1">Search Data</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search by item name" value="{{ request()->get('search') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="mb-1">Filter Berdasarkan Status</label>
                            <form method="GET" class="mb-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Semua Status --</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="assigned_to_division" {{ request('status') == 'assigned_to_division' ? 'selected' : '' }}>Assigned</option>
                                    <option value="follow_up_submitted" {{ request('status') == 'follow_up_submitted' ? 'selected' : '' }}>Follow Up Submitted</option>
                                    <option value="under_review_by_qshe" {{ request('status') == 'under_review_by_qshe' ? 'selected' : '' }}>Under Review</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="follow_up_rejected" {{ request('status') == 'follow_up_rejected' ? 'selected' : '' }}>Follow Up Rejected</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
                 

                <!-- Table Desktop-->
                <div id="table-search" class="table-responsive d-none d-md-block">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th>Nomor</th>
                                <th>Title</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Divisi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach($reports as $report)
                            <tr>
                                <td>{{$report->nomor_laporan}}</td>
                                <td>{{$report->judul}}</td>  
                                <td>{{date('d-m-Y', strtotime($report->tanggal_laporan))}}</td>  
                                <td><span class="badge {{ $statusColors[$report->status] ?? 'bg-secondary' }}">{{ $report->status }}</span></td>  
                                <td>{{$report->division->name ?? '-'}}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('laporan.show', hashid_encode($report->id)) }}" class="btn btn-soft-danger btn-sm">
                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <!-- <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $report->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a> -->
                                        @if ($user->role == 'QSHE' && !$report->division_id && !$report->assigned_to)
                                            <a href="#" class="btn btn-soft-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalQshe{{hashid_encode($report->id)}}">
                                                <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Review Laporan
                                            </a>
                                        @endif
                                        @if ($user->role === 'PIC' )
                                            <a href="#" class="btn btn-soft-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalPIC{{hashid_encode($report->id)}}">
                                                <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Review Laporan
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <tfoot>
                        <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
                            <div>
                                Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} entries
                            </div>
                            <div class="">
                                {{ $reports->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                            </div>
                        </div>
                    </tfoot>
                </div>

            </div>
            <!-- end card -->

            <!-- Mobile View -->
            <div class="d-block d-md-none">

                @forelse($reports as $report)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="fw-bold mb-1">{{ $report->judul }}</h5>
                            <p class="mb-1"><strong>Nomor:</strong> {{ $report->nomor_laporan }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ date('d-m-Y', strtotime($report->tanggal_laporan)) }}</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge {{ $statusColors[$report->status] ?? 'bg-secondary' }}">{{ $report->status }}</span></p>
                            <p class="mb-1"><strong>Divisi:</strong> {{ $report->division->name ?? 'Belum di Assign' }}</p>
                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ route('laporan.show', hashid_encode($report->id)) }}" class="btn btn-soft-danger btn-sm w-100">
                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18 me-1"></iconify-icon> Lihat Laporan
                                </a>
                                @if ($user->role == 'qshe' && !$report->division_id && !$report->assigned_to)
                                    <a href="#" class="btn btn-soft-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalQshe{{hashid_encode($report->id)}}">
                                        <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Review Laporan
                                    </a>
                                @endif
                                @if ($user->is_pic == 1 && !in_array($report->status, ['follow_up_submitted', 'under_review_by_qshe', 'closed', 'follow_up_rejected']))
                                    <a href="#" class="btn btn-soft-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalPIC{{hashid_encode($report->id)}}">
                                        <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Review Laporan
                                    </a>
                                @endif
                                @if(in_array($report->status, ['follow_up_submitted', 'follow_up_rejected']))
                                    <a href="#" class="btn btn-soft-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#submitProgress{{hashid_encode($report->id)}}">
                                        <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Update Progress 
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center" role="alert">
                        Tidak ada laporan ditemukan untuk filter yang dipilih.
                    </div>
                @endforelse
            </div>

            @foreach($reports as $report)
            <div class="modal fade" id="modalQshe{{hashid_encode($report->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Review Laporan {{$report->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.review.qshe', hashid_encode($report->id)) }}" method="POST" id="qsheReviewForm{{ hashid_encode($report->id) }}">
                                @csrf
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Setuju / Tidak</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($report->id) }}" required>
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="approve">Setuju</option>
                                        <option value="reject">Tidak Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control"></textarea>
                                </div>

                                <div class="div-approval-fields d-none" id="approvalFields{{ hashid_encode($report->id) }}">
                                    <div class="mb-2">
                                        <label for="" class="form-label">Divisi</label>
                                        <select name="division_id" class="form-control">
                                            <option value="">-- Pilih Divisi --</option>
                                            @foreach ($divisi as $div)
                                                <option value="{{ $div->id }}">{{ $div->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Review</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Modal PIC -->
            @foreach($reports as $report)
            <div class="modal fade" id="modalPIC{{hashid_encode($report->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Review Laporan {{$report->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.review.pic', hashid_encode($report->id)) }}" method="POST" id="qsheReviewForm{{ hashid_encode($report->id) }}">
                                @csrf
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Setuju / Tidak</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($report->id) }}" required>
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="approve">Setuju</option>
                                        <option value="reject">Tidak Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Review</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Modal Submit PIC -->
            @foreach($reports as $report)
            <div class="modal fade" id="submitProgress{{hashid_encode($report->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update Tindak Lanjut Laporan {{$report->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.submit.pic', hashid_encode($report->id)) }}" method="POST" id="submitProgress{{ hashid_encode($report->id) }}">
                                @csrf
                                <div class="col-md-12 mb-2">
                                    <label for="foto" class="form-label">Ambil Foto</label>
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        name="foto" 
                                        id="fotoInput" 
                                        accept="image/*" 
                                        capture="environment"
                                        onchange="compressAndPreviewFoto(event)">
                                    <input type="hidden" name="foto_base64" id="fotoBase64">
                                </div>

                                <div class="col-md-12 mb-2">
                                    <img id="previewImage" src="#" alt="Preview Foto" style="display: none; max-width: 100%; height: auto;" class="rounded shadow-sm"/>
                                </div>
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Status</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($report->id) }}" required>
                                        <option value="1">Selesai</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Tindak Lanjut</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
                url: "{{ route('laporan.index') }}",  // Route for user list
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
                url: "{{ route('laporan.index') }}",  // Route for user list
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
                fetch('/laporan/' + ItemID, {
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

                fetch(`/laporan/${maintenanceId}`)
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Event handler untuk aksi QSHE (Setuju / Tidak Setuju)
        document.querySelectorAll('.action-select').forEach(function (select) {
            select.addEventListener('change', function () {
                const targetId = this.dataset.target;
                const approvalDiv = document.getElementById('approvalFields' + targetId);
                
                if (this.value === 'approve') {
                    approvalDiv.classList.remove('d-none');
                } else {
                    approvalDiv.classList.add('d-none');
                    // Optional: Kosongkan field divisi dan PIC jika ingin reset saat tidak setuju
                    approvalDiv.querySelector('select[name="division_id"]').value = "";
                }
            });
        });
    });
</script>
<script>
    function compressAndPreviewFoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');

                // Tentukan ukuran maksimal (misalnya 1024 x 1024)
                const maxSize = 1024;
                let width = img.width;
                let height = img.height;

                if (width > height && width > maxSize) {
                    height *= maxSize / width;
                    width = maxSize;
                } else if (height > maxSize) {
                    width *= maxSize / height;
                    height = maxSize;
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                // Kompresi ke format JPG dengan kualitas 0.7
                const compressedDataUrl = canvas.toDataURL('image/jpeg', 0.7);

                // Tampilkan preview
                const preview = document.getElementById('previewImage');
                preview.src = compressedDataUrl;
                preview.style.display = 'block';

                // Simpan ke input hidden base64
                document.getElementById('fotoBase64').value = compressedDataUrl;
            };

            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
</script>
@endsection
