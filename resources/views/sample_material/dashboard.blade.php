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
  window.routes = {
      getSampelData: "{{ route('sample_material.data') }}",
      deleteSampel: "{{ route('sample_material.destroy', '') }}",
      csrfToken: "{{ csrf_token() }}"
  };
</script>
@vite(['resources/assets/js/table-data-sample-material.js'])
@endsection

@section('content')

{{-- [BARU] Tombol Tambah Data Sampel & Material ditambahkan kembali --}}
@can('admin')
<div class="d-flex justify-content-end mb-3">
  <a href="{{ route('sample_material.create') }}" class="btn btn-primary">
    <span class="ti-xs ti ti-plus me-2"></span>Tambah Sampel Manual
  </a>
</div>
@endcan

<div class="card">
  <h5 class="card-header">Manajemen Sampel & Material</h5>
  <div class="card-body">Halaman ini menampilkan semua sampel yang terdaftar dalam sistem, baik yang dibuat melalui wizard pengujian maupun yang ditambahkan secara manual.
  </div>
  <div class="card-datatable text-nowrap">
    <table class="datatables-ajax table table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>ID Order</th>
          <th>Nama Mitra</th>
          <th>Nama Sampel</th>
          <th>Jumlah</th>
          <th>Tgl Diterima</th>
          <th>Status Sampel</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Hapus Data Sampel -->
{{-- (Kode Modal Tetap Sama) --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus data sampel <strong id="modalSampleName"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Hapus</button>
      </div>
    </div>
  </div>
</div>
@endsection
