@php
    use Illuminate\Support\Facades\Route;
@endphp
@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/pages-auth.js'
])
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
                        <span class="app-brand-logo demo" style="overflow: visible; max-height: none;">@include('_partials.macros',['height'=>50, 'withbg' => "fill: #fff;"])</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                     <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}!</h4>
                     <p class="mb-6">Please sign-in to your account</p>
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form id="formAuthentication" class="mb-4" method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="form-label" for="email" :value="__('Email')">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus>
                        </div>

                        <!-- Password -->
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>

                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="my-8" >
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0 ms-2">
                                    <input id="remember_me" class="form-check-input" type="checkbox" name="remember">
                                    <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Log in') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
