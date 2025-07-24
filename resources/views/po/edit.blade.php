@extends('layouts.vertical', ['title' => 'Edit Purchase Order'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
@endsection

@section('content')
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">✏️ Edit Purchase Order</h4>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('purchase_orders.update', $purchaseOrder->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select" id="choices-single-groups" data-choices required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('distributor_id', $purchaseOrder->distributor_id) == $supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="order_date" class="form-label">Tanggal Order</label>
                        <input type="date" name="order_date" id="order_date" class="form-control"
                               value="{{ old('order_date', $purchaseOrder->po_date) }}" required>
                    </div>
                    <div class="col">
                        <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                               value="{{ old('due_date', $purchaseOrder->due_date) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="terms_of_payment" class="form-label">Terms of Payment</label>
                    <input type="text" name="terms_of_payment" id="terms_of_payment" class="form-control"
                           value="{{ old('top', $purchaseOrder->top) }}"
                           placeholder="Contoh: Cicilan 3 bulan">
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
                        @foreach(old('items', $purchaseOrder->items) as $i => $item)
                        <tr>
                            <td>
                                <select class="form-control select2" name="items[{{ $i }}][warehouse_item_id]" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach($items as $wItem)
                                        <option value="{{ $wItem->id }}"
                                            @selected((is_array($item) ? $item['warehouse_item_id'] : $item->warehouse_item_id) == $wItem->id)>
                                            {{ $wItem->name }} - {{ $wItem->type }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $i }}][quantity]" class="form-control qty" min="1"
                                       value="{{ is_array($item) ? $item['quantity'] : $item->quantity }}" required>
                            </td>
                            <td>
                                <select class="form-control select2" name="items[{{ $i }}][uom]" required>
                                    @php
                                        $selectedUom = is_array($item) ? $item['uom'] : $item->uom;
                                        $uoms = ['kg','g','mg','ton','l','ml','pcs','m','cm','mm','box','roll','pack','set',
                                                 'Pail 4 Kg','Pail 10 Kg','Pail 14 Kg','Pail 15 Kg',
                                                 'Drum 170 Kg','Drum 175 Kg','Drum 180 Kg',
                                                 'Pot 450 gram','Pot 500 gram'];
                                    @endphp
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom }}" @selected($selectedUom == $uom)>{{ $uom }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[{{ $i }}][price]" class="form-control price" min="0" step="0.01"
                                       value="{{ is_array($item) ? $item['price'] : $item->price }}" required></td>
                            <td><input type="text" class="form-control subtotal" readonly></td>
                            <td><input type="text" name="items[{{ $i }}][notes]" class="form-control"
                                       value="{{ is_array($item) ? $item['notes'] ?? '' : $item->notes }}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-item">x</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mb-3">
                    <label for="discount" class="form-label">Diskon (%)</label>
                    <input type="number" name="discount" id="discount" class="form-control" min="0" max="100" step="0.01"
                           value="{{ old('discount', $purchaseOrder->discount) }}">
                </div>

                <div class="mb-0">
                    <label for="tax" class="form-label">Pajak (%)</label>
                    <input type="number" name="tax" id="tax" class="form-control" min="0" max="100" step="0.01"
                           value="{{ old('tax', $purchaseOrder->tax) }}">
                </div>

                <div class="mb-3">
                    <small class="text-danger">Contoh untuk diskon dan pajak : isi 11 untuk 11%</small>
                </div>

                <div class="mb-3">
                    <h3>Total : <strong><span id="total-amount">Rp 0</span></strong></h3>
                    <input type="hidden" name="total" id="total_raw" value="0">
                </div>

                <button type="submit" class="btn btn-success">Update Purchase Order</button>
            </form>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID', { minimumFractionDigits: 2 });
    }

    function calculateSubtotals() {
        let total = 0;
        document.querySelectorAll('#items-table tbody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty')?.value || 0);
            const price = parseFloat(row.querySelector('.price')?.value || 0);
            const subtotal = qty * price;
            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            total += subtotal;
        });

        const discount = parseFloat(document.getElementById('discount')?.value || 0);
        const tax = parseFloat(document.getElementById('tax')?.value || 0);

        let totalAfterDiscount = total * (1 - discount / 100);
        let totalWithTax = totalAfterDiscount * (1 + tax / 100);

        // tampilkan ke user dalam format rupiah
        document.getElementById('total-amount').innerText = formatRupiah(totalWithTax);

        // simpan ke input tersembunyi dalam format angka
        document.getElementById('total_raw').value = totalWithTax.toFixed(2);
    }

    document.addEventListener('input', function (e) {
        if (
            e.target.classList.contains('qty') ||
            e.target.classList.contains('price') ||
            e.target.id === 'discount' ||
            e.target.id === 'tax'
        ) {
            calculateSubtotals();
        }
    });

    document.getElementById('add-item').addEventListener('click', function () {
        const index = document.querySelectorAll('#items-table tbody tr').length;
        const newRow = `
        <tr>
            <td>
                <select class="form-control select2" name="items[${index}][warehouse_item_id]" required>
                    <option value="">Pilih Barang</option>
                    @foreach($items as $wItem)
                        <option value="{{ $wItem->id }}">{{ $wItem->name }} - {{ $wItem->type }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][quantity]" class="form-control qty" min="1" required></td>
            <td>
                <select class="form-control select2" name="items[${index}][uom]" required>
                    @foreach($uoms as $uom)
                        <option value="{{ $uom }}">{{ $uom }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][price]" class="form-control price" min="0" step="0.01" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><input type="text" name="items[${index}][notes]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">x</button></td>
        </tr>`;
        document.querySelector('#items-table tbody').insertAdjacentHTML('beforeend', newRow);
        calculateSubtotals();
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
            calculateSubtotals();
        }
    });

    document.addEventListener('DOMContentLoaded', calculateSubtotals);
</script>

@endsection
