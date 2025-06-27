@extends('layouts/layoutMaster')

@section('title', 'Tambah Data Layanan')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
'resources/assets/vendor/libs/cleavejs/cleave.js',
'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
'resources/assets/vendor/libs/moment/moment.js',
'resources/assets/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/form-layouts.js']) {{-- Pastikan form-layouts.js menginisialisasi flatpickr/select2 jika diperlukan --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Flatpickr jika ada input tanggal di masa depan
        // flatpickr("#id_tanggal_input", { /* opsi flatpickr */ });

        // Inisialisasi Select2 jika ada dropdown yang menggunakan Select2
        // $('.select2').select2();
    });
</script>
@endsection

@section('content')
<!-- Form Tambah Data Layanan -->
<div class="col-xl mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Tambah Data Layanan</h5>
            <small class="text-muted float-end">Isi data layanan baru dengan lengkap</small>
        </div>
        <div class="card-body">
            {{-- Form akan mengirim data ke route 'services.store' --}}
            <form action="{{ route('services.store') }}" method="POST">
                @csrf {{-- Token CSRF Laravel, wajib ada untuk form POST --}}

                {{-- Input ID Modul --}}
                <div class="mb-6">
                    <label class="form-label" for="module_id">ID Modul</label>
                    <input type="number" class="form-control" id="module_id" name="module_id"
                           placeholder="Contoh: 1" value="{{ old('module_id') }}" required min="1" />
                    @error('module_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Nama Layanan --}}
                <div class="mb-6">
                    <label class="form-label" for="name">Nama Layanan</label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="Contoh: Pengujian Saja, Pengujian + Analisis" value="{{ old('name') }}" required />
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Harga --}}
                <div class="mb-6">
                    <label class="form-label" for="price">Harga (Rp)</label>
                    <input type="number" class="form-control" id="price" name="price"
                           placeholder="Contoh: 250000" value="{{ old('price') }}" required min="0" step="0.01" />
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Simpan --}}
                <button type="submit" class="btn btn-primary">Simpan Data Layanan</button>
            </form>
        </div>
    </div>
</div>
@endsection
