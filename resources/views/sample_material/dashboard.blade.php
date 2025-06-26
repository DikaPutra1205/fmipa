@extends('layouts/layoutMaster')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'Data Sampel & Material')

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
      getSampelMaterialData: "{{ route('sample_material.data') }}",
      createSampelMaterial: "{{ route('sample_material.create') }}",
      editSampelMaterial: "{{ route('sample_material.edit', '') }}",
      deleteSampelMaterial: "{{ route('sample_material.destroy', '') }}",
      csrfToken: "{{ csrf_token() }}" // CSRF token untuk permintaan AJAX DELETE
  };
</script>
@vite(['resources/assets/js/table-data-sample-material.js'])
@endsection

@section('content')

{{-- Button Tambah Data Sampel & Material --}}
<div class="demo-inline-spacing mb-3">
  <a href="{{ route('sample_material.create') }}" class="btn btn-primary waves-effect waves-light">
    <span class="ti-xs ti ti-plus me-2"></span> Tambah Data Sampel & Material
  </a>
</div>

{{-- Card Data Sampel & Material --}}
<div class="card">
  <h5 class="card-header">Data Sampel & Material</h5>
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Sampel & Material</th>
          <th>Jumlah Sampel</th>
          <th>Tanggal Penerimaan</th>
          <th>Tanggal Pengembalian</th>
          <th>Status</th>
          @if(Auth::user()->role === 'admin')
            <th>Aksi</th>
          @endif
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Data Sampel & Material -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data Sampel & Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Konten ini akan diisi secara dinamis oleh JavaScript --}}
        <p>Apakah Anda yakin ingin menghapus data <strong id="modalSampleName"></strong> (ID: <span id="modalSampleId"></span>)?</p>
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
