@extends('layouts/layoutMaster')

@section('title', 'Tambah Data Mitra')

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
@vite(['resources/assets/js/form-layouts.js']) {{-- Pastikan form-layouts.js menginisialisasi cleave.js/select2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Cleave.js untuk input nomor telepon
        // Pastikan form-layouts.js atau script lain belum menginisialisasinya
        if (typeof Cleave !== 'undefined') {
            new Cleave('.phone-mask', {
                phone: true,
                phoneRegionCode: 'ID' // Contoh untuk format nomor telepon Indonesia
            });
        }

        // Inisialisasi Select2 (jika diperlukan untuk styling dropdown)
        // $('.select2').select2();
    });
</script>
@endsection

@section('content')
<!-- Form Tambah Data Mitra -->
<div class="col-xl mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Tambah Data Mitra</h5>
            <small class="text-muted float-end">Isi data dengan lengkap</small>
        </div>
        <div class="card-body">
            {{-- Sesuaikan action ke rute yang benar untuk menyimpan mitra --}}
            <form action="{{ route('mitra.store') }}" method="POST">
                @csrf {{-- Token CSRF untuk keamanan --}}

                {{-- Input Nama --}}
                <div class="mb-6">
                    <label class="form-label" for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="name" placeholder="Masukkan Nama Lengkap" required />
                </div>

                {{-- Input Email --}}
                <div class="mb-6">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Alamat Email" required />
                </div>

                {{-- Input Password (penting untuk akun pengguna) --}}
                <div class="mb-6">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required />
                </div>
                
                {{-- Input Nama Institusi --}}
                <div class="mb-6">
                    <label class="form-label" for="institution">Nama Institusi</label>
                    <input type="text" class="form-control" id="institution" name="institution" placeholder="Masukkan Nama Institusi" required />
                </div>

                {{-- Input Nama Koordinator --}}
                <div class="mb-6">
                    <label class="form-label" for="coordinator_name">Nama Koordinator</label>
                    <input type="text" class="form-control" id="coordinator_name" name="coordinator_name" placeholder="Masukkan Alamat Nama Koordinator" required />
                </div>

                {{-- Input No Telp --}}
                <div class="mb-6">
                    <label class="form-label" for="no_telp">No. Telepon</label>
                    {{-- Kelas 'phone-mask' digunakan oleh Cleave.js --}}
                    <input type="text" id="no_telp" name="phone" class="form-control phone-mask" placeholder="Contoh: 0812 3456 7890" />
                </div>

                {{-- Select Status --}}
                <div class="mb-6">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="is_active" class="form-select" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                {{-- Tombol Simpan --}}
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
@endsection
