@extends('layouts/layoutMaster')

@section('title', 'Edit Data Sampel & Material')

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
            // Anda bisa menambahkan opsi lain di sini
        });

        // Inisialisasi Flatpickr untuk tanggal pengembalian
        flatpickr("#tanggal_pengembalian", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            // Tambahkan opsi lain sesuai kebutuhan Anda
        });

        // Jika Anda menggunakan Select2 untuk styling dropdown lain
        // $('.select2').select2();
    });
</script>
@endsection

@section('content')
<!-- Form Edit Data Sampel & Material -->
<div class="col-xl mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Sampel & Material</h5>
            <small class="text-muted float-end">Ubah data sesuai kebutuhan</small>
        </div>
        <div class="card-body">
            {{-- Form action mengarah ke rute update dengan ID sampel material --}}
            <form action="{{ route('sample_material.update', $sampelMaterial->id) }}" method="POST">
                @csrf {{-- Token CSRF Laravel, wajib ada --}}
                @method('PUT') {{-- Metode HTTP PUT untuk operasi update --}}

                {{-- Input Nama Sampel & Material --}}
                <div class="mb-6">
                    <label class="form-label" for="nama_sampel_material">Nama Sampel & Material</label>
                    <input type="text" class="form-control" id="nama_sampel_material" name="nama_sampel_material"
                           placeholder="Contoh: Tanah Merah, Besi Beton"
                           value="{{ old('nama_sampel_material', $sampelMaterial->nama_sampel_material) }}" required />
                    @error('nama_sampel_material')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Jumlah Sampel --}}
                <div class="mb-6">
                    <label class="form-label" for="jumlah_sampel">Jumlah Sampel</label>
                    <input type="number" class="form-control" id="jumlah_sampel" name="jumlah_sampel"
                           placeholder="Contoh: 100"
                           value="{{ old('jumlah_sampel', $sampelMaterial->jumlah_sampel) }}" required min="0" />
                    @error('jumlah_sampel')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Tanggal Penerimaan --}}
                <div class="mb-6">
                    <label class="form-label" for="tanggal_penerimaan">Tanggal Penerimaan</label>
                    <input type="text" class="form-control" id="tanggal_penerimaan" name="tanggal_penerimaan"
                           placeholder="Pilih Tanggal Penerimaan"
                           value="{{ old('tanggal_penerimaan', $sampelMaterial->tanggal_penerimaan) }}" required />
                    @error('tanggal_penerimaan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Tanggal Pengembalian --}}
                <div class="mb-6">
                    <label class="form-label" for="tanggal_pengembalian">Tanggal Pengembalian (Opsional)</label>
                    <input type="text" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian"
                           placeholder="Pilih Tanggal Pengembalian"
                           value="{{ old('tanggal_pengembalian', $sampelMaterial->tanggal_pengembalian) }}" />
                    @error('tanggal_pengembalian')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Select Status Data --}}
                <div class="mb-6">
                    <label class="form-label" for="status_data">Status Data</label>
                    <select id="status_data" name="status_data" class="form-select" required>
                        <option value="" disabled>Pilih Status</option>
                        {{-- Menggunakan $sampelMaterial->status_data untuk pre-select opsi --}}
                        <option value="1" {{ old('status_data', $sampelMaterial->status_data) == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status_data', $sampelMaterial->status_data) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status_data')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Simpan Perubahan --}}
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
