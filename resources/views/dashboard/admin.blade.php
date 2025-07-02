{{-- File: resources/views/dashboard/admin.blade.php --}}

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Admin')

@section('content')
<h4 class="py-3 mb-4">Dashboard Utama</h4>

{{-- Baris untuk Statistik Umum --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Total Teknisi & Ahli</span>
                        <div class="d-flex align-items-end h3 mb-0">
                            <h4 class="mb-0">{{ $totalTeknisi + $totalAhli }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-school ti-24px"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Total Mitra</span>
                        <div class="d-flex align-items-end h3 mb-0">
                            <h4 class="mb-0">{{ $totalMitra }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-building-bank ti-24px"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Pendapatan Bulan Ini</span>
                        <div class="d-flex align-items-end h3 mb-0">
                            <h4 class="mb-0">Rp{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success"><i class="ti ti-calendar-dollar ti-24px"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">Pendapatan Tahun Ini</span>
                        <div class="d-flex align-items-end h3 mb-0">
                            <h4 class="mb-0">Rp{{ number_format($pendapatanTahunIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info"><i class="ti ti-report-money ti-24px"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel untuk Pengujian yang Sedang Berjalan --}}
<div class="card">
    <h5 class="card-header">Pengujian Sedang Berjalan</h5>
    <div class="table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>ID Order</th>
                    <th>Mitra</th>
                    <th>Pengujian</th>
                    <th>Status</th>
                    <th>Progres</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($ongoingTests as $test)
                    <tr>
                        <td><strong>#{{ $test->id }}</strong></td>
                        <td>{{ $test->mitra->name }}</td>
                        <td>{{ $test->module->name }}</td>
                        <td>
                            @php
                                $statusText = ucwords(str_replace('_', ' ', $test->status));
                                $progress = $statusProgress[$test->status] ?? 0;
                            @endphp
                            <span class="badge bg-label-primary me-1">{{ $statusText }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress w-100 me-2" style="height: 8px;">
                                    <div class="progress-bar" style="width: {{ $progress }}%" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span>{{ $progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('wizard.dispatcher', $test->id) }}" class="btn btn-sm btn-label-primary">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada pengujian yang sedang berjalan saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection