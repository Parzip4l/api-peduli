@extends('layouts.vertical', ['title' => 'Warehouse Item Create'])

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
                <form action="{{ route('warehouse.items.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe</label>
                        <input type="text" class="form-control" name="type" value="{{ old('type') }}" placeholder ="Type Item eg; Bahan">
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-control" name="category" required>
                            <option value="">Pilih Tipe</option>
                            <option value="RMA">Raw Material</option>
                            <option value="FNG">Finish Goods</option>
                            <option value="PCK">Packaging</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">Satuan</label>
                        <select class="form-control select2" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="unit" required>
                            <option value="">Pilih Satuan</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="mg" {{ old('unit') == 'mg' ? 'selected' : '' }}>Miligram (mg)</option>
                            <option value="ton" {{ old('unit') == 'ton' ? 'selected' : '' }}>Ton (t)</option>
                            <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Liter (l)</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>MiliLiter (ml)</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            <option value="m" {{ old('unit') == 'm' ? 'selected' : '' }}>Meter (m)</option>
                            <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>Centimeter (cm)</option>
                            <option value="mm" {{ old('unit') == 'mm' ? 'selected' : '' }}>Millimeter (mm)</option>
                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>Roll</option>
                            <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="set" {{ old('unit') == 'set' ? 'selected' : '' }}>Set</option>

                            <option value="Pail 4 Kg" {{ old('unit') == 'Pail 4 Kg' ? 'selected' : '' }}>Pail 4 Kg</option>
                            <option value="Pail 10 Kg" {{ old('unit') == 'Pail 10 Kg' ? 'selected' : '' }}>Pail 10 Kg</option>
                            <option value="Pail 14 Kg" {{ old('unit') == 'Pail 14 Kg' ? 'selected' : '' }}>Pail 14 Kg</option>
                            <option value="Pail 15 Kg" {{ old('unit') == 'Pail 15 Kg' ? 'selected' : '' }}>Pail 15 Kg</option>
                            
                            <option value="Drum 170 Kg" {{ old('unit') == 'Drum 170 Kg' ? 'selected' : '' }}>Drum 170 Kg</option>
                            <option value="Drum 175 Kg" {{ old('unit') == 'Drum 175 Kg' ? 'selected' : '' }}>Drum 175 Kg</option>
                            <option value="Drum 180 Kg" {{ old('unit') == 'Drum 180 Kg' ? 'selected' : '' }}>Drum 180 Kg</option>

                            <option value="Pot 450 gram" {{ old('unit') == 'Pot 450 gram' ? 'selected' : '' }}>Pot 450 gram</option>
                            <option value="Pot 500 gram" {{ old('unit') == 'Pot 500 gram' ? 'selected' : '' }}>Pot 500 gram</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="minimum_qty" class="form-label">Minimum Stok</label>
                        <input type="number" step="0.01" class="form-control" name="minimum_qty" value="{{ old('minimum_qty') }}" required>
                    </div>

                    <hr>
                    <h5 class="mt-4">Stok Awal Item</h5>
                    <div class="mb-3">
                        <input type="number" step="0.01" class="form-control" name="stokawal" value="{{ old('stokawal') }}" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
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