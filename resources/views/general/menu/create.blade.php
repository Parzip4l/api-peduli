@extends('layouts.vertical', ['title' => 'Create Menu'])
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
        <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data" id="product-dropzone" class="dropzone">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Menu</h4>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">Menu Name</label>
                            <input type="text" name="title" id="username" class="form-control" placeholder="Menu Name" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="prodemail" class="form-label">Icon <span>(List Icon Click <a href="https://icon-sets.iconify.design/solar/page-100.html">Here</a></span>)</label>
                        <input type="text" name="icon" id="icon" class="form-control" placeholder="icon">
                    </div>
                    <div class="col-lg-4">
                        <label for="prodemail" class="form-label">Url</label>
                        <input type="text" name="url" id="url" class="form-control" placeholder="Your Routes">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">Parent Menu</label>
                            <select class="form-select" name="parent_id" id="example-select">
                                <option value="">Select parent if this child menu</option>
                                @foreach($menuData as $parent)
                                    <option value="{{$parent->id}}">{{$parent->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="prodemail" class="form-label">Is Active</label>
                            <select class="form-select" name="is_active" id="example-select">
                                <option value="1">Active</option>
                                <option value="0">Non Active</option>
                            </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="prodemail" class="form-label">Order Menu</label>
                        <input type="number" name="order" id="order" class="form-control" placeholder="Order menu" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <label for="roles" class="form-label">Select Roles</label>
                        <select class="form-control" name="role_ids[]" id="roles" data-choices multiple>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button class="btn btn-primary w-100" type="submit">Create Menu</button>
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