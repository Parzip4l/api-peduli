@extends('layouts.vertical', ['title' => 'Form Maintenance'])

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Form Maintenance</h4>
        </div>
        <div class="card-body">
        @if($schedule)
                <form action="{{ route('maintenance.store') }}" method="POST">
                    @csrf
                    <!-- Nama Item -->
                    <div class="mb-3">
                        <label class="form-label">Nama Item</label>
                        <input type="text" class="form-control" value="{{ $item->name }}" readonly>
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                    </div>

                    <!-- Pilihan Part -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Part</label>
                        <select class="form-control" id="part_id" name="part_id" required>
                            <option value="">-- Pilih Part --</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Checklist -->
                    <div class="mb-3">
                        <label class="form-label">Checklist</label>
                        <div id="checklist-container">
                            <p class="text-muted">Pilih part terlebih dahulu untuk menampilkan checklist.</p>
                        </div>
                    </div>

                    <input type="hidden" name="performed_at" value="{{ now() }}">
                    <input type="hidden" name="maintenance_by" value="{{ auth()->user()->id }}">

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            @else
                <div class="alert alert-warning">
                    <strong>Belum waktunya untuk maintenance item ini.</strong>
                </div>
            @endif
        </div>
    </div>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const partSelect = document.getElementById('part_id');
        const checklistContainer = document.getElementById('checklist-container');

        partSelect.addEventListener('change', function () {
            const partId = this.value;
            checklistContainer.innerHTML = "<p>Loading...</p>";

            if (partId) {
                fetch(`/maintenance/get-checklist/${partId}`)
                    .then(response => response.json())
                    .then(data => {
                        checklistContainer.innerHTML = "";
                        if (data.length > 0) {
                            data.forEach((item, index) => {
                                const div = document.createElement('div');
                                div.classList.add('form-check', 'mb-2');
                                div.innerHTML = `
                                    <input type="hidden" name="checklist_items[${item.id}]" value="${item.checklist_item}">
                                    <input class="form-check-input checklist-checkbox" type="checkbox" id="checklist_${item.id}" name="checklist_ids[]" value="${item.id}">
                                    <label class="form-check-label" for="checklist_${item.id}">${item.checklist_item} (${item.keterangan})</label>

                                    <!-- Status Dropdown -->
                                    <div class="mt-2">
                                        <label class="form-label">Status</label>
                                        <select class="form-control checklist-status" name="checklist_status[${item.id}]" disabled>
                                            <option value="Baik">Baik</option>
                                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                        </select>
                                    </div>

                                    <!-- Catatan -->
                                    <div class="mt-2">
                                        <label class="form-label">Catatan</label>
                                        <textarea class="form-control checklist-notes" name="checklist_notes[${item.id}]" disabled></textarea>
                                    </div>
                                    <hr>
                                `;
                                checklistContainer.appendChild(div);
                            });

                            // Aktifkan status dan catatan saat checklist dicentang
                            document.querySelectorAll('.checklist-checkbox').forEach(checkbox => {
                                checkbox.addEventListener('change', function () {
                                    const statusSelect = this.parentElement.querySelector('.checklist-status');
                                    const notesInput = this.parentElement.querySelector('.checklist-notes');
                                    if (this.checked) {
                                        statusSelect.removeAttribute('disabled');
                                        notesInput.removeAttribute('disabled');
                                    } else {
                                        statusSelect.setAttribute('disabled', 'disabled');
                                        notesInput.setAttribute('disabled', 'disabled');
                                    }
                                });
                            });
                        } else {
                            checklistContainer.innerHTML = "<p class='text-danger'>Tidak ada checklist untuk part ini.</p>";
                        }
                    });
            } else {
                checklistContainer.innerHTML = "<p class='text-muted'>Pilih part terlebih dahulu untuk menampilkan checklist.</p>";
            }
        });
    });
</script>

@endsection
