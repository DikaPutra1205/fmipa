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
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Sampel Manual</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sample_material.store') }}" method="POST">
            @csrf
            <div class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Sampel yang dibuat di sini tidak akan terhubung ke order pengujian manapun. Gunakan form ini hanya untuk keperluan inventaris.
            </div>

            <div class="mb-3">
                <label class="form-label" for="nama_sampel_material">Nama Sampel</label>
                <input type="text" class="form-control" name="nama_sampel_material" value="{{ old('nama_sampel_material') }}" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="jumlah_sampel">Jumlah Sampel</label>
                <input type="number" class="form-control" name="jumlah_sampel" value="{{ old('jumlah_sampel', 1) }}" required min="1" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="status">Status Sampel</label>
                <select name="status" class="form-select" required>
                    @foreach(['menunggu_kedatangan', 'diterima_di_lab', 'sedang_diuji', 'pengujian_selesai', 'selesai'] as $status)
                        <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="tanggal_penerimaan">Tanggal Penerimaan (Opsional)</label>
                <input type="date" class="form-control" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan') }}" />
            </div>

            <button type="submit" class="btn btn-primary">Simpan Sampel</button>
            <a href="{{ route('sample_material.dashboard') }}" class="btn btn-label-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
