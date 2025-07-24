@extends('layouts.vertical', ['title' => 'Warehouse Item Edit'])

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
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('warehouse.items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe</label>
                        <input type="text" class="form-control" name="type" value="{{ old('type', $item->type) }}" placeholder="Type Item eg; Bahan">
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-control" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="RMA" {{ old('category', $item->category) == 'RMA' ? 'selected' : '' }}>Raw Material</option>
                            <option value="FNG" {{ old('category', $item->category) == 'FNG' ? 'selected' : '' }}>Finish Goods</option>
                            <option value="PCK" {{ old('category', $item->category) == 'PCK' ? 'selected' : '' }}>Packaging</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">Satuan</label>
                        <select class="form-control select2" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="unit" required>
                            <option value="">Pilih Satuan</option>
                            <option value="kg" {{ old('unit', $item->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ old('unit', $item->unit) == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="mg" {{ old('unit', $item->unit) == 'mg' ? 'selected' : '' }}>Miligram (mg)</option>
                            <option value="ton" {{ old('unit', $item->unit) == 'ton' ? 'selected' : '' }}>Ton (t)</option>
                            <option value="l" {{ old('unit', $item->unit) == 'l' ? 'selected' : '' }}>Liter (l)</option>
                            <option value="ml" {{ old('unit', $item->unit) == 'ml' ? 'selected' : '' }}>MiliLiter (ml)</option>
                            <option value="pcs" {{ old('unit', $item->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            <option value="m" {{ old('unit', $item->unit) == 'm' ? 'selected' : '' }}>Meter (m)</option>
                            <option value="cm" {{ old('unit', $item->unit) == 'cm' ? 'selected' : '' }}>Centimeter (cm)</option>
                            <option value="mm" {{ old('unit', $item->unit) == 'mm' ? 'selected' : '' }}>Millimeter (mm)</option>
                            <option value="box" {{ old('unit', $item->unit) == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="roll" {{ old('unit', $item->unit) == 'roll' ? 'selected' : '' }}>Roll</option>
                            <option value="pack" {{ old('unit', $item->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="set" {{ old('unit', $item->unit) == 'set' ? 'selected' : '' }}>Set</option>

                            <option value="Pail 4 Kg" {{ old('unit', $item->unit) == 'Pail 4 Kg' ? 'selected' : '' }}>Pail 4 Kg</option>
                            <option value="Pail 10 Kg" {{ old('unit', $item->unit) == 'Pail 10 Kg' ? 'selected' : '' }}>Pail 10 Kg</option>
                            <option value="Pail 14 Kg" {{ old('unit', $item->unit) == 'Pail 14 Kg' ? 'selected' : '' }}>Pail 14 Kg</option>
                            <option value="Pail 15 Kg" {{ old('unit', $item->unit) == 'Pail 15 Kg' ? 'selected' : '' }}>Pail 15 Kg</option>
                            
                            <option value="Drum 170 Kg" {{ old('unit', $item->unit) == 'Drum 170 Kg' ? 'selected' : '' }}>Drum 170 Kg</option>
                            <option value="Drum 175 Kg" {{ old('unit', $item->unit) == 'Drum 175 Kg' ? 'selected' : '' }}>Drum 175 Kg</option>
                            <option value="Drum 180 Kg" {{ old('unit', $item->unit) == 'Drum 180 Kg' ? 'selected' : '' }}>Drum 180 Kg</option>

                            <option value="Pot 450 gram" {{ old('unit', $item->unit) == 'Pot 450 gram' ? 'selected' : '' }}>Pot 450 gram</option>
                            <option value="Pot 500 gram" {{ old('unit', $item->unit) == 'Pot 500 gram' ? 'selected' : '' }}>Pot 500 gram</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="minimum_qty" class="form-label">Minimum Stok</label>
                        <input type="number" step="0.01" class="form-control" name="minimum_qty" value="{{ old('minimum_qty', $item->minimum_qty) }}" required>
                    </div>

                    <hr>
                    <h5 class="mt-4">Stok Item</h5>
                    <div class="mb-3">
                        <input type="number" step="0.01" class="form-control" name="stokawal" value="{{ old('stokawal', $stokawal )}}" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('warehouse.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-bottom')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
