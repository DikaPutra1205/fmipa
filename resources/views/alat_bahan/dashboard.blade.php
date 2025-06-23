@extends('layouts/layoutMaster')

@section('title', 'Data TA & Teknisi')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/jquery/jquery.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/vendor/libs/moment/moment.js',
'resources/assets/vendor/libs/flatpickr/flatpickr.js'
])
@endsection

@section('page-script')
@vite(['resources/assets/js/table-data-alat-bahan.js'])
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
@endphp

{{-- Definisi userRole untuk JavaScript --}}
<script>
    window.userRole = "{{ Auth::user()->role ?? '' }}"; // Pastikan userRole tersedia
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.datatables-ajax').forEach(function(table) {
      table.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-user');
        if (!btn) return;
  
        const userId = btn.getAttribute('data-id');
        if (!userId) return;
  
        if (confirm('Yakin ingin menghapus user ini?')) {
          fetch('/user/' + userId, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            }
          })
          .then(res => {
            if (!res.ok) throw new Error('Gagal hapus user');
            return res.json();
          })
          .then(() => {
            alert('User berhasil dihapus');
            // Reload datatables pakai instance
            if (window.userTable) {
              window.userTable.ajax.reload();
            } else {
              location.reload(); // fallback
            }
          })
          .catch(() => {
            alert('Gagal menghapus user');
          });
        }
      });
    });
  });
  </script>
  
<div class="card">
    <h5 class="card-header">Data Alat & Bahan</h5>
    <div class="card-datatable text-nowrap">
        <table class="datatables-ajax table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat & Bahan</th>
                    <th>Kondisi Alat</th>
                    <th>Jumlah Alat</th>
                    <th>Status Data</th>
                    @if(Auth::user()->role === 'admin')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
        </table>
        
    </div>
</div>
@endsection