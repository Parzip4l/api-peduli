@extends('layouts.vertical', ['title' => 'Edit Penerimaan Oli'])

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush


@section('content')
<div class="row">
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4>Form Edit Pengiriman Oli</h4>
            </div>
            <div class="card-body">
                <form action="{{route('oli.update', $oli->id )}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Pengirim</label>
                            <input type="text" class="form-control" name="pengirim" required value="{{$oli->pengirim}}">    
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Jenis Oli</label>
                            <select name="jenis_oli" class="form-control" id="" required>
                                <option value="Bahan" {{ $oli->jenis_oli == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                                <option value="Service" {{ $oli->jenis_oli == 'Service' ? 'selected' : '' }}>Service</option>
                                <option value="Trafo" {{ $oli->jenis_oli == 'Trafo' ? 'selected' : '' }}>Trafo</option>
                                <option value="Minarex" {{ $oli->jenis_oli == 'Minarex' ? 'selected' : '' }}>Minarex</option>
                            </select>   
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Jumlah</label>
                            <input type="text" class="form-control" name="jumlah" placeholder="2 Drum" required value="{{$oli->jumlah}}">       
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Harga</label>
                            <input type="text" class="form-control" name="harga" placeholder="2 Drum" required value="{{$oli->harga}}" id="harga">       
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Total</label>
                            <input type="text" class="form-control" name="total" placeholder="2 Drum" required value="{{$oli->total}}" id="total">       
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary w-100" type="submit">Update Data</button>
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
@endsection