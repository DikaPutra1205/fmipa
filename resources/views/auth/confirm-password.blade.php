@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Konfirmasi Password')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Confirm Password -->
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
            <h4 class="mb-1">Konfirmasi Password</h4>
            <p class="mb-6">Ini adalah area aman. Mohon konfirmasi password Anda sebelum melanjutkan.</p>
          </div>

          <form id="formAuthentication" class="mb-4" action="{{ route('password.confirm') }}" method="POST">
            @csrf

            <!-- Password -->
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autocomplete="current-password" autofocus />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
            </div>

            <button class="btn btn-primary d-grid w-100" type="submit">Konfirmasi</button>
          </form>
        </div>
      </div>
      <!-- /Confirm Password -->
    </div>
  </div>
</div>
@endsection
