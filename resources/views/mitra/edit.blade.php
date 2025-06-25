@extends('layouts/layoutMaster')

@section('title', 'Edit Data Mitra')

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
<!-- Form Edit Data Mitra -->
<div class="col-xl mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Data Mitra</h5>
            <small class="text-muted float-end">Ubah data sesuai kebutuhan</small>
        </div>
        <div class="card-body">
            {{-- Form action mengarah ke rute update dengan ID user --}}
            <form action="{{ route('teknisi.update', $user->id) }}" method="POST">
                @csrf {{-- Token CSRF untuk keamanan --}}
                @method('PUT') {{-- Metode HTTP PUT untuk update --}}

                {{-- Input Nama Koordinator --}}
                <div class="mb-6">
                    <label class="form-label" for="nama">Nama Koordinator</label>
                    <input type="text" class="form-control" id="coordinator_name" name="coordinator_name"
                           placeholder="Masukkan Nama Lengkap" value="{{ old('coordinator_name', $user->coordinator_name) }}" required />
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Select Role --}}
                <div class="mb-6">
                    <label class="form-label" for="role" readonly>Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="" disabled>Pilih Role</option>
                        <option value="teknisi_lab" {{ old('role', $user->role) == 'teknisi_lab' ? 'selected' : '' }}>Teknisi Lab</option>
                        <option value="tenaga_ahli" {{ old('role', $user->role) == 'tenaga_ahli' ? 'selected' : '' }}>Tenaga Ahli</option>
                        {{-- Tambahkan 'admin' jika memungkinkan untuk diedit dari sini --}}
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Nama Koordinator --}}
                <div class="mb-6">
                    <label class="form-label" for="nama_koordinator">Nama Koordinator</label>
                    <input type="text" class="form-control" id="nama_koordinator" name="coordinator_name"
                           placeholder="Nama Koordinator" value="{{ old('coordinator_name', $user->coordinator_name) }}" />
                    @error('coordinator_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input No Telp --}}
                <div class="mb-6">
                    <label class="form-label" for="no_telp">No. Telepon</label>
                    {{-- Kelas 'phone-mask' digunakan oleh Cleave.js --}}
                    <input type="text" id="no_telp" name="phone" class="form-control phone-mask"
                           placeholder="Contoh: 0812 3456 7890" value="{{ old('phone', $user->phone) }}" />
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Select Status --}}
                <div class="mb-6">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="is_active" class="form-select" required>
                        <option value="" disabled>Pilih Status</option>
                        {{-- Perhatikan bahwa nilai status Anda adalah boolean (1 atau 0) --}}
                        <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('is_active')
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
