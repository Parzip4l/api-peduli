@extends('layouts.vertical', ['title' => 'Crete Purchase Order'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
@endsection

@section('content')
<div class="row">
    <div class="card">

        <div class="card-header">
            <h4 class="mb-0">📦 Buat Purchase Order</h4>
        </div>

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('purchase_orders.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="order_date" class="form-label">Tanggal Order</label>
                        <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col">
                        <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="terms_of_payment" class="form-label">Terms of Payment</label>
                    <input type="text" name="terms_of_payment" id="terms_of_payment" class="form-control" value="{{ old('terms_of_payment') }}" placeholder="Contoh: Cicilan 3 bulan">
                </div>

                <hr>

                <h4>Items</h4>

                <table class="table" id="items-table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>UoM</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Catatan</th>
                            <th><button type="button" id="add-item" class="btn btn-sm btn-primary">+ Tambah</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(old('items'))
                            @foreach(old('items') as $i => $oldItem)
                            <tr>
                                <td>
                                    <select class="form-control select2" name="items[{{ $i }}][warehouse_item_id]" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" @selected($oldItem['warehouse_item_id'] == $item->id)>{{ $item->name }} - {{ $item->type }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control qty" min="1" value="{{ $oldItem['quantity'] }}" required></td>
                                <td>
                                    <select class="form-control select2"  name="items[{{ $i }}][uom]" required>
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
                                </td>
                                <td><input type="number" name="items[{{ $i }}][price]" class="form-control price" min="0" step="0.01" value="{{ $oldItem['price'] }}" required></td>
                                <td><input type="text" class="form-control subtotal" readonly></td>
                                <td><input type="text" name="items[{{ $i }}][notes]" class="form-control" value="{{ $oldItem['notes'] ?? '' }}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-item">x</button></td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td>
                                <select name="items[0][warehouse_item_id]" class="form-control" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->type }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][quantity]" class="form-control qty" min="1" value="1" required></td>
                            <td>
                                <select class="form-control select2" name="items[0][uom]" required>
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
                            </td>
                            <td><input type="number" name="items[0][price]" class="form-control price" min="0" step="0.01" value="0" required></td>
                            <td><input type="text" class="form-control subtotal" readonly></td>
                            <td><input type="text" name="items[0][notes]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-item">x</button></td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mb-3">
                    <label for="discount" class="form-label">Diskon (%)</label>
                    <input type="number" name="discount" id="discount" class="form-control" min="0" max="100" step="0.01" value="{{ old('discount', 0) }}">
                </div>

                <div class="">
                    <label for="tax" class="form-label">Pajak (%)</label>
                    <input type="number" name="tax" id="tax" class="form-control" min="0" max="100" step="0.01" value="{{ old('tax', 0) }}">
                </div>
                <div class="mb-3">
                    <small class="text-danger mb-3">Contoh untuk diskon dan pajak : isi 11 untuk 11%</small>
                </div>
                

                <div class="mb-3">
                    <h3><strong>Total: </strong> <span id="total-amount">0</span></h3>
                </div>

                <button type="submit" class="btn btn-success">Simpan Purchase Order</button>
            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsTableBody = document.querySelector('#items-table tbody');
    const addItemBtn = document.querySelector('#add-item');
    let itemIndex = {{ old('items') ? count(old('items')) : 1 }};

    function recalcSubtotal(row) {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const subtotalInput = row.querySelector('.subtotal');
        subtotalInput.value = (qty * price).toFixed(2);
    }

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('#items-table tbody tr').forEach(row => {
            const subtotal = parseFloat(row.querySelector('.subtotal').value) || 0;
            total += subtotal;
        });

        const discountPercent = parseFloat(document.querySelector('#discount').value) || 0;
        const taxPercent = parseFloat(document.querySelector('#tax').value) || 0;

        const discountAmount = total * (discountPercent / 100);
        const taxAmount = total * (taxPercent / 100);

        const finalTotal = total - discountAmount + taxAmount;
        document.querySelector('#total-amount').textContent = finalTotal.toFixed(2);
    }

    function addRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][warehouse_item_id]" class="form-select" required>
                    <option value="">Pilih Barang</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->type }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" min="1" value="1" required></td>
            <td>
                <select class="form-control" data-placeholder="Select Item" name="items[${itemIndex}][uom]" required>
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
            </td>
            <td><input type="number" name="items[${itemIndex}][price]" class="form-control price" min="0" step="0.01" value="0" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><input type="text" name="items[${itemIndex}][notes]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">x</button></td>
        `;
        itemsTableBody.appendChild(tr);
        itemIndex++;

        tr.querySelectorAll('.qty, .price').forEach(input => {
            input.addEventListener('input', () => {
                recalcSubtotal(tr);
                recalcTotal();
            });
        });

        tr.querySelector('.remove-item').addEventListener('click', () => {
            tr.remove();
            recalcTotal();
        });

        // Init subtotal
        recalcSubtotal(tr);
        recalcTotal();
    }

    // Init existing rows
    document.querySelectorAll('#items-table tbody tr').forEach(row => {
        row.querySelectorAll('.qty, .price').forEach(input => {
            input.addEventListener('input', () => {
                recalcSubtotal(row);
                recalcTotal();
            });
        });
        row.querySelector('.remove-item').addEventListener('click', () => {
            row.remove();
            recalcTotal();
        });

        // Set subtotal
        recalcSubtotal(row);
    });

    // Add new item
    addItemBtn.addEventListener('click', addRow);

    // Recalc total on discount/tax change
    document.querySelector('#discount').addEventListener('input', recalcTotal);
    document.querySelector('#tax').addEventListener('input', recalcTotal);

    recalcTotal();
});
</script>
@endsection