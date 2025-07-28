@extends('layouts.vertical', ['title' => 'Details Data Laporan'])

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
@php 
$user = auth()->user();
@endphp
    <div class="row">
        <div class="col-xl-8 col-lg-8">
            <div class="d-flex gap-2 mt-2">
                @if($data->status === 'under_review_by_qshe' && $user->role == 'qshe')
                <a href="#" class="btn btn-sm btn-soft-success d-block mb-2 " data-bs-toggle="modal" data-bs-target="#progressPIC{{hashid_encode($data->id)}}">
                    <i class="bx bxs-down-left fs-12"></i> Review Progess
                </a>
                @endif
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Laporan {{$data->nomor_laporan}}</h5>
                </div>
                <div class="card-body">
                    <div class="row d-none d-md-flex">
                        <div class="col-md-4">
                            <div class="form-group">
                                <p class="mb-2">Judul</p>
                                @if ($user->role === 'qshe' && $user->role === 'admin')
                                <p class="mb-2">Nama Pelapor</p>
                                @endif
                                <p class="mb-2">Jenis Pengamatan</p>
                                <p class="mb-2">Lokasi Diamati </p>
                                <p class="mb-2">Detail Lokasi </p>
                                <p class="mb-2">Keterangan </p>
                                <p class="mb-2">Potensi Cedera </p>
                                <p class="mb-2">Perlu Tindak Lanjut </p>
                                <p class="mb-2">Divisi </p>
                                <p class="mb-2">Status </p>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <p class="mb-2">:</p>
                                @if ($user->role === 'qshe' && $user->role === 'admin')
                                <p class="mb-2">:</p>
                                @endif
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                                <p class="mb-2">:</p>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <p class="mb-2"><strong>{{ $data->judul }}</strong></p>
                                @if ($user->role === 'qshe' && $user->role === 'admin')
                                <p class="mb-2"><strong>{{ $data->user->name }}</strong></p>
                                @endif
                                <p class="mb-2"><strong>{{ $data->observationType->name }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->location->nama_lokasi }} - {{ $data->location->kode }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->detail_lokasi }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->keterangan }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->hazardPotential->name ?? '-' }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->perlu_tindak_lanjut ? 'Ya' : 'Tidak' }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->division->name ?? 'Belum Ada' }}</strong></p>
                                <p class="mb-2"><strong>{{ $data->status}}</strong></p>
                                @if ($user->role === 'QSHE' && $data->status === 'open' && !$data->division_id && !$data->assigned_to)
                                    <a href="{{ route('laporan.show', hashid_encode($data->id)) }}" class="btn btn-soft-warning btn-sm w-100">
                                        <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Assign Report
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5 mt-4 me-2">
                            @if ($data->foto)
                                <img src="{{ asset('storage/laporan_foto/' . $data->foto) }}" alt="Foto Laporan" class="img-fluid rounded shadow-sm sm mb-2" style="height:300px; object-fit:cover;"/>
                                <p><strong>Foto Laporan</strong></p>
                            @endif
                        </div>
                        <div class="col-md-5 mt-4">
                            @if (!empty($followup) && !empty($followup->attachment) && file_exists(public_path('storage/laporan_foto/' . $followup->attachment)))
                                <img src="{{ asset('storage/laporan_foto/' . $followup->attachment) }}" alt="Foto Laporan" class="img-fluid rounded shadow-sm sm mb-2" style="height:300px; object-fit:cover;"/>
                                <p><strong>Foto Setelah Tindak Lanjut</strong></p>
                            @endif

                        </div>
                    </div>

                    <div class="d-block d-md-none">
                        @if ($data->foto)
                            <img src="{{ asset('storage/laporan_foto/' . $data->foto) }}" alt="Foto Laporan" class="img-fluid rounded shadow-sm sm mb-2" style="height:200px;"/>
                        @endif
                        <div class="mb-2"><strong>Judul Laporan:</strong><br>{{ $data->judul }}</div>
                        @if ($user->role === 'qshe' && $user->role === 'admin')
                        <div class="mb-2"><strong>Nama Pelapor:</strong><br>{{ $data->user->name }}</div>
                        @endif
                        <div class="mb-2"><strong>Jenis Pengamatan:</strong><br>{{ $data->observationType->name }}</div>
                        <div class="mb-2"><strong>Lokasi Diamati:</strong><br>{{ $data->location->nama_lokasi }} - {{ $data->location->kode }}</div>
                        <div class="mb-2"><strong>Detail Lokasi:</strong><br>{{ $data->detail_lokasi }}</div>
                        <div class="mb-2"><strong>Keterangan:</strong><br>{{ $data->keterangan }}</div>
                        <div class="mb-2"><strong>Perlu Tindak Lanjut:</strong><br>{{ $data->perlu_tindak_lanjut ? 'Ya' : 'Tidak' }}</div>
                        <div class="mb-2"><strong>Divisi:</strong><br>{{ $data->division->name ?? 'Belum Ada' }}</div>
                        <div class="mb-2"><strong>PIC:</strong><br>{{ $data->assignedTo->name ?? 'Belum Ada' }}</div>
                        <div class="mb-2"><strong>Status:</strong><br>{{ $data->status }}</div>
                        @if ($user->role === 'QSHE' && $data->status === 'open' && !$data->division_id && !$data->assigned_to)
                            <a href="{{ route('laporan.show', hashid_encode($data->id)) }}" class="btn btn-soft-warning btn-sm w-100">
                                <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Assign Report
                            </a>
                        @endif
                    </div>
                </div>
                
            </div>
            @if ($user->role == 'qshe' && !$data->division_id && !$data->assigned_to)
                <a href="#" class="btn btn-soft-danger btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalQshe{{hashid_encode($data->id)}}">
                    <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Review Laporan
                </a>
            @endif
            @if ($user->is_pic == 1 && !in_array($data->status, ['follow_up_submitted', 'under_review_by_qshe', 'closed', 'follow_up_rejected']))
                <a href="#" class="btn btn-soft-success btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalPIC{{hashid_encode($data->id)}}">
                    <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Review Laporan
                </a>
            @endif
            @if(in_array($data->status, ['follow_up_submitted', 'follow_up_rejected']))
                <a href="#" class="btn btn-soft-success btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#submitProgress{{hashid_encode($data->id)}}">
                    <iconify-icon icon="solar:user-check-line-duotone" class="align-middle fs-18 me-1"></iconify-icon> Update Progress 
                </a>
            @endif

            <div class="modal fade" id="modalQshe{{hashid_encode($data->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Review Laporan {{$data->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.review.qshe', hashid_encode($data->id)) }}" method="POST" id="qsheReviewForm{{ hashid_encode($data->id) }}">
                                @csrf
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Setuju / Tidak</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($data->id) }}" required>
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="approve">Setuju</option>
                                        <option value="reject">Tidak Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control"></textarea>
                                </div>

                                <div class="div-approval-fields d-none" id="approvalFields{{ hashid_encode($data->id) }}">
                                    <div class="mb-2">
                                        <label for="" class="form-label">Divisi</label>
                                        <select name="division_id" class="form-control">
                                            <option value="">-- Pilih Divisi --</option>
                                            @foreach ($divisi as $div)
                                                <option value="{{ $div->id }}">{{ $div->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Review</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal PIC -->
            <div class="modal fade" id="modalPIC{{hashid_encode($data->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Review Laporan {{$data->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.review.pic', hashid_encode($data->id)) }}" method="POST" id="qsheReviewForm{{ hashid_encode($data->id) }}">
                                @csrf
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Setuju / Tidak</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($data->id) }}" required>
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="approve">Setuju</option>
                                        <option value="reject">Tidak Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Review</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Submit PIC -->
            <div class="modal fade" id="submitProgress{{hashid_encode($data->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update Tindak Lanjut Laporan {{$data->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.submit.pic', hashid_encode($data->id)) }}" method="POST" id="submitProgress{{ hashid_encode($data->id) }}">
                                @csrf
                                <div class="col-md-12 mb-2">
                                    <label for="foto" class="form-label">Ambil Foto</label>
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        name="foto" 
                                        id="fotoInput" 
                                        accept="image/*" 
                                        capture="environment"
                                        onchange="compressAndPreviewFoto(event)">
                                    <input type="hidden" name="foto_base64" id="fotoBase64">
                                </div>

                                <div class="col-md-12 mb-2">
                                    <img id="previewImage" src="#" alt="Preview Foto" style="display: none; max-width: 100%; height: auto;" class="rounded shadow-sm"/>
                                </div>
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Status</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($data->id) }}" required>
                                        <option value="1">Selesai</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Tindak Lanjut</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal PIC -->
            <div class="modal fade" id="modalPIC{{hashid_encode($data->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Review Laporan {{$data->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.review.pic', hashid_encode($data->id)) }}" method="POST" id="qsheReviewForm{{ hashid_encode($data->id) }}">
                                @csrf
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Setuju / Tidak</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($data->id) }}" required>
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="approve">Setuju</option>
                                        <option value="reject">Tidak Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Review</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Submit PIC -->
            <div class="modal fade" id="submitProgress{{hashid_encode($data->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update Tindak Lanjut Laporan {{$data->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.submit.pic', hashid_encode($data->id)) }}" method="POST" id="submitProgress{{ hashid_encode($data->id) }}">
                                @csrf
                                <div class="col-md-12 mb-2">
                                    <label for="foto" class="form-label">Ambil Foto</label>
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        name="foto" 
                                        id="fotoInput" 
                                        accept="image/*" 
                                        capture="environment"
                                        onchange="compressAndPreviewFoto(event)">
                                    <input type="hidden" name="foto_base64" id="fotoBase64">
                                </div>

                                <div class="col-md-12 mb-2">
                                    <img id="previewImage" src="#" alt="Preview Foto" style="display: none; max-width: 100%; height: auto;" class="rounded shadow-sm"/>
                                </div>
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Status</label>
                                    <select name="action" class="form-control action-select" data-target="{{ hashid_encode($data->id) }}" required>
                                        <option value="1">Selesai</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Tindak Lanjut</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Review Progress PIC -->
            <div class="modal fade" id="progressPIC{{hashid_encode($data->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Review Tindak Lanjut Laporan {{$data->nomor_laporan}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.review-submit.pic', hashid_encode($data->id)) }}" method="POST" id="progressPIC{{ hashid_encode($data->id) }}">
                                @csrf
                                
                                <div class="mb-2">
                                    <label for="" class="form-label">Setuju / Tidak</label>
                                    <select name="is_approved" class="form-control action-select" data-target="{{ hashid_encode($data->id) }}" required>
                                        <option value="">-- Pilih Aksi --</option>
                                        <option value="1">Setuju</option>
                                        <option value="0">Tidak Setuju</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="" class="form-label">Catatan</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Kirim Review</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if ($user->role === 'admin'|| $user->role === 'qshe')
        <div class="col-xl-4 col-lg-4 mt-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Histori Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="position-relative ms-2">
                        <span class="position-absolute start-0  top-0 border border-dashed h-100"></span>
                        @foreach($history as $his)
                        @php
                            $isRejected = str_contains(strtolower($his->tipe), 'rejected');
                        @endphp
                        <div class="position-relative ps-4">
                            <div class="mb-4">
                                <span class="position-absolute start-0 avatar-sm translate-middle-x bg-light d-inline-flex align-items-center justify-content-center rounded-circle {{ $isRejected ? 'text-danger' : 'text-success' }} fs-20">
                                    @if($isRejected)
                                        <i class='bx bx-x-circle'></i> {{-- Ikon merah untuk rejected --}}
                                    @else
                                        <i class='bx bx-check-circle'></i> {{-- Default check --}}
                                    @endif
                                </span>
                                <div class="ms-2 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1 text-dark fw-medium fs-15">{{ $his->catatan }}</h5>
                                        <p class="mb-0">Status Laporan : {{ $his->tipe }}</p>
                                    </div>
                                    <p class="mb-0">{{ $his->tanggal }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
        @endif
        
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event handler untuk aksi QSHE (Setuju / Tidak Setuju)
            document.querySelectorAll('.action-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    const targetId = this.dataset.target;
                    const approvalDiv = document.getElementById('approvalFields' + targetId);
                    
                    if (this.value === 'approve') {
                        approvalDiv.classList.remove('d-none');
                    } else {
                        approvalDiv.classList.add('d-none');
                        // Optional: Kosongkan field divisi dan PIC jika ingin reset saat tidak setuju
                        approvalDiv.querySelector('select[name="division_id"]').value = "";
                    }
                });
            });
        });
    </script>
@endsection
