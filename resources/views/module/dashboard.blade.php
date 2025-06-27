@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Data Modul') {{-- Diubah dari Data Layanan (Test Package) --}}

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
  // Definisi userRole untuk JavaScript
  window.userRole = "{{ Auth::user()->role ?? '' }}";

  // Definisi rute yang akan digunakan di JavaScript (penting untuk AJAX dan link)
  window.routes = {
      // Mengarahkan ke route 'module.data'
      getModuleData: "{{ route('module.data') }}", {{-- Diubah dari getServiceData --}}
      createModule: "{{ route('module.create') }}", {{-- Diubah dari createService --}}
      editModule: "{{ route('module.edit', '') }}", // ID akan ditambahkan oleh JS {{-- Diubah dari editService --}}
      deleteModule: "{{ route('module.destroy', '') }}", // ID akan ditambahkan oleh JS {{-- Diubah dari deleteService --}}
      csrfToken: "{{ csrf_token() }}" // CSRF token untuk permintaan AJAX DELETE
  };
</script>
{{-- Menggunakan file JS baru: resources/assets/js/table-data-module.js --}}
@vite(['resources/assets/js/table-data-module.js']) {{-- Diubah dari table-data-test-package.js --}}
@endsection

@section('content')

{{-- Button Tambah Data Modul --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('module.create') }}" class="btn btn-primary waves-effect waves-light"> {{-- Diubah dari test_package.create --}}
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data Modul
  </a>
</div>

{{-- Card Data Modul --}}
<div class="card">
  <h5 class="card-header">Data Modul</h5>
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>Deskripsi</th>
          @if(Auth::user()->role === 'admin')
            <th>Aksi</th>
          @endif
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Data Modul -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data Modul</h5> {{-- Diubah dari Data Layanan --}}
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Konten ini akan diisi secara dinamis oleh JavaScript --}}
        <p>Apakah Anda yakin ingin menghapus data modul <strong id="modalModuleName"></strong> (ID: <span id="modalModuleId"></span>)?</p> {{-- Diubah dari modalServiceName dan modalServiceId --}}
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
