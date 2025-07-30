@extends('layouts.vertical', ['title' => 'User List'])

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            List User Active
                        </h4>
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
                                    <input type="text" name="username" class="form-control" value="{{$data->username}}">
                                </div>
                                <div class="mb-2">
                                    <label for="" class="form-label">email</label>
                                    <input type="text" name="email" class="form-control" value="{{$data->email}}">
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

@endsection
