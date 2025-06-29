@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Data Paket Pengujian')

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
      getTestPackageData: "{{ route('test_package.data') }}",
      createTestPackage: "{{ route('test_package.create') }}",
      editTestPackage: "{{ route('test_package.edit', '') }}", // ID akan ditambahkan JS
      deleteTestPackage: "{{ route('test_package.destroy', '') }}", // Nanti ID ditambahkan
      csrfToken: "{{ csrf_token() }}" // CSRF token untuk permintaan AJAX DELETE
  };
</script>
@vite(['resources/assets/js/table-data-test-package.js'])
@endsection

@section('content')

@can('admin')
{{-- Button Tambah Data Paket Pengujian --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('test_package.create') }}" class="btn btn-primary waves-effect waves-light">
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data Paket Pengujian
  </a>
</div>
@endcan

{{-- Deskripsi Halaman --}}

{{-- Card Data Paket Pengujian --}}
<div class="card">
  <h5 class="card-header">Data Paket Pengujian</h5>
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Module</th>
          <th>Paket Pengujian</th>
          <th>Harga</th>
          @if(Auth::user()->role === 'admin')
            <th>Aksi</th>
          @endif
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus paket <strong id="modalDeleteName"></strong>?</p>
        <p class="text-danger">Tindakan ini tidak bisa dibatalkan.</p>
        <input type="hidden" id="modalDeleteId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Hapus</button>
      </div>
    </div>
  </div>
</div>

@endsection