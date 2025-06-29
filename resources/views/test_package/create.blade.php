@extends('layouts/layoutMaster')

@section('title', 'Tambah Data Paket Pengujian')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

@section('page-script')
@vite(['resources/assets/js/form-layouts.js'])
@endsection

@section('content')
<div class="col-xl mb-6">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Form Tambah Data Paket Pengujian</h5>
      <small class="text-muted float-end">Isi data dengan lengkap</small>
    </div>
    <div class="card-body">
      <form action="{{ route('test_package.store') }}" method="POST">
        @csrf

        {{-- Pilih Nama Modul --}}
        <div class="mb-3">
          <label class="form-label" for="module_id">Nama Modul</label>
          <select class="form-select" id="module_id" name="module_id" required>
            <option value="" disabled selected>Pilih Modul</option>
            @foreach($modules as $module)
              <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                {{ $module->name }}
              </option>
            @endforeach
          </select>
          @error('module_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        {{-- Nama Paket Pengujian --}}
        <div class="mb-3">
          <label class="form-label" for="name">Nama Paket Pengujian</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Pengujian + Analisis" value="{{ old('name') }}" required>
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        {{-- Harga --}}
        <div class="mb-3">
          <label class="form-label" for="price">Harga</label>
          <input type="number" class="form-control" id="price" name="price" placeholder="Contoh: 50000" value="{{ old('price') }}" required min="0">
          @error('price')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </form>
    </div>
  </div>
</div>
@endsection
