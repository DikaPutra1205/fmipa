@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Register Mitra Baru')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">

      <!-- Register Card -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6" style="overflow: visible;">
            <a href="{{url('/')}}" class="app-brand-link">
                <span class="app-brand-logo demo" style="overflow: visible; max-height: none;">@include('_partials.macros',['height'=>50, 'withbg' => "fill: #666cff;"])</span>
            </a>
          </div>
          <!-- /Logo -->
          {{-- [PERUBAHAN] Menambahkan class text-center --}}
          <div class="text-center">
            <h4 class="mb-1">Pendaftaran Mitra Baru</h4>
            <p class="mb-6">Silakan isi data berikut untuk membuat akun.</p>
          </div>

          <form id="formAuthentication" class="mb-4" action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-6">
              <label for="name" class="form-label">Nama Lengkap Koordinator</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap Koordinator" value="{{ old('name') }}" required autofocus>
              <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
            </div>

            <!-- Institution -->
            <div class="mb-6">
              <label for="institution" class="form-label">Asal Institusi / Perusahaan</label>
              <input type="text" class="form-control" id="institution" name="institution" placeholder="Contoh: Universitas Indonesia" value="{{ old('institution') }}" required>
              <x-input-error :messages="$errors->get('institution')" class="mt-2 text-danger" />
            </div>

            <!-- Phone -->
            <div class="mb-6">
              <label for="phone" class="form-label">Nomor Telepon</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon aktif" value="{{ old('phone') }}" required>
              <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger" />
            </div>

            <!-- Email -->
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>

            <!-- Password -->
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autocomplete="new-password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
            </div>
            
            <!-- Confirm Password -->
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autocomplete="new-password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
            </div>

            <button class="btn btn-primary d-grid w-100">
              Daftar
            </button>
          </form>

          <p class="text-center">
            <span>Sudah punya akun?</span>
            <a href="{{ route('login') }}">
              <span>Masuk di sini</span>
            </a>
          </p>
        </div>
      </div>
      <!-- /Register Card -->
    </div>
  </div>
</div>
@endsection