@extends('layouts/layoutMaster')

{{-- Judul halaman akan dinamis berdasarkan wizard saat ini --}}
@section('title', $wizardTitle ?? 'Proses Pengujian')

<!-- Vendor Styles (dari template Anda) -->
@section('vendor-style')
@vite([
'resources/assets/vendor/libs/bs-stepper/bs-stepper.scss',
'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

<!-- Vendor Scripts (dari template Anda) -->
@section('vendor-script')
@vite([
'resources/assets/vendor/libs/bs-stepper/bs-stepper.js',
'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
{{-- JavaScript sekarang hanya bertugas menginisialisasi, tanpa logika 'to()' --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const stepperElement = document.querySelector('.bs-stepper');
  if (stepperElement) {
    // Inisialisasi library bs-stepper. Tidak perlu .to() lagi.
    // Kelas 'active' sudah diatur oleh Blade di sisi server.
    const wizard = new Stepper(stepperElement, {
      linear: false
    });
  }
});
</script>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="bs-stepper wizard-icons wizard-icons-example">

      {{-- ======================================================= --}}
      {{-- BAGIAN HEADER WIZARD (STEP INDICATORS) - DENGAN PERBAIKAN --}}
      {{-- ======================================================= --}}
      <div class="bs-stepper-header">
        @foreach($steps as $index => $step)
          {{-- PERBAIKAN FINAL: Menambahkan class 'active' secara dinamis dari Blade --}}
          <div class="step {{ $index == $currentStepIndex ? 'active' : '' }}" data-target="#step-{{ $index }}">
            <button type="button" class="step-trigger" disabled>
              <span class="bs-stepper-icon">
                <i class="ti {{ $step['icon'] }}"></i>
              </span>
              <span class="bs-stepper-label">
                <span class="bs-stepper-title">{{ $step['title'] }}</span>
                <span class="bs-stepper-subtitle">{{ $step['subtitle'] }}</span>
              </span>
            </button>
          </div>
          @if(!$loop->last)
          <div class="line">
            <i class="ti ti-chevron-right"></i>
          </div>
          @endif
        @endforeach
      </div>

      {{-- ======================================================= --}}
      {{-- BAGIAN KONTEN WIZARD - MENJADI TEMPAT KONTEN --}}
      {{-- ======================================================= --}}
      <div class="bs-stepper-content">
        @yield('wizard-content')
      </div>

    </div>
  </div>
</div>
@endsection
