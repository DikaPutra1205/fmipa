@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
@endphp

@section('title', 'Arsip Hasil Uji Material')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('page-script')
<script>
    window.routes = {
        getHasilUjiData: "{{ route('hasil_uji.data') }}"
    };
    window.userRole = "{{ $user->role }}";
</script>
@vite(['resources/assets/js/table-data-hasil-uji.js'])
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Arsip /</span> Hasil Uji Material
</h4>

<div class="card">
    <h5 class="card-header">Daftar Hasil Uji Material</h5>
    <div class="card-body">
        <p class="text-muted">Halaman ini berisi semua riwayat hasil pengujian yang telah selesai dan siap untuk diunduh.</p>
    </div>
    <div class="card-datatable text-nowrap">
        <table class="datatables-ajax table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Order</th>
                    @if($user->role !== 'mitra')
                        <th>Nama Mitra</th>
                    @endif
                    <th>Modul Pengujian</th>
                    <th>Paket Layanan</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection