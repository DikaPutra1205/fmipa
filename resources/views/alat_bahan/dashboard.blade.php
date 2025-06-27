@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Data Alat & Bahan')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/jquery/jquery.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/vendor/libs/moment/moment.js',
'resources/assets/vendor/libs/flatpickr/flatpickr.js'
])
@endsection

@section('page-script')
<script>
  // Definisi userRole untuk JavaScript (digunakan di kolom 'Aksi' DataTables)
  window.userRole = "{{ Auth::user()->role ?? '' }}";

  // Definisi rute yang akan digunakan di JavaScript
  window.routes = {
      getAlatBahanData: "{{ route('alat_bahan.data') }}",
      createAlatBahan: "{{ route('alat_bahan.create') }}",
      editAlatBahan: "{{ route('alat_bahan.edit', '') }}", // ID akan ditambahkan JS
      deleteAlatBahan: "{{ route('alat_bahan.destroy', '') }}", // ID akan ditambahkan JS
      csrfToken: "{{ csrf_token() }}" // CSRF token untuk permintaan AJAX DELETE
  };
</script>
@vite(['resources/assets/js/table-data-alat-bahan.js'])
@endsection

@section('content')

@can('admin')
{{-- Button Tambah Data Alat & Bahan --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('alat_bahan.create') }}" class="btn btn-primary waves-effect waves-light">
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data Alat & Bahan
  </a>
</div>
@endcan

{{-- Deskripsi Halaman --}}

{{-- Card Data Alat & Bahan --}}
<div class="card">
  <h5 class="card-header">Data Alat & Bahan</h5>
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Alat & Bahan</th>
          <th>Kondisi Alat</th>
          <th>Jumlah Alat</th>
          <th>Status Data</th>
          @if(Auth::user()->role === 'admin')
            <th>Aksi</th>
          @endif
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Data Alat & Bahan -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data Alat & Bahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Konten ini akan diisi secara dinamis oleh JavaScript --}}
        <p>Apakah Anda yakin ingin menghapus data <strong id="modalAlatBahanName"></strong> (ID: <span id="modalAlatBahanId"></span>)?</p>
        <p class="text-danger">Data yang sudah dihapus tidak dapat dikembalikan.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Hapus Data</button>
      </div>
    </div>
  </div>
</div>
@endsection
