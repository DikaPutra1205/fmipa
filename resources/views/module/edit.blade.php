@extends('layouts/layoutMaster')

@section('title', 'Edit Data Module')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/cleavejs/cleave.js',
'resources/assets/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

@section('page-script')
@vite(['resources/assets/js/form-layouts.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
  const jenisSampelWrapper = document.getElementById('jenis-sampel-wrapper');
  const tambahBtn = document.getElementById('tambah-sampel');

  tambahBtn.addEventListener('click', function () {
    const index = jenisSampelWrapper.children.length;
    const html = `
      <div class="row g-3 align-items-center mb-2">
        <div class="col-md-5">
          <input type="text" name="details[jenis_sampel][${index}][tipe]" class="form-control" placeholder="Tipe (misal: Powder)" required>
        </div>
        <div class="col-md-5">
          <input type="text" name="details[jenis_sampel][${index}][spek]" class="form-control" placeholder="Spesifikasi (misal: 0.2 gram)" required>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">Hapus</button>
        </div>
      </div>`;
    jenisSampelWrapper.insertAdjacentHTML('beforeend', html);
  });
});
</script>
@endsection

@section('content')
<div class="col-xl mb-6">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Edit Data Module</h5>
      <small class="text-muted float-end">Perbarui data dengan benar</small>
    </div>
    <div class="card-body">
      <form action="{{ route('module.update', $Module->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label" for="code">Nomor Kode</label>
          <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $Module->code) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="name">Nama Module</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $Module->name) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="description">Deskripsi Singkat</label>
          <textarea class="form-control" id="description" name="description" rows="2" required>{{ old('description', $Module->description) }}</textarea>
        </div>

        @php
          $details = is_array($Module->details) ? $Module->details : json_decode($Module->details, true);
        @endphp

        <hr>
        <h6 class="fw-semibold mb-2">Detail</h6>

        <div class="mb-3">
          <label class="form-label" for="alat">Alat</label>
          <input type="text" class="form-control" name="details[alat]" id="alat" value="{{ old('details.alat', $details['alat'] ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="metode">Metode</label>
          <input type="text" class="form-control" name="details[metode]" id="metode" value="{{ old('details.metode', $details['metode'] ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="deskripsi_lengkap">Deskripsi Lengkap</label>
          <textarea class="form-control" name="details[deskripsi_lengkap]" id="deskripsi_lengkap" rows="3" required>{{ old('details.deskripsi_lengkap', $details['deskripsi_lengkap'] ?? '') }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Jenis Sampel</label>
          <div id="jenis-sampel-wrapper">
            @if(!empty($details['jenis_sampel']) && is_array($details['jenis_sampel']))
              @foreach($details['jenis_sampel'] as $i => $sampel)
              <div class="row g-3 align-items-center mb-2">
                <div class="col-md-5">
                  <input type="text" name="details[jenis_sampel][{{ $i }}][tipe]" class="form-control" placeholder="Tipe" value="{{ $sampel['tipe'] ?? '' }}" required>
                </div>
                <div class="col-md-5">
                  <input type="text" name="details[jenis_sampel][{{ $i }}][spek]" class="form-control" placeholder="Spesifikasi" value="{{ $sampel['spek'] ?? '' }}" required>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">Hapus</button>
                </div>
              </div>
              @endforeach
            @endif
          </div>
          <button type="button" class="btn btn-sm btn-secondary mt-2" id="tambah-sampel">+ Tambah Jenis Sampel</button>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
@endsection
