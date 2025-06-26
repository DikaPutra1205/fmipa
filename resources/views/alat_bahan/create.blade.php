@extends('layouts/layoutMaster')

@section('title', 'Tambah Data Alat & Bahan')

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
@vite(['resources/assets/js/form-layouts.js']) {{-- Pastikan form-layouts.js menangani inisialisasi umum --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Contoh inisialisasi Select2 jika 'kondisi_alat' ingin menjadi dropdown Select2
        // Atau jika ada dropdown lain yang memerlukan Select2
        // $('#kondisi_alat').select2({
        //     placeholder: "Pilih Kondisi Alat",
        //     allowClear: true
        // });
        // Anda bisa tambahkan opsi lain seperti 'Rusak', 'Perbaikan', dll.
    });
</script>
@endsection

@section('content')
<!-- Form Tambah Data Alat & Bahan -->
<div class="col-xl mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Tambah Data Alat & Bahan</h5>
            <small class="text-muted float-end">Isi data dengan lengkap</small>
        </div>
        <div class="card-body">
            <form action="{{ route('alat_bahan.store') }}" method="POST">
                @csrf {{-- Token CSRF Laravel, wajib ada untuk form POST --}}

                {{-- Input Nama Alat & Bahan --}}
                <div class="mb-6">
                    <label class="form-label" for="nama_alat_bahan">Nama Alat & Bahan</label>
                    <input type="text" class="form-control" id="nama_alat_bahan" name="nama_alat_bahan"
                           placeholder="Contoh: Mikroskop, Tabung Reaksi" value="{{ old('nama_alat_bahan') }}" required />
                    @error('nama_alat_bahan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Kondisi Alat --}}
                <div class="mb-6">
                    <label class="form-label" for="kondisi_alat">Kondisi Alat</label>
                    {{-- Anda bisa gunakan input teks sederhana atau select jika kondisinya terbatas --}}
                    <input type="text" class="form-control" id="kondisi_alat" name="kondisi_alat"
                           placeholder="Contoh: Baik, Rusak, Perbaikan" value="{{ old('kondisi_alat') }}" required />
                    {{-- Atau jika menggunakan select (contoh):
                    <select id="kondisi_alat" name="kondisi_alat" class="form-select" required>
                        <option value="" disabled selected>Pilih Kondisi</option>
                        <option value="Baik" {{ old('kondisi_alat') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak" {{ old('kondisi_alat') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="Perbaikan" {{ old('kondisi_alat') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    --}}
                    @error('kondisi_alat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Jumlah Alat --}}
                <div class="mb-6">
                    <label class="form-label" for="jumlah_alat">Jumlah Alat</label>
                    <input type="number" class="form-control" id="jumlah_alat" name="jumlah_alat"
                           placeholder="Contoh: 5" value="{{ old('jumlah_alat') }}" required min="0" />
                    @error('jumlah_alat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Select Status Data --}}
                <div class="mb-6">
                    <label class="form-label" for="status_data">Status Data</label>
                    <select id="status_data" name="status_data" class="form-select" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="1" {{ old('status_data') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status_data') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status_data')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Simpan --}}
                <button type="submit" class="btn btn-primary">Simpan Data Alat & Bahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
