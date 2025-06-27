@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
@endphp

{{-- Judul halaman akan dinamis berdasarkan modul yang dibuka --}}
@section('title', 'Dashboard Pengujian ' . $module->name)

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/jquery/jquery.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
])
@endsection

@section('page-script')
<script>
    // Mendefinisikan variabel global untuk JavaScript
    window.myApp = {
        role: "{{ $user->role ?? '' }}",
        getDataUrl: "{{ route('module.data', $module->code) }}"
    };
</script>
{{-- Kita akan membuat file JS terpisah untuk logika DataTables ini --}}
@vite(['resources/assets/js/module-dashboard.js'])
@endsection

@section('content')

{{-- Tombol untuk mengajukan pengujian baru --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="py-3 mb-0">
        <span class="text-muted fw-light">Modul /</span> Dashboard Pengujian {{ $module->name }}
    </h4>
    {{-- Tombol ini hanya muncul untuk Mitra --}}
    @if($user->role === 'mitra')
    <a href="{{ route('wizard.submission.create', $module->code) }}" class="btn btn-primary">
        <span class="ti-xs ti ti-plus me-2"></span>Ajukan Pengujian {{ $module->name }} Baru
    </a>
    @endif
</div>

{{-- Card untuk menampilkan DataTable riwayat pengujian --}}
<div class="card">
    <h5 class="card-header">Riwayat Pengujian {{ $module->name }}</h5>
    <div class="card-datatable text-nowrap">
        <table class="datatables-ajax table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Order</th>
                    {{-- Tampilkan kolom Mitra jika bukan Mitra yang login --}}
                    @if($user->role !== 'mitra')
                    <th>Nama Mitra</th>
                    @endif
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
