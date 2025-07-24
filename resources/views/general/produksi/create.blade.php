@extends('layouts.vertical', ['title' => 'Production Batch Data'])

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
        <a href="{{ route('production_batches.index') }}" class="btn btn-primary btn-sm mb-2">
            <iconify-icon icon="solar:alt-arrow-left-bold" class="align-middle fs-18"></iconify-icon> Kembali
        </a>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('production_batches.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Production Batch</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="distributor-name" class="form-label">Batch Code</label>
                                        <input type="text" id="batch_code" name="batch_code" class="form-control" placeholder="Batch Code">
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="distributor-name" class="form-label">Tanggal Produksi</label>
                                        <input type="date" id="tanggal" name="tanggal" class="form-control" placeholder="Batch Code">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="seller-email" class="form-label">Produk</label>
                                    <div class="mb-4">
                                        <select name="produk" id="" class="form-control">
                                            <option value="Multipurpose">Multipurpose</option>
                                            <option value="Xtreme">Xtreme</option>
                                            <option value="Heavy Loader">Heavy Loader</option>
                                            <option value="Supreme">Supreme</option>
                                            <option value="F300">F300</option>
                                            <option value="Super">Super</option>
                                            <option value="Optima">Optima</option>
                                            <option value="Wheel Power">Wheel Power</option>
                                            <option value="Wheel Active">Wheel Active</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="seller-number" class="form-label">Tangki Masak</label>
                                    <div class="mb-3">
                                        <select name="tangki_masak" id="" class="form-control">
                                            <option value="1">Tangki Masak 1</option>
                                            <option value="2">Tangki Masak 2</option>
                                            <option value="3">Tangki Masak 3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <label for="seller-number" class="form-label">Status Produksi</label>
                                    <div class="mb-3">
                                        <select name="status" id="" class="form-control">
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <label for="seller-number" class="form-label">Hasil Produksi</label>
                                    <div class="mb-3">
                                        <select name="hasil_status" id="" class="form-control">
                                            <option value="OK">Ok</option>
                                            <option value="BS">BS</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Production Information -->

                    <div class="card pt-3">
                        
                        <div class="card-body">
                            <div class="step-indicator mb-4">
                                <div id="wizard-step1" class="active">Step 1: Tangki Masak</div>
                                <div id="wizard-step2">Step 2: Tangki Olah</div>
                            </div>
                            <!-- ========== STEP 1: Bahan Tangki Masak ========== -->
                            <div id="step1" class="card p-3 mb-4">
                                <h5>Step 1: Bahan Baku - Tangki Masak</h5>
                                @foreach (['oli', 'lemak', 'kapur'] as $kategori)
                                    <div class="mb-2">
                                        <label>{{ strtoupper($kategori) }}</label>
                                        <div id="{{ $kategori }}-container-step1"></div>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addRow('{{ $kategori }}', 'step1')">Tambah {{ strtoupper($kategori) }}</button>
                                    </div>
                                @endforeach
                                <div class="form-group mt-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label>Bahan Bakar Tangki Masak</label>
                                            <select name="bahan_bakar" class="form-control" id="">
                                                <option value="solar">Solar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Qty (Liter)</label>
                                            <input type="number" name="bahan_bakar_masak" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success mt-3" onclick="nextStep()">Lanjut ke Step 2</button>
                            </div>

                            <!-- ========== STEP 2: Tangki Olah & Bahan Tambahan ========== -->
                            <div id="step2" class="card p-3 mb-4" style="display: none;">
                                <h5>Step 2: Tangki Olah</h5>
                                <div class="form-group">
                                    <label>Pilih Tangki Olah</label>
                                    <select name="tangki_olah" id="tangkiOlah" class="form-control" onchange="showOlahForm()" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="1">Tangki Olah 1</option>
                                        <option value="2">Tangki Olah 2</option>
                                        <option value="3">Tangki Olah 3</option>
                                    </select>
                                </div>

                            <div id="olah-form" style="display: none;">
                                <h5 class="mt-4">Bahan Tambahan - Tangki Olah</h5>
                                @foreach (['oli', 'lemak', 'kapur', 'pewarna', 'additif', 'bs'] as $kategori)
                                <div class="mb-2">
                                    <label>{{ strtoupper($kategori) }}</label>
                                        <div id="{{ $kategori }}-container-step2"></div>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addRow('{{ $kategori }}', 'step2')">Tambah {{ strtoupper($kategori) }}</button>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label>Bahan Bakar Tangki Olah</label>
                                        <select name="bahan_bakar" class="form-control" id="">
                                            <option value="solar">Solar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Qty (Liter)</label>
                                        <input type="number" name="bahan_bakar_masak" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary w-100 mt-2" onclick="prevStep()">Kembali Ke Step 1</button>
                        </div>
                    </div>

                    
                    <div class="p-3 bg-light mb-3 rounded">
                        <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <a href="#!" class="btn btn-outline-secondary w-100">Cancel</a>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary w-100" type="submit">Save Change</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- end card -->
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

        function addRow(kategori, step) {
            const container = document.getElementById(`${kategori}-container-${step}`);
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');

            let tipeOptions = '';
            let digunakanOptions = '';

            if (kategori === 'oli') {
                tipeOptions = `
                    <option value="Bahan">Bahan</option>
                    <option value="Service">Service</option>
                    <option value="Minarex">Minarex</option>
                    <option value="Trafo">Trafo</option>`;
                digunakanOptions = `
                    <option value="Tembak">Tembak</option>
                    <option value="Pancing">Pancing</option>
                    <option value="Bilas">Bilas</option>`;
            } else if (kategori === 'lemak') {
                digunakanOptions = `
                    <option value="Mendri">Mendri</option>
                    <option value="Wandes">Wandes</option>
                    <option value="PFAD">PFAD</option>
                    <option value="Solo">Solo</option>
                    <option value="Saeful">Saeful</option>
                    <option value="12 HSA">12 HSA</option>
                    <option value="S Acid">S Acid</option>`;
            } else if (kategori === 'additif') {
                digunakanOptions = `
                    <option value="Latex">Latex</option>
                    <option value="Tackifier 2022">Tackifier 2022</option>
                    <option value="Tackifier Champ">Tackifier Champ</option>
                    <option value="EP">EP</option>`;
            } else if (kategori === 'pewarna') {
                digunakanOptions = `
                    <option value="Sepuhan Merah">Sepuhan Merah</option>
                    <option value="Sepuhan Kuning">Sepuhan Kuning</option>
                    <option value="Sepuhan Biru">Sepuhan Biru</option>
                    <option value="Sepuhan Putih">Sepuhan Putih</option>
                    <option value="Sepuhan Hijau">Sepuhan Hijau</option>`;
                    
            }

            div.innerHTML = `

                ${tipeOptions ? `
                <div class="col-md-2 mb-2">
                    <select name="${kategori}_tipe_${step}[]" class="form-control">
                        ${tipeOptions}
                    </select>
                </div>` : ''}

                ${digunakanOptions ? `
                <div class="col-md-2 mb-2">
                    <select name="${kategori}_jenis_${step}[]" class="form-control">
                        ${digunakanOptions}
                    </select>
                </div>` : ''}

                <div class="col-md-3 mb-2">
                    <input type="number" name="${kategori}_qty_${step}[]" class="form-control" placeholder="Qty (Kg)" step="0.01" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="${kategori}_ket_${step}[]" class="form-control" placeholder="Keterangan">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">X Hapus</button>
                </div>
            `;
            container.appendChild(div);
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
