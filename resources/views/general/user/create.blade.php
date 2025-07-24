@extends('layouts.vertical', ['title' => 'Create User'])

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
    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data" id="product-dropzone" class="dropzone">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add User</h4>
            </div>
            <div class="card-body">
                <label for="avatar" class="form-label">Photo Profile</label>
                <input name="images[]" type="file">
                <!-- end dropzon-preview -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name</label>
                            <input type="text" name="name" id="username" class="form-control" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="prodemail" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-lg-4">
                        <label for="prodemail" class="form-label">Roles</label>
                        <select name="role_id" class="form-control" id="">
                            @foreach($role as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                            <span id="password-error" style="color: red; display: none;">Password does not match!</span>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label for="retype-password" class="form-label">Re-type Password</label>
                        <input type="password" name="retype-password" id="retype-password" class="form-control" placeholder="Re-type Password" required>
                        <span id="retype-password-error" style="color: red; display: none;">Password does not match!</span>
                    </div>

                    <div class="col-lg-6 mt-2">
                        <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button class="btn btn-primary w-100" type="submit">Create User</button>
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
<script>
    // Function to toggle password visibility
    function togglePassword() {
        var password = document.getElementById("password");
        var retypePassword = document.getElementById("retype-password");
        var showPasswordCheckbox = document.getElementById("show-password");
        
        if (showPasswordCheckbox.checked) {
            password.type = "text";
            retypePassword.type = "text";
        } else {
            password.type = "password";
            retypePassword.type = "password";
        }
    }

    // Password validation
    document.getElementById("retype-password").addEventListener("input", function() {
        var password = document.getElementById("password").value;
        var retypePassword = document.getElementById("retype-password").value;
        var errorMessage = document.getElementById("password-error");
        var retypeErrorMessage = document.getElementById("retype-password-error");

        // Show/hide validation messages
        if (password !== retypePassword) {
            retypeErrorMessage.style.display = "inline";
            errorMessage.style.display = "inline";
        } else {
            retypeErrorMessage.style.display = "none";
            errorMessage.style.display = "none";
        }
    });

    // Submit event handler (optional, if you need to do additional checks)
    document.querySelector('form').addEventListener('submit', function(event) {
        var password = document.getElementById("password").value;
        var retypePassword = document.getElementById("retype-password").value;
        
        if (password !== retypePassword) {
            event.preventDefault(); // Prevent form submission
            alert('Passwords do not match!');
        }
    });
</script>
@endsection