@extends('layouts.vertical', ['title' => 'Penerimaan Oli Bulan Ini'])

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush


@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4>Form Pencatatan Pengiriman Oli</h4>
                
            </div>
            <div class="card-body">
            <form action="{{route('oli.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Pengirim</label>
                            <input type="text" class="form-control" name="pengirim" required>    
                        </div>
                        <div id="oli-items">
                            <div class="row oli-item mb-3">
                                <div class="col-md-5">
                                    <label>Jenis Oli</label>
                                    <select name="jenis_oli[]" class="form-control" required>
                                        <option value="Bahan">Bahan</option>
                                        <option value="Service">Service</option>
                                        <option value="Trafo">Trafo</option>
                                        <option value="Minarex">Minarex</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label>Jumlah</label>
                                    <input type="text" name="jumlah[]" class="form-control" placeholder="2 Drum" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-remove w-100">Hapus</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success w-100" id="addRow">Tambah Data</button>
                        </div>
                        
                        <div class="col-md-6">
                            <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
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
    $(document).ready(function() {
        // Tambah baris baru
        $('#addRow').click(function() {
            let row = `
            <div class="row oli-item mb-3">
                <div class="col-md-5">
                    <select name="jenis_oli[]" class="form-control" required>
                        <option value="Bahan">Bahan</option>
                        <option value="Service">Service</option>
                        <option value="Trafo">Trafo</option>
                        <option value="Minarex">Minarex</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="jumlah[]" class="form-control" placeholder="2 Drum" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove w-100">Hapus</button>
                </div>
            </div>`;
            $('#oli-items').append(row);
        });

        // Hapus baris
        $(document).on('click', '.btn-remove', function() {
            $(this).closest('.oli-item').remove();
        });

        // SweetAlert
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
    });
</script>
@endsection