@extends('layouts.vertical', ['title' => 'Edit Production Batch'])

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
    <div class="col">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('production_batches.update', $batch->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Production Batch</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="batch_code" class="form-label">Batch Code</label>
                                        <input type="text" id="batch_code" name="batch_code" class="form-control" placeholder="Batch Code" value="{{ $batch->batch_code }}">
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="distributor-name" class="form-label">Tanggal Produksi</label>
                                        <input type="date" id="tanggal" name="tanggal" class="form-control" placeholder="Batch Code"  value="{{ $batch->tanggal }}">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label for="produk" class="form-label">Produk</label>
                                    <div class="mb-3">
                                        <select name="produk" class="form-control">
                                            <option value="Multipurpose" {{ $batch->produk == 'Multipurpose' ? 'selected' : '' }}>Multipurpose</option>
                                            <option value="Xtreme" {{ $batch->produk == 'Xtreme' ? 'selected' : '' }}>Xtreme</option>
                                            <option value="Heavy Loader" {{ $batch->produk == 'Heavy Loader' ? 'selected' : '' }}>Heavy Loader</option>
                                            <option value="Supreme" {{ $batch->produk == 'Supreme' ? 'selected' : '' }}>Supreme</option>
                                            <option value="F300" {{ $batch->produk == 'F300' ? 'selected' : '' }}>F300</option>
                                            <option value="Super" {{ $batch->produk == 'Super' ? 'selected' : '' }}>Super</option>
                                            <option value="Optima" {{ $batch->produk == 'Optima' ? 'selected' : '' }}>Optima</option>
                                            <option value="Wheel Power" {{ $batch->produk == 'Wheel Power' ? 'selected' : '' }}>Wheel Power</option>
                                            <option value="Wheel Active" {{ $batch->produk == 'Wheel Active' ? 'selected' : '' }}>Wheel Active</option>
                                        </select>
                                    </div>
                                </div>
                                

                                <div class="col-lg-3">
                                    <label for="tangki_masak" class="form-label">Tangki Masak</label>
                                    <div class="mb-3">
                                        <select name="tangki_masak" class="form-control">
                                            <option value="masak1" {{ $batch->tangki_masak == 'masak1' ? 'selected' : '' }}>Tangki Masak 1</option>
                                            <option value="masak2" {{ $batch->tangki_masak == 'masak2' ? 'selected' : '' }}>Tangki Masak 2</option>
                                            <option value="masak3" {{ $batch->tangki_masak == 'masak3' ? 'selected' : '' }}>Tangki Masak 3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <label for="status" class="form-label">Status Produksi</label>
                                    <div class="mb-3">
                                        <select name="status" class="form-control">
                                            <option value="Open" {{ $batch->status == 'Open' ? 'selected' : '' }}>Open</option>
                                            <option value="Closed" {{ $batch->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <label for="hasil_status" class="form-label">Hasil Produksi</label>
                                    <div class="mb-3">
                                        <select name="hasil_status" class="form-control">
                                            <option value="OK" {{ $batch->hasil_status == 'OK' ? 'selected' : '' }}>Ok</option>
                                            <option value="BS" {{ $batch->hasil_status == 'BS' ? 'selected' : '' }}>BS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Step 1: Bahan Tangki Masak -->
                    <div class="card pt-3" id="step1">
                        <div class="card-body">
                            <h5>Step 1: Bahan Baku - Tangki Masak</h5>
                            @foreach (['oli', 'lemak', 'kapur'] as $kategori)
                                <div class="mb-2">
                                    <label>{{ strtoupper($kategori) }}</label>
                                    <div id="{{ $kategori }}-container-step1">
                                        @if(isset($groupedMaterials['step1'][$kategori]))
                                            @foreach($groupedMaterials['step1'][$kategori] as $material)
                                                <div class="row mb-2">
                                                    @if ($kategori === 'oli')
                                                    <div class="col-md-2 mb-2">
                                                        <select name="{{ $kategori }}_tipe_step1[]" class="form-control">
                                                            <option value="Bahan" {{ $material->tipe == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                                                            <option value="Service" {{ $material->tipe == 'Service' ? 'selected' : '' }}>Service</option>
                                                            <option value="Minarex" {{ $material->tipe == 'Minarex' ? 'selected' : '' }}>Minarex</option>
                                                            <option value="Trafo" {{ $material->tipe == 'Trafo' ? 'selected' : '' }}>Trafo</option>
                                                        </select>
                                                    </div>
                                                    @endif
                                                    @if ($kategori === 'oli')
                                                        <div class="col-md-2 mb-2">
                                                            <select name="{{ $kategori }}_jenis_step1[]" class="form-control">
                                                                <option value="Tembak" {{ $material->jenis == 'Tembak' ? 'selected' : '' }}>Tembak</option>
                                                                <option value="Pancing" {{ $material->jenis == 'Pancing' ? 'selected' : '' }}>Pancing</option>
                                                                <option value="Bilas" {{ $material->jenis == 'Bilas' ? 'selected' : '' }}>Bilas</option>
                                                            </select>
                                                        </div>
                                                    @elseif ($kategori === 'lemak')
                                                        <div class="col-md-2 mb-2">
                                                            <select name="{{ $kategori }}_jenis_step1[]" class="form-control">
                                                                <option value="Mendri" {{ $material->jenis == 'Mendri' ? 'selected' : '' }}>Mendri</option>
                                                                <option value="Wandes" {{ $material->jenis == 'Wandes' ? 'selected' : '' }}>Wandes</option>
                                                                <option value="PFAD" {{ $material->jenis == 'PFAD' ? 'selected' : '' }}>PFAD</option>
                                                                <option value="Solo" {{ $material->jenis == 'Solo' ? 'selected' : '' }}>Solo</option>
                                                                <option value="Saeful" {{ $material->jenis == 'Saeful' ? 'selected' : '' }}>Saeful</option>
                                                                <option value="12 HSA" {{ $material->jenis == '12 HSA' ? 'selected' : '' }}>12 HSA</option>
                                                                <option value="S Acid" {{ $material->jenis == 'S Acid' ? 'selected' : '' }}>S Acid</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3 mb-2">
                                                        <input type="number" name="{{ $kategori }}_qty_step1[]" class="form-control" value="{{ $material->qty }}">
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <input type="text" name="{{ $kategori }}_ket_step1[]" class="form-control" value="{{ $material->keterangan }}">
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <button type="button" class="btn btn-danger" onclick="confirmDeleteRow(this)">X Hapus</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addRow('{{ $kategori }}', 'step1')">Tambah {{ strtoupper($kategori) }}</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 2: Bahan Tangki Olah -->
                    <div class="card pt-3" id="step2">
                        <div class="card-body">
                            <h5>Step 2: Bahan Baku - Tangki Olah</h5>
                            @foreach (['oli', 'lemak', 'kapur', 'pewarna', 'additif', 'bs'] as $kategori)
                                <div class="mb-2">
                                    <label>{{ strtoupper($kategori) }}</label>
                                    <div id="{{ $kategori }}-container-step2">
                                        @if(isset($groupedMaterials['step2'][$kategori]))
                                            @foreach($groupedMaterials['step2'][$kategori] as $material)
                                                
                                                <div class="row mb-2">
                                                    @if ($kategori === 'oli')
                                                    <div class="col-md-2 mb-2">
                                                        <select name="{{ $kategori }}_tipe_step2[]" class="form-control">
                                                            <option value="Bahan" {{ $material->tipe == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                                                            <option value="Service" {{ $material->tipe == 'Service' ? 'selected' : '' }}>Service</option>
                                                            <option value="Minarex" {{ $material->tipe == 'Minarex' ? 'selected' : '' }}>Minarex</option>
                                                            <option value="Trafo" {{ $material->tipe == 'Trafo' ? 'selected' : '' }}>Trafo</option>
                                                        </select>
                                                    </div>
                                                    @endif
                                                    @if ($kategori === 'oli')
                                                        <div class="col-md-2 mb-2">
                                                            <select name="{{ $kategori }}_jenis_step2[]" class="form-control">
                                                                <option value="Tembak" {{ $material->jenis == 'Tembak' ? 'selected' : '' }}>Tembak</option>
                                                                <option value="Pancing" {{ $material->jenis == 'Pancing' ? 'selected' : '' }}>Pancing</option>
                                                                <option value="Bilas" {{ $material->jenis == 'Bilas' ? 'selected' : '' }}>Bilas</option>
                                                            </select>
                                                        </div>
                                                    @elseif ($kategori === 'lemak')
                                                        <div class="col-md-2 mb-2">
                                                            <select name="{{ $kategori }}_jenis_step2[]" class="form-control">
                                                                <option value="Mendri" {{ $material->jenis == 'Mendri' ? 'selected' : '' }}>Mendri</option>
                                                                <option value="Wandes" {{ $material->jenis == 'Wandes' ? 'selected' : '' }}>Wandes</option>
                                                                <option value="PFAD" {{ $material->jenis == 'PFAD' ? 'selected' : '' }}>PFAD</option>
                                                                <option value="Solo" {{ $material->jenis == 'Solo' ? 'selected' : '' }}>Solo</option>
                                                                <option value="Saeful" {{ $material->jenis == 'Saeful' ? 'selected' : '' }}>Saeful</option>
                                                                <option value="12 HSA" {{ $material->jenis == '12 HSA' ? 'selected' : '' }}>12 HSA</option>
                                                                <option value="S Acid" {{ $material->jenis == 'S Acid' ? 'selected' : '' }}>S Acid</option>
                                                            </select>
                                                        </div>
                                                    @elseif ($kategori === 'pewarna')
                                                        <div class="col-md-2 mb-2">
                                                            <select name="{{ $kategori }}_jenis_step2[]" class="form-control">
                                                                <option value="Sepuhan Merah" {{ $material->jenis == 'Sepuhan Merah' ? 'selected' : '' }}>Sepuhan Merah</option>
                                                                <option value="Sepuhan Hijau" {{ $material->jenis == 'Sepuhan Hijau' ? 'selected' : '' }}>Sepuhan Hijau</option>
                                                                <option value="Sepuhan Kuning" {{ $material->jenis == 'Sepuhan Kuning' ? 'selected' : '' }}>Sepuhan Kuning</option>
                                                                <option value="Sepuhan Biru" {{ $material->jenis == 'Sepuhan Biru' ? 'selected' : '' }}>Sepuhan Biru</option>
                                                                <option value="Sepuhan Putih" {{ $material->jenis == 'Sepuhan Putih' ? 'selected' : '' }}>Sepuhan Putih</option>
                                                            </select>
                                                        </div>
                                                    @elseif ($kategori === 'additif')
                                                        <div class="col-md-2 mb-2">
                                                            <select name="{{ $kategori }}_jenis_step2[]" class="form-control">
                                                                <option value="Latex" {{ $material->jenis == 'Latex' ? 'selected' : '' }}>Latex</option>
                                                                <option value="Tackifier 2022" {{ $material->jenis == 'Tackifier 2022' ? 'selected' : '' }}>Tackifier 2022</option>
                                                                <option value="Tackifier Champ" {{ $material->jenis == 'Tackifier Champ' ? 'selected' : '' }}>Tackifier Champ</option>
                                                                <option value="EP" {{ $material->jenis == 'EP' ? 'selected' : '' }}>EP</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3 mb-2">
                                                        <input type="number" name="{{ $kategori }}_qty_step2[]" class="form-control" value="{{ $material->qty }}">
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <input type="text" name="{{ $kategori }}_ket_step2[]" class="form-control" value="{{ $material->keterangan }}">
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <button type="button" class="btn btn-danger" onclick="confirmDeleteRow(this)">X Hapus</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addRow('{{ $kategori }}', 'step2')">Tambah {{ strtoupper($kategori) }}</button>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('production_batches.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        function nextStep() {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }

        function showOlahForm() {
            const tangki = document.getElementById('tangkiOlah').value;
            document.getElementById('olah-form').style.display = tangki ? 'block' : 'none';
        }

        function setWizardStep(step) {
            document.getElementById('wizard-step1').classList.remove('active');
            document.getElementById('wizard-step2').classList.remove('active');
            document.getElementById(`wizard-step${step}`).classList.add('active');
        }

        function nextStep() {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            setWizardStep(2);
        }

        function prevStep() {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
            setWizardStep(1);
        }
</script>
<script>
        function addRow(kategori, step) {
            var container = document.getElementById(kategori + '-container-' + step);
            var newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-2');
            let html = '';

            // Tentukan isi select jenis tergantung kategori
            let jenisOptions = `<option value="-">-</option>`;
            if (kategori === 'oli') {
                jenisOptions += `
                    <option value="Tembak">Tembak</option>
                    <option value="Pancing">Pancing</option>
                    <option value="Bilas">Bilas</option>
                `;
            } else if (kategori === 'lemak') {
                jenisOptions += `
                        <option value="Mendri">Mendri</option>
                        <option value="Wandes">Wandes</option>
                        <option value="PFAD">PFAD</option>
                        <option value="Solo">Solo</option>
                        <option value="Saeful">Saeful</option>
                        <option value="12 HSA">12 HSA</option>
                        <option value="S Acid">S Acid</option>
                `;
            } else if (kategori === 'pewarna') {
                jenisOptions += `
                    <option value="Sepuhan Merah">Sepuhan Merah</option>
                    <option value="Sepuhan Kuning">Sepuhan Kuning</option>
                    <option value="Sepuhan Biru">Sepuhan Biru</option>
                    <option value="Sepuhan Putih">Sepuhan Putih</option>
                    <option value="Sepuhan Hijau">Sepuhan Hijau</option>
                `;
            } else if (kategori === 'additif') {
                jenisOptions += `
                    <option value="Latex">Latex</option>
                    <option value="Tackifier 2022">Tackifier 2022</option>
                    <option value="Tackifier Champ">Tackifier Champ</option>
                    <option value="EP">EP</option>
                `;
            }

            if (kategori === 'oli') {
                html += `
                    <div class="col-md-2 mb-2">
                        <select name="${kategori}_tipe_${step}[]" class="form-control">
                            <option value="Bahan">Bahan</option>
                            <option value="Service">Service</option>
                            <option value="Minarex">Minarex</option>
                            <option value="Trafo">Trafo</option>
                        </select>
                    </div>
                `;
            }

            html += `
                <div class="col-md-2 mb-2">
                    <select name="${kategori}_jenis_${step}[]" class="form-control">
                        ${jenisOptions}
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="number" name="${kategori}_qty_${step}[]" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="${kategori}_ket_${step}[]" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">X Hapus</button>
                </div>
            `;

            newRow.innerHTML = html;
            container.appendChild(newRow);
        }
</script>

<script>
    function confirmDeleteRow(button) {
        Swal.fire({
            title: 'Yakin ingin menghapus baris ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.row').remove();
            }
        });
    }
</script>
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .step-indicator div {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-bottom: 3px solid lightgray;
            font-weight: bold;
            color: gray;
        }
        .step-indicator .active {
            border-color: #0d6efd;
            color: #0d6efd;
        }
    </style>
@endsection
