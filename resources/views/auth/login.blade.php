@extends('layouts.auth', ['title' => 'Login'])

@section('content')
<div class="d-flex flex-column h-100 p-3">
    <div class="d-flex flex-column flex-grow-1">
        <div class="row h-100">
            <div class="col-xxl-7">
                <div class="row justify-content-center h-100">
                    <div class="col-lg-6 py-lg-5">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <div class="auth-logo mb-4">
                                <a href="#" class="logo-dark">
                                    <img src="/images/lrtj.png" height="55" alt="logo dark">
                                </a>
                                <a href="#" class="logo-light">
                                    <img src="/images/lrtj-putih.png" height="55" alt="logo light">
                                </a>
                            </div>

                            <h2 class="fw-bold fs-24">Sign In</h2>
                            <p class="text-muted mt-1 mb-4">Masukkan username dan password Anda untuk masuk.</p>

                            <form method="POST" action="{{ route('login.attempt') }}" class="authentication-form">
                                @csrf

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label" for="username">Email</label>
                                    <input type="text" name="email" id="username" class="form-control" required value="{{ old('username') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" id="checkbox-signin" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="mb-1 text-center d-grid">
                                    <button class="btn btn-danger" type="submit">Sign In</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-5 d-none d-xxl-flex">
                <div class="card h-100 mb-0 overflow-hidden">
                    <div class="d-flex flex-column h-100">
                        <img src="/images/bg-login.jpeg" alt="" class="w-100 h-100" style="object-fit:cover;">
                    </div>
                </div> <!-- end card -->
            </div>
        </div>
    </div>
</div>
@endsection
