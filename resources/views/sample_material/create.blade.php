@extends('layouts/layoutMaster')

@section('title', 'Tambah Data Sampel & Material')

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
@vite(['resources/assets/js/form-layouts.js']) {{-- Pastikan form-layouts.js menginisialisasi flatpickr/select2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Flatpickr untuk tanggal penerimaan
        flatpickr("#tanggal_penerimaan", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            // Anda bisa menambahkan opsi lain di sini, seperti:
            // maxDate: "today" // Mencegah pemilihan tanggal di masa depan
        });

        // Inisialisasi Flatpickr untuk tanggal pengembalian
        flatpickr("#tanggal_pengembalian", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            // Tambahkan opsi lain sesuai kebutuhan Anda, misalnya:
            // minDate: "today" // Mencegah pemilihan tanggal di masa lalu
        });

        // Jika Anda menggunakan Select2 untuk styling dropdown lain (selain status)
        // $('.select2').select2();
    });
</script>
@endsection

@section('content')
<!-- Form Tambah Data Sampel & Material -->
<div class="col-xl mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Tambah Data Sampel & Material</h5>
            <small class="text-muted float-end">Isi data dengan lengkap</small>
        </div>
        <div class="card-body">
            <form action="{{ route('sample_material.store') }}" method="POST">
                @csrf {{-- Token CSRF Laravel, wajib ada untuk form POST --}}

                {{-- Input Nama Sampel & Material --}}
                <div class="mb-6">
                    <label class="form-label" for="nama_sampel_material">Nama Sampel & Material</label>
                    <input type="text" class="form-control" id="nama_sampel_material" name="nama_sampel_material"
                           placeholder="Contoh: Tanah Merah, Besi Beton" value="{{ old('nama_sampel_material') }}" required />
                    @error('nama_sampel_material')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Jumlah Sampel --}}
                <div class="mb-6">
                    <label class="form-label" for="jumlah_sampel">Jumlah Sampel</label>
                    <input type="number" class="form-control" id="jumlah_sampel" name="jumlah_sampel"
                           placeholder="Contoh: 100" value="{{ old('jumlah_sampel') }}" required min="0" />
                    @error('jumlah_sampel')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Tanggal Penerimaan --}}
                <div class="mb-6">
                    <label class="form-label" for="tanggal_penerimaan">Tanggal Penerimaan</label>
                    <input type="text" class="form-control" id="tanggal_penerimaan" name="tanggal_penerimaan"
                           placeholder="Pilih Tanggal Penerimaan" value="{{ old('tanggal_penerimaan') }}" required />
                    @error('tanggal_penerimaan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Tanggal Pengembalian --}}
                <div class="mb-6">
                    <label class="form-label" for="tanggal_pengembalian">Tanggal Pengembalian (Opsional)</label>
                    <input type="text" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian"
                           placeholder="Pilih Tanggal Pengembalian" value="{{ old('tanggal_pengembalian') }}" />
                    @error('tanggal_pengembalian')
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
                <button type="submit" class="btn btn-primary">Simpan Data Sampel</button>
            </form>
        </div>
    </div>
</div>
@endsection
