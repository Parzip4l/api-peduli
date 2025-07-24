@extends('layouts.vertical', ['title' => 'Create Product'])

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
<div class="row">
    <div class="col-xl-12 col-lg-12 ">
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="product-dropzone" class="dropzone">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Product Photo</h4>
            </div>
            <div class="card-body">
                <input name="images[]" type="file">
                <!-- end dropzon-preview -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="product-name" class="form-label">Product Name</label>
                            <input type="text" name="name" id="product-name" class="form-control" placeholder="Items Name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="product-categories" class="form-label">Product Categories</label>
                        <select class="form-control" name="categories" id="product-categories" data-choices data-choices-groups data-placeholder="Select Categories" name="choices-single-groups">
                            <option value="">Choose a categories</option>
                            <option value="CHAMPOIL">CHAMPOIL</option>
                            <option value="WHEEL">WHEEL</option>
                            <option value="KUHL">KUHL</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label for="weight-product" class="form-label">Weight</label>
                        <select class="form-control" name="weight" id="weight-product" data-choices data-choices-groups data-placeholder="Select Categories" name="choices-single-groups">
                            <option value="">Choose a weight</option>
                            <option value="Kg">KG</option>
                            <option value="gr">Gr</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="size-product" class="form-label">Size</label>
                            <input type="number" name="size" id="size-product" class="form-control" placeholder="15">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="product-id" class="form-label">Tag Number</label>
                            <input type="number" name="tag_number" id="product-id" class="form-control" placeholder="#******">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="product-stock" class="form-label">Stock</label>
                            <input type="number" name="stock" id="product-stock" class="form-control" placeholder="Quantity">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="product-stock" class="form-label">Tag</label>
                        <select class="form-control" name="tags[]" id="choices-multiple-remove-button" data-choices data-choices-removeItem multiple>
                            <option value="CHAMPOIL" selected>CHAMPOIL</option>
                            <option value="WHEEL">WHEEL</option>
                            <option value="KUHL">KUHL</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button class="btn btn-outline-secondary w-100" type="submit">Create Product</button>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-primary w-100" type="reset">Cancel</button>
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
    Dropzone.options.productDropzone = {
        paramName: "images[]", // Nama field untuk file
        maxFilesize: 2, // Ukuran maksimum file dalam MB
        acceptedFiles: "image/jpeg,image/png,image/gif", // Format yang diterima
        addRemoveLinks: true, // Opsi untuk menghapus file
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}" // Menyertakan token CSRF
        },
        init: function () {
            this.on("sending", function(file, xhr, formData) {
                // Ambil nilai input lain dari form
                formData.append("name", document.querySelector("input[name='name']").value);
                formData.append("categories", document.querySelector("select[name='categories']").value);
                formData.append("weight", document.querySelector("select[name='weight']").value);
                formData.append("size", document.querySelector("input[name='size']").value);
                formData.append("tag_number", document.querySelector("input[name='tag_number']").value);
                formData.append("stock", document.querySelector("input[name='stock']").value);

                // Jika ada data multiple (array), tambahkan seperti ini
                const tags = document.querySelector("select[name='tags[]']");
                const selectedTags = Array.from(tags.options).filter(option => option.selected).map(option => option.value);
                formData.append("tags", JSON.stringify(selectedTags)); // Kirim sebagai JSON
            });
        },
        success: function(file, response) {
            console.log("File uploaded successfully:", response);
        },
        error: function(file, message) {
            console.error("Upload error:", message);
        }
    };
</script>

@endsection