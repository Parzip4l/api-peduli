@extends('layouts.vertical', ['title' => 'Form Buat Laporan'])

@section('css')
@vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
@endsection

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush


@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4>Form Laporan</h4>
            </div>
            <div class="card-body">
                <form action="{{route('laporan.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="foto" class="form-label">Ambil / Pilih Foto</label>
                            <input 
                                type="file" 
                                class="form-control" 
                                name="foto" 
                                id="fotoInput" 
                                accept="image/*" 
                                onchange="compressAndPreviewFoto(event)">
                            <input type="hidden" name="foto_base64" id="fotoBase64">
                        </div>

                        <div class="col-md-12 mb-2">
                            <img id="previewImage" src="#" alt="Preview Foto" style="display: none; max-width: 100%; height: auto;" class="rounded shadow-sm"/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" required>    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="" class="form-control"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Jenis Pengamatan</label>
                            <select class="form-control" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="observation_type_id">
                                <option value="">-- Pilih Jenis Pengamatan --</option>
                                @foreach ($pengamatan as $obs)
                                    <option value="{{$obs->id}}">{{$obs->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Potensi Bahaya</label>
                            <select class="form-control" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="bahaya_id">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach ($bahaya as $bahaya)
                                    <option value="{{$bahaya->id}}">{{$bahaya->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Lokasi</label>
                            <select class="form-control" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="location_id">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($lokasi as $loc)
                                    <option value="{{$loc->id}}">( {{$loc->kode}} ) {{$loc->nama_lokasi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Detail Lokasi</label>
                            <textarea name="detail_lokasi" id="" class="form-control"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Potensi Cedera</label>
                            <select class="form-control" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="hazard_potential_id">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach ($hazard as $haz)
                                    <option value="{{$haz->id}}">{{$haz->deskripsi}} [{{$haz->name}}]</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Perlu Tindak Lanjut</label>
                            <select class="form-control" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Item" name="perlu_tindak_lanjut">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Save Item</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
    <script>
        function compressAndPreviewFoto(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');

                    // Tentukan ukuran maksimal (misalnya 1024 x 1024)
                    const maxSize = 1024;
                    let width = img.width;
                    let height = img.height;

                    if (width > height && width > maxSize) {
                        height *= maxSize / width;
                        width = maxSize;
                    } else if (height > maxSize) {
                        width *= maxSize / height;
                        height = maxSize;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Kompresi ke format JPG dengan kualitas 0.7
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', 0.7);

                    // Tampilkan preview
                    const preview = document.getElementById('previewImage');
                    preview.src = compressedDataUrl;
                    preview.style.display = 'block';

                    // Simpan ke input hidden base64
                    document.getElementById('fotoBase64').value = compressedDataUrl;
                };

                img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
        </script>
@endsection