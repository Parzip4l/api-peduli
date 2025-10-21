@extends('layouts.vertical', ['title' => 'Data PIC'])
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
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">
                            Data PIC
                        </h4>

                        <a href="#" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#ModalPIC">
                            <i class="bx bx-plus me-1"></i>Buat PIC
                        </a>
                    </div>
                </div>
                <!-- end card body -->

                <!-- Search Input -->
                <div class="mb-3 mx-3">
                    <label for="" class="mb-2">Cari Data</label>
                    <input type="text" id="search-input" class="form-control" placeholder="Cari berdasarkan nama" value="{{ request()->get('search') }}">
                </div>

                <!-- Table -->
                <div id="table-search">
                    <table class="table mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Nama User</th>
                                <th>Nama Divisi</th>
                                <th>Nama Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body">
                            @php 
                                $no = 1;
                            @endphp
                            @foreach($user as $data)
                            <tr>
                                <td class="ps-3">{{$no++}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->division->name}}</td>
                                <td>{{ $data->department?->name ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-soft-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalPIC{{ $data->id }}">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <a href="#!" class="btn btn-soft-danger btn-sm" onclick="confirmDelete({{ $data->id }})">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <tfoot>
                        <div class="d-flex justify-content-between mx-3 mt-2 mb-2">
                            <div>
                                Showing {{ $user->firstItem() }} to {{ $user->lastItem() }} of {{ $user->total() }} entries
                            </div>
                            <div class="">
                                {{ $user->links('pagination::bootstrap-4') }}  <!-- Pagination links -->
                            </div>
                        </div>
                    </tfoot>
                </div>

            </div>

            <!-- Modal Tambah Data -->
            <div class="modal fade" id="ModalPIC" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Lokasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('pic.dataupdate') }}" method="POST" id="ModalPIC">
                                @csrf
                                <div class="mb-2">
                                    <label for="name" class="form-label">Nama Karyawan</label>
                                    <select name="name" id="name" class="form-control" data-choices data-choices-groups required>
                                        <option value="">Pilih Nama</option>
                                        @foreach($allUser as $userData)
                                            <option value="{{ $userData->id }}">{{ $userData->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="divisi" class="form-label">Divisi</label>
                                    <select name="divisi" id="divisi" class="form-control division-select" data-choices required>
                                        <option value="">Pilih Divisi</option>
                                        @foreach($divisi as $div)
                                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Optional: Tambah dropdown department dinamis -->
                                <div class="mb-2 d-none" id="departmentWrapperCreate">
                                    <label for="department_id" class="form-label">Departemen</label>
                                    <select name="department_id" id="departmentSelectCreate" class="form-control">
                                        <option value="">-- Pilih Departemen --</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Simpan Data</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Data -->
            @foreach($user as $data)
            <div class="modal fade" id="ModalPIC{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update Data PIC</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('pic.update', $data->id) }}" method="POST" id="ModalPIC{{ $data->id }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-2">
                                    <label for="" class="form-label">Nama Karyawan</label>
                                    <input type="text" name="name_display" class="form-control" value="{{ $data->name }}" readonly>
                                    <input type="hidden" name="name" value="{{ $data->id }}">
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Divisi</label>
                                    <select name="divisi" class="form-control division-select" data-target="{{ $data->id }}" data-choices required>
                                        <option value="">Pilih Divisi</option>
                                        @foreach($divisi as $div)
                                            <option value="{{ $div->id }}" {{ $data->division_id == $div->id ? 'selected' : '' }}>
                                                {{ $div->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Optional: Tambah dropdown department dinamis -->
                                <div class="mb-2 {{ $data->department_id ? '' : 'd-none' }}" id="departmentWrapper{{ $data->id }}">
                                    <label for="department_id" class="form-label">Departemen</label>
                                    <select name="department_id" id="departmentSelect{{ $data->id }}" class="form-control">
                                        <option value="">-- Pilih Departemen --</option>
                                        @if(isset($departments))
                                            @foreach($departments as $dep)
                                                <option value="{{ $dep->id }}" {{ $data->department_id == $dep->id ? 'selected' : '' }}>
                                                    {{ $dep->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
        // Trigger an AJAX request on keyup event
            $('#search-input').on('keyup', function() {
                var search = $(this).val();  // Get the search input value
                var page = $('.pagination .active a').text() || 1;  // Get the current page, default to 1

                $.ajax({
                    url: "{{ route('pic.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the current page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html());  // Replace table body with filtered data
                        $('.pagination').html($(response).find('.pagination').html());  // Replace pagination
                    }
                });
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                
                var page = $(this).attr('href').split('page=')[1];  // Extract the page number from the link
                var search = $('#search-input').val();  // Get the search input value

                $.ajax({
                    url: "{{ route('pic.index') }}",  // Route for user list
                    method: 'GET',
                    data: { 
                        search: search,  // Send the search query
                        page: page       // Send the page number
                    },
                    success: function(response) {
                        $('#user-table-body').html($(response).find('#user-table-body').html()); 
                        $('.pagination').html($(response).find('.pagination').html()); 
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.division-select').forEach(select => {
                select.addEventListener('change', async function () {
                    const divisionId = this.value;
                    const targetId = this.getAttribute('data-target') || 'Create';
                    const wrapper = document.getElementById(`departmentWrapper${targetId}`);
                    const dropdown = document.getElementById(`departmentSelect${targetId}`);

                    dropdown.innerHTML = '<option value="">-- Pilih Departemen --</option>';

                    if (!divisionId) {
                        wrapper.classList.add('d-none');
                        return;
                    }

                    try {
                        const res = await fetch(`/departments/by-division/${divisionId}`);
                        const data = await res.json();

                        if (data.length > 0) {
                            data.forEach(dep => {
                                const opt = document.createElement('option');
                                opt.value = dep.id;
                                opt.textContent = dep.name;
                                dropdown.appendChild(opt);
                            });
                            wrapper.classList.remove('d-none');
                        } else {
                            wrapper.classList.add('d-none');
                        }
                    } catch (err) {
                        console.error(err);
                    }
                });
            });
        });
    </script>

@endsection
