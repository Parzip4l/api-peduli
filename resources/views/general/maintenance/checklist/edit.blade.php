@extends('layouts.vertical', ['title' => 'Form Create Maintenance Checlist Item'])

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
                <h4>Form Maintenance Checklist Item</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('maintenance.listmaintenance.update', $checklist->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Parent Item Name</label>
                            <select class="form-control" name="part_id" required>
                                <option value="">-- Select Item --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" 
                                        {{ $checklist->part_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->item->name ?? 'No Parent' }} - {{ $item->name }}
                                    </option>
                                @endforeach
                            </select> 
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Checklist Name</label>
                            <input type="text" class="form-control" name="checklist_item" value="{{ $checklist->checklist_item }}" required>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="keterangan" class="form-control">{{ $checklist->keterangan }}</textarea>
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