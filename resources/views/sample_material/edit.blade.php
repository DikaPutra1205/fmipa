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
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Form Edit Data Sampel & Material</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sample_material.update', $sampelMaterial->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label" for="test_id">ID Order Terkait</label>
                <input type="text" class="form-control" id="test_id" value="{{ $sampelMaterial->test_id ? '#' . $sampelMaterial->test_id : 'Manual Input' }}" disabled />
            </div>

            <div class="mb-3">
                <label class="form-label" for="nama_sampel_material">Nama Sampel</label>
                <input type="text" class="form-control" name="nama_sampel_material" value="{{ old('nama_sampel_material', $sampelMaterial->nama_sampel_material) }}" required />
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="jumlah_sampel">Jumlah Sampel</label>
                <input type="number" class="form-control" name="jumlah_sampel" value="{{ old('jumlah_sampel', $sampelMaterial->jumlah_sampel) }}" required min="1" />
            </div>

            <div class="mb-3">
                <label class="form-label" for="status">Status Sampel</label>
                <select id="status" name="status" class="form-select" required>
                    @foreach(['menunggu_kedatangan', 'diterima_di_lab', 'sedang_diuji', 'pengujian_selesai', 'selesai'] as $status)
                        <option value="{{ $status }}" {{ old('status', $sampelMaterial->status) == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="tanggal_penerimaan">Tanggal Penerimaan</label>
                {{-- [PERBAIKAN] Menggunakan type="text" dan format value yang benar --}}
                <input type="text" class="form-control" id="tanggal_penerimaan" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', $sampelMaterial->tanggal_penerimaan ? $sampelMaterial->tanggal_penerimaan->format('Y-m-d') : '') }}" placeholder="Pilih Tanggal" />
            </div>

            <div class="mb-3">
                <label class="form-label" for="tanggal_pengembalian">Tanggal Pengembalian (Opsional)</label>
                {{-- [PERBAIKAN] Menggunakan type="text" dan format value yang benar --}}
                <input type="text" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" value="{{ old('tanggal_pengembalian', $sampelMaterial->tanggal_pengembalian ? $sampelMaterial->tanggal_pengembalian->format('Y-m-d') : '') }}" placeholder="Pilih Tanggal" />
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('sample_material.dashboard') }}" class="btn btn-label-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
