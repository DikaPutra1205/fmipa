@php
$customizerHidden = 'customizer-hide';
use Illuminate\Support\Facades\Route; // [PERBAIKAN] Menambahkan use statement
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6" style="overflow: visible;">
            <a href="{{url('/')}}" class="app-brand-link">
              <span class="app-brand-logo demo" style="overflow: visible; max-height: none;">@include('_partials.macros',['height'=>50, 'withbg' => "fill: #666cff;"])</span>
            </a>
          </div>
          <!-- /Logo -->
          {{-- [PERUBAHAN] Menambahkan class text-center dan teks baru --}}
          <div class="text-center">
            <h4 class="mb-1">Selamat Datang Kembali!</h4>
            <p class="mb-6">Masuk untuk melanjutkan dan melacak status pengujian Anda.</p>
          </div>
          
          <!-- Session Status -->
          <x-auth-session-status class="mb-4" :status="session('status')" />

          <form id="formAuthentication" class="mb-4" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" autofocus required>
            </div>

            <!-- Password -->
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="my-8" >
              <div class="d-flex justify-content-between">
                <div class="form-check mb-0 ms-2">
                  <input id="remember_me" class="form-check-input" type="checkbox" name="remember">
                  <label class="form-check-label" for="remember_me">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                  <a href="{{ route('password.request') }}">
                    Lupa password?
                  </a>
                @endif
              </div>
            </div>

            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
            </div>
          </form>

          <p class="text-center">
            <span>Pengguna baru?</span>
            <a href="{{ route('register') }}">
              <span>Buat akun di sini</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
