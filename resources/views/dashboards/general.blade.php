@extends('layouts.vertical', ['title' => 'Dashboard'])
@section('css')
<!-- DataTables CSS -->
    <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="alert alert-info mb-3">
            Selamat Datang Kembali, <strong>{{auth()->check() ? auth()->user()->name : 'Guest'}}</strong> !
        </div>
        <div class="card">
            <div class="card-body">
                <a href="{{ route('laporan.create') }}" class="btn btn-sm btn-danger w-100">
                    <i class="bx bx-plus me-1"></i>Buat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

    <!-- ApexCharts & SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
@endsection
