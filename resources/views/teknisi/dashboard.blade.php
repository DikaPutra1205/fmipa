@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Data TA & Teknisi')

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

  // Definisi rute delete dan CSRF token untuk JavaScript
  window.routes = {
      deleteTeknisi: "{{ route('teknisi.destroy', '') }}",
      getTeknisiData: "{{ route('teknisi.data') }}",
      csrfToken: "{{ csrf_token() }}" // <--- Tambahkan ini
  };
</script>
@vite(['resources/assets/js/table-data-ta-teknisi.js'])
@endsection

@section('content')

{{-- Button Tambah Data TA & Teknisi --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('users.create') }}" class="btn btn-primary waves-effect waves-light">
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data TA & Teknisi
  </a>
</div>

{{-- Card Data Tenaga Ahli & Teknisi --}}
<div class="card">
  <h5 class="card-header">Data Tenaga Ahli & Teknisi</h5>
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Nama Koordinator</th>
          <th>No Telp</th>
          <th>Status</th>
          @if(Auth::user()->role === 'admin')
          <th>Aksi</th>
          @endif
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Data -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus data <strong id="modalUserName"></strong> (ID: <span id="modalUserId"></span>)?</p>
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
