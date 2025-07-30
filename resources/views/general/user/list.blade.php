@extends('layouts.vertical', ['title' => 'User List'])

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
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            List User Active
                        </h4>
                        <a href="{{route('user.create')}}" class="btn btn-outline-primary">Tambah User</a>
                    </div>
                </div>
                <!-- end card body -->

                <!-- Search Input -->
                <div class="mb-3 mx-3">
                    <label for="" class="mb-2">Search Data</label>
                    <input type="text" id="search-input" class="form-control" placeholder="Search by name or email" value="{{ request()->get('search') }}">
                </div>

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">
                                    Nama Lengkap
                                </th>
                                <th>
                                    Username
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Role
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @foreach($user as $data)
                            <tr>
                                <td class="ps-3">
                                    <a href="apps-ecommerce-order-detail.html">{{ $data->name }}</a>
                                </td>
                                <td>{{ $data->username ?? '-'}}</td>
                                <td>{{ $data->email }}</td>
                                <td>
                                    @php
                                        $role = App\Models\Setting\Role::where('id',$data->role_id)->first();
                                    @endphp
                                    <a href="#!">{{$data->role ?? 'No Role'}}</a>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#!" class="btn btn-soft-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUserUpdate{{ $data->id }}"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                        
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            
                        </tfoot>
                    </table>
                    
                    <tfoot>
                        <div class="d-flex justify-content-between mx-3 mt-2 mb-2 ">
                            <div>
                                Showing {{ $user->firstItem() }} to {{ $user->lastItem() }} of {{ $user->total() }} entries
                            </div>
                            <div class="">
                            {{ $user->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </tfoot>
                    
                </div>
            </div>
            @foreach($user as $data)
                <div class="modal fade" id="ModalUserUpdate{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Update Data User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('user.update', $data->id)}}" method="POST" id="ModalDivisi">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <label for="" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" value="{{$data->username}}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="" class="form-label">email</label>
                                        <input type="text" name="email" class="form-control" value="{{$data->email}}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Password Baru (opsional)</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password{{$data->id}}" class="form-control">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password{{$data->id}}">
                                                👁️
                                            </button>
                                        </div>
                                        <div class="text-danger small" id="passwordError{{$data->id}}"></div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="password_confirmation{{$data->id}}" class="form-control">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation{{$data->id}}">
                                                👁️
                                            </button>
                                        </div>
                                    </div>


                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-danger">Update Data</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('script')
    @vite(['resources/js/pages/dashboard.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Trigger an AJAX request on keyup event
        $('#search-input').on('keyup', function() {
            var search = $(this).val();  // Get the search input value

            $.ajax({
                url: "{{ route('user.index') }}",  // Route for user list
                method: 'GET',
                data: { search: search },  // Send the search query
                success: function(response) {
                    $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                    $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                }
            });
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle Show/Hide Password
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = '🙈';
            } else {
                input.type = 'password';
                this.textContent = '👁️';
            }
        });
    });

    // Validasi Password Tooltip
    document.querySelectorAll('form[id^="ModalDivisi"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            const passwordInput = form.querySelector('input[name="password"]');
            const passwordError = form.querySelector('[id^="passwordError"]');
            const password = passwordInput.value;
            let errors = [];

            passwordError.textContent = ''; // reset

            if (password.length > 0) {
                if (password.length < 8) {
                    errors.push("Minimal 8 karakter");
                }
                if (!/[A-Z]/.test(password)) {
                    errors.push("Harus mengandung huruf besar");
                }
                if (!/[a-z]/.test(password)) {
                    errors.push("Harus mengandung huruf kecil");
                }
                if (!/[0-9]/.test(password)) {
                    errors.push("Harus mengandung angka");
                }
                if (!/[^A-Za-z0-9]/.test(password)) {
                    errors.push("Harus mengandung karakter khusus");
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    passwordError.innerHTML = errors.map(err => `• ${err}`).join('<br>');
                    passwordInput.classList.add('is-invalid');
                } else {
                    passwordInput.classList.remove('is-invalid');
                }
            }
        });
    });
});
</script>


@endsection
