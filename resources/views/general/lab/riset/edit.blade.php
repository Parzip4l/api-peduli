@extends('layouts.vertical', ['title' => 'Update Riset Data Produk'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

<a href="{{route('log-riset-grease.index')}}" class="btn btn-sm btn-primary mb-2"><iconify-icon icon="mynaui:chevron-left-solid" class="align-middle fs-18"></iconify-icon> Kembali</a>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <form action="{{ route('log-riset-grease.update', $master->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Master Data</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Product / Formulation / Sample</label>
                        <input type="text" name="product_name" id="product-name" class="form-control" value="{{ $master->product_name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="start-date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="expected_start_date" id="start-date" class="form-control" value="{{ $master->expected_start_date }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="end-date" class="form-label">Ekspektasi Tanggal Selesai</label>
                            <input type="date" name="expected_end_date" id="end-date" class="form-control" value="{{ $master->expected_end_date }}" required>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title">Details Data</h4>
                </div>
                <div class="card-body">
                    <div id="details-container">
                        @foreach($master->details as $key => $detail)
                            <div class="detail-item" data-id="{{ $detail->id }}" data-index="{{ $key }}">
                                <h5>Detail #{{ $key + 1 }}</h5>
                                <input type="hidden" name="details[{{ $key }}][id]" value="{{ $detail->id }}">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="trial_method_{{ $key }}">Metode Uji</label>
                                        <textarea name="details[{{ $key }}][trial_method]" id="trial_method{{ $key }}" class="form-control" required>{{ $detail->trial_method }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="trial_result_{{ $key }}">Hasil Uji</label>
                                        <textarea name="details[{{ $key }}][trial_result]" id="trial_result{{ $key }}" class="form-control" required>{{ $detail->trial_result }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <label for="issue_{{ $key }}" class="form-label">Temuan Masalah</label>
                                        <textarea name="details[{{ $key }}][issue]" id="issue_{{ $key }}" class="form-control" required>{{ $detail->issue }}</textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="improvement_ideas_{{ $key }}" class="form-label">Ide Improvment</label>
                                        <textarea name="details[{{ $key }}][improvement_ideas]" id="improvement_ideas_{{ $key }}" class="form-control" required>{{ $detail->improvement_ideas }}</textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="improvement_schedule_{{ $key }}" class="form-label">Schedule Tindak Lanjut</label>
                                        <input type="date" name="details[{{ $key }}][improvement_schedule]" id="improvement_schedule_{{ $key }}" class="form-control" required value="{{ $detail->improvement_schedule }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <label for="competitor_comparison_{{ $key }}" class="form-label">Kompetitor Komparasi</label>
                                        <input type="text" name="details[{{ $key }}][competitor_comparison]" id="competitor_comparison_{{ $key }}" class="form-control" required value="{{ $detail->competitor_comparison }}">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="status_{{ $key }}" class="form-label">Status</label>
                                        <select name="details[{{ $key }}][status]" id="status_{{ $key }}" class="form-control">
                                            <option value="On Progress" {{ $detail->status == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                            <option value="On Hold" {{ $detail->status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                            <option value="Done" {{ $detail->status == 'Done' ? 'selected' : '' }}>Done</option>
                                            <option value="Closed" {{ $detail->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="competitor_comparison_{{ $key }}" class="form-label">Attachment</label>
                                        <input type="file" name="details[{{ $key }}][file]" id="file{{ $key }}" class="form-control" value="{{ $detail->attachment }}">
                                        @if($detail->attachment)
                                        <a href="{{ asset('storage/' . $detail->attachment) }}" target="_blank" class="btn btn-sm btn-success mt-2">View Attachment</a>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger remove-detail mt-2">Hapus</button>
                                <hr>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-detail" class="btn btn-primary mt-3">Add Row</button>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script-bottom')
@vite(['resources/js/pages/ecommerce-product-details.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const detailsContainer = document.getElementById('details-container');

    document.getElementById('add-detail').addEventListener('click', function () {
        const index = detailsContainer.children.length;

        const detailTemplate = `
            <div class="detail-item" data-index="${index}">
                <h5>Detail #${index + 1}</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="trial_method_${index}">Metode Uji</label>
                        <input type="text" name="details[${index}][trial_method]" id="trial_method_${index}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="trial_result_${index}">Hasil Uji</label>
                        <input type="text" name="details[${index}][trial_result]" id="trial_result_${index}" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label for="issue_${index}" class="form-label">Temuan Masalah</label>
                        <textarea name="details[${index}][issue]" id="issue_${index}" class="form-control" required>{{ $detail->issue }}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label for="improvement_ideas_${index}" class="form-label">Ide Improvment</label>
                        <textarea name="details[${index}][improvement_ideas]" id="improvement_ideas_${index}" class="form-control" required>{{ $detail->improvement_ideas }}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label for="improvement_schedule_${index}" class="form-label">Schedule Tindak Lanjut</label>
                        <input type="date" name="details[${index}][improvement_schedule]" id="improvement_schedule_${index}" class="form-control" required value="{{ $detail->improvement_schedule }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label for="competitor_comparison_${index}" class="form-label">Kompetitor Komparasi</label>
                        <input type="text" name="details[${index}][competitor_comparison]" id="competitor_comparison_${index}" class="form-control" required value="{{ $detail->competitor_comparison }}">
                    </div>
                    <div class="col-lg-4">
                        <label for="status_${index}" class="form-label">Status</label>
                        <select name="details[${index}][status]" id="status_${index}" class="form-control">
                            <option value="On Progress" {{ $detail->status == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="On Hold" {{ $detail->status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Done" {{ $detail->status == 'Done' ? 'selected' : '' }}>Done</option>
                            <option value="Closed" {{ $detail->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="competitor_comparison_${index}" class="form-label">Attachment</label>
                        <input type="file" name="details[${index}][file]" id="attachment_${index}" class="form-control" value="{{ $detail->attachment }}">
                        <p>{{ $detail->attachment }}</p>
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-detail mt-2">Hapus</button>
                <hr>
            </div>
        `;

        detailsContainer.insertAdjacentHTML('beforeend', detailTemplate);
    });

    detailsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-detail')) {
            const detailItem = e.target.closest('.detail-item');
            const detailId = detailItem.getAttribute('data-id');

            if (detailId) {
                // Show confirmation dialog
                Swal.fire({
                    title: 'Hapus Detail?',
                    text: 'Data akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/log-riset-grease/detail/${detailId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(() => {
                            detailItem.remove();
                            Swal.fire('Deleted!', 'Detail berhasil dihapus.', 'success');
                        }).catch(() => {
                            Swal.fire('Error!', 'Gagal menghapus detail.', 'error');
                        });
                    }
                });
            } else {
                detailItem.remove();
            }
        }
    });
</script>
@endsection
