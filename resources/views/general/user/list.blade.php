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

                        <a href="{{ route('user.create') }}" class="btn btn-sm btn-soft-primary">
                            <i class="bx bx-plus me-1"></i>Create User
                        </a>
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
                                    Nama
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
                                <td>{{ $data->email }}</td>
                                <td>
                                    @php
                                        $role = App\Models\Setting\Role::where('id',$data->role_id)->first();
                                    @endphp
                                    <a href="#!">{{$role->name ?? 'No Role'}}</a>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                        <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                        <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
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
