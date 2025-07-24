@extends('layouts.vertical', ['title' => 'Create Role'])
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
    <div class="col-xl-12 col-lg-12 ">
        <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data" id="product-dropzone" class="dropzone">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Roles</h4>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="username" class="form-label">Roles Name</label>
                            <input type="text" name="name" id="username" class="form-control" placeholder="Superadmin" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button class="btn btn-primary w-100" type="submit">Create Roles</button>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-outline-secondary w-100" type="reset">Cancel</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

@endsection

@section('script-bottom')

@endsection