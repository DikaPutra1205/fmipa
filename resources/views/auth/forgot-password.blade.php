@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Lupa Password')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Forgot Password -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6" style="overflow: visible;">
            <a href="{{url('/')}}" class="app-brand-link">
              <span class="app-brand-logo demo" style="overflow: visible; max-height: none;">@include('_partials.macros',['height'=>50, 'withbg' => "fill: #666cff;"])</span>
            </a>
          </div>
          <!-- /Logo -->
          <div class="text-center">
            <h4 class="mb-1">Lupa Password Anda? 🔒</h4>
            <p class="mb-6">Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
          </div>

          <!-- Session Status -->
          <x-auth-session-status class="mb-4" :status="session('status')" />

          <form id="formAuthentication" class="mb-4" action="{{ route('password.email') }}" method="POST">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus />
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>

            <button class="btn btn-primary d-grid w-100" type="submit">Kirim Link Reset</button>
          </form>

          <div class="text-center">
            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
              <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
              Kembali ke halaman login
            </a>
          </div>
        </div>
      </div>
      <!-- /Forgot Password -->
    </div>
  </div>
</div>
@endsection