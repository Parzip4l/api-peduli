@extends('layouts.vertical', ['title' => 'Create Riset Data Produk'])

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
<a href="{{route('log-riset-grease.index')}}" class="btn btn-sm btn-primary mb-2"><iconify-icon icon="mynaui:chevron-left-solid" class="align-middle fs-18"></iconify-icon> Kembali</a>
<div class="row">
    <div class="col-xl-12 col-lg-12 ">
    <form action="{{ route('log-riset-grease.store') }}" method="POST" enctype="multipart/form-data" id="product-dropzone" class="dropzone">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Master Data</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="product-name" class="form-label">Product / Formulation / Sample</label>
                            <input type="text" name="product_name" id="product-name" class="form-control" placeholder="Riset Name" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="weight-product" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="expected_start_date" class="form-control" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="weight-product" class="form-label">Ekspektasi Tanggal Selesai</label>
                        <input type="date" name="expected_end_date" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <h4 class="card-title">Details Data</h4>
            </div>
            <div class="card-body">
                <div id="details-container">
                    <div class="details-item">
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <label for="trial_method" class="form-label">Metode Riset</label>
                                <textarea name="details[0][trial_method]" class="form-control" id="" required></textarea>
                            </div>
                            <div class="col-lg-6">
                                <label for="trial_result" class="form-label">Hasil Riset</label>
                                <textarea name="details[0][trial_result]" class="form-control" id=""></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label for="issue" class="form-label">Temuan Masalah</label>
                                <textarea name="details[0][issue]" class="form-control" required></textarea>
                            </div>
                            <div class="col-lg-4">
                                <label for="improvement_ideas" class="form-label">Ide Improvment</label>
                                <textarea name="details[0][improvement_ideas]" class="form-control" required></textarea>
                            </div>
                            <div class="col-lg-4">
                                <label for="improvement_schedule" class="form-label">Schedule Tindak Lanjut</label>
                                <input type="date" name="details[0][improvement_schedule]" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label for="competitor_comparison" class="form-label">Kompetitor Komparasi</label>
                                <textarea name="details[0][competitor_comparison]" class="form-control" required></textarea>
                            </div>
                            <div class="col-lg-4">
                                <label for="status" class="form-label">Status</label>
                                <select name="details[0][status]" class="form-control">
                                    <option value="On Progress">On Progress</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Done">Done</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="attachement" class="form-label">Attachment</label>
                                <input type="file" name="details[0][file]" class="form-control">
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger remove-detail mb-2">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn btn-success mt-3" id="add-detail">Add Details</button>
            </div>

        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button class="btn btn-primary w-100" type="submit">Create Data</button>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn--outline-secondary w-100" type="reset">Cancel</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

@endsection

@section('script-bottom')
@vite(['resources/js/pages/ecommerce-product-details.js'])
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let detailIndex = 1; // Indeks awal untuk detail

    // Tambahkan detail baru
    document.getElementById("add-detail").addEventListener("click", function () {
        const detailsContainer = document.getElementById("details-container");

        // Template detail baru
        const newDetail = `
        <div class="details-item">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <label for="trial_method" class="form-label">Metode Riset</label>
                    <textarea name="details[${detailIndex}][trial_method]" class="form-control" id="" required></textarea>
                </div>
                <div class="col-lg-6">
                    <label for="trial_result" class="form-label">Hasil Riset</label>
                     <textarea name="details[${detailIndex}][trial_result]" class="form-control" id=""></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label for="issue" class="form-label">Temuan Masalah</label>
                    <textarea name="details[${detailIndex}][issue]" class="form-control"></textarea>
                </div>
                <div class="col-lg-4">
                    <label for="improvement_ideas" class="form-label">Ide Improvment</label>
                    <textarea name="details[${detailIndex}][improvement_ideas]" class="form-control"></textarea>
                </div>
                <div class="col-lg-4">
                    <label for="improvement_schedule" class="form-label">Schedule Tindak Lanjut</label>
                    <input type="date" name="details[${detailIndex}][improvement_schedule]" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label for="competitor_comparison" class="form-label">Kompetitor Komparasi</label>
                    <textarea name="details[${detailIndex}][competitor_comparison]" class="form-control"></textarea>
                </div>
                <div class="col-lg-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="details[${detailIndex}][status]" class="form-control">
                        <option value="On Progress">On Progress</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Done">Done</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label for="attachement" class="form-label">Attachment</label>
                    <input type="file" name="details[${detailIndex}][file]" class="form-control" required>
                </div>
            </div>
            <button type="button" class="btn btn-danger remove-detail mb-2">Remove</button>
        </div>
        `;

        // Tambahkan detail baru ke dalam container
        detailsContainer.insertAdjacentHTML("beforeend", newDetail);
        detailIndex++;
    });

    // Hapus detail
    document.getElementById("details-container").addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-detail")) {
            event.target.closest(".details-item").remove();
        }
    });
});

</script>
@endsection