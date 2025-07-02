{{-- File: resources/views/dashboard/mitra.blade.php --}}

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Mitra')

@section('page-style')
<style>
    .card-module {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-module:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
</style>
@endsection

@section('content')
<div class="text-center mb-5">
    <h3 class="mb-1">Selamat Datang, {{ auth()->user()->name }}!</h3>
    <p class="text-muted">Siap untuk memulai pengujian? Pilih salah satu layanan di bawah ini.</p>
</div>

<div class="row justify-content-center">
    @foreach ($modules as $module)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card card-module h-100">
            <a href="{{ route('module.show', $module->code) }}">
                {{-- [PERUBAHAN] Menggunakan $module->image_url dari database --}}
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" 
                     style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ $module->image_url ?? 'https://source.unsplash.com/400x300/?science' }}'); background-size: cover; background-position: center; min-height: 200px; border-radius: 0.375rem;">
                    <h4 class="card-title text-white">{{ $module->name }}</h4>
                    <p class="card-text text-white-50">{{ $module->description }}</p>
                </div>
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection