@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Verifikasi Email')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Verify Email -->
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
            <h4 class="mb-1">Verifikasi Email Anda ✉️</h4>
            <p class="mb-6">Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan? Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.</p>
          </div>

          @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
              Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
          @endif

          <div class="mt-6 d-flex justify-content-between">
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-label-danger">
                    Log Out
                </button>
            </form>
          </div>
        </div>
      </div>
      <!-- /Verify Email -->
    </div>
  </div>
</div>
@endsection