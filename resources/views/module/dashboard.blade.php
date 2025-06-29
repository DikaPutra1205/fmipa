@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
@endphp

<<<<<<< HEAD
@section('title', 'Data Modul') {{-- Diubah dari Data Layanan (Test Package) --}}
=======
@section('title', 'Data Module')
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17

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
<<<<<<< HEAD
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
=======
  // Definisi userRole untuk JavaScript (digunakan di kolom 'Aksi' DataTables)
  window.userRole = "{{ Auth::user()->role ?? '' }}";

  // Definisi rute yang akan digunakan di JavaScript
  window.routes = {
      getModuleData: "{{ route('module.view') }}",
      createModule: "{{ route('module.create') }}",
      editModule: "{{ route('module.edit', '') }}", // ID akan ditambahkan JS
      deletemodule: '{{ url("module") }}',
      csrfToken: '{{ csrf_token() }}',
      userRole: '{{ auth()->user()->role }}'
  };
</script>
<style>
  .detail-cell {
      max-width: 400px;
      word-wrap: break-word;
      white-space: normal;
      padding: 10px;
      line-height: 1.5em;
  }
</style>

@vite(['resources/assets/js/table-data-module.js'])
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
@endsection

@section('content')

<<<<<<< HEAD
{{-- Button Tambah Data Modul --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('module.create') }}" class="btn btn-primary waves-effect waves-light"> {{-- Diubah dari test_package.create --}}
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data Modul
  </a>
</div>

{{-- Card Data Modul --}}
<div class="card">
  <h5 class="card-header">Data Modul</h5>
=======
@can('admin')
{{-- Button Tambah Data Module --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('module.create') }}" class="btn btn-primary waves-effect waves-light">
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data Module
  </a>
</div>
@endcan

{{-- Deskripsi Halaman --}}

{{-- Card Data Module --}}
<div class="card">
  <h5 class="card-header">Data Module</h5>
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table">
      <thead>
        <tr>
          <th>No</th>
<<<<<<< HEAD
          <th>Kode</th>
          <th>Nama</th>
          <th>Deskripsi</th>
=======
          <th>Nomor Kode</th>
          <th>Nama Module</th>
          <th>Deskripsi</th>
          <th>Detail</th>
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
          @if(Auth::user()->role === 'admin')
            <th>Aksi</th>
          @endif
        </tr>
      </thead>
    </table>
  </div>
</div>

<<<<<<< HEAD
<!-- Modal Konfirmasi Hapus Data Modul -->
=======
<!-- Modal Konfirmasi Hapus Data Module -->
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
<<<<<<< HEAD
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data Modul</h5> {{-- Diubah dari Data Layanan --}}
=======
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data Module</h5>
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Konten ini akan diisi secara dinamis oleh JavaScript --}}
<<<<<<< HEAD
        <p>Apakah Anda yakin ingin menghapus data modul <strong id="modalModuleName"></strong> (ID: <span id="modalModuleId"></span>)?</p> {{-- Diubah dari modalServiceName dan modalServiceId --}}
=======
        <p>Apakah Anda yakin ingin menghapus data <strong id="modalModuleName"></strong> (ID: <span id="modalModuleId"></span>)?</p>
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
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
