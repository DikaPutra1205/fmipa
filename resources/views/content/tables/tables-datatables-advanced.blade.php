@extends('layouts/layoutMaster')

@section('title', 'DataTables - Advanced Tables')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/vendor/libs/moment/moment.js',
'resources/assets/vendor/libs/flatpickr/flatpickr.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/tables-datatables-advanced.js'])
@endsection

@section('content')
<!-- Ajax Sourced Server-side -->
<div class="card">
    <h5 class="card-header">Ajax Sourced Server-side</h5>
    <div class="card-datatable text-nowrap">
        <table class="datatables-ajax table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Institusi</th>
                    <th>Nama Koordinator</th>
                    <th>Telp WA</th>
                    <th>Status Data</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection