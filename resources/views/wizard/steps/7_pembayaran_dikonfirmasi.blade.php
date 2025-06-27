{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step Unduh Hasil & Penyelesaian</h6>
    <p>Pembayaran Anda telah dikonfirmasi. Terima kasih telah menggunakan layanan kami.</p>
  </div>

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH ADMIN / TEKNISI (STATUS INFO) --}}
  {{-- ======================================================= --}}
  @cannot('completeOrder', $test)
    <div class="alert alert-success" role="alert">
        <h6 class="alert-heading fw-bold mb-1">Pembayaran Dikonfirmasi</h6>
        <p class="mb-0">
            Menunggu Mitra ({{ $test->mitra->name }}) untuk mengunduh hasil dan menyelesaikan proses.
        </p>
    </div>
  @endcannot

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH MITRA (TOMBOL AKSI) --}}
  {{-- ======================================================= --}}
  @can('completeOrder', $test)
    <div class="card text-center">
        <div class="card-body">
            <i class="ti ti-circle-check ti-lg text-success mb-3"></i>
            <h5 class="card-title">Pembayaran Berhasil & Hasil Siap Diunduh!</h5>
            <p class="card-text">Silakan unduh file hasil pengujian Anda melalui tombol di bawah ini. Setelah memastikan semuanya sesuai, mohon selesaikan proses ini.</p>
            
            {{-- Tombol untuk Download File --}}
            <a href="{{ asset('storage/' . $test->result_file_path) }}" target="_blank" class="btn btn-primary mb-3">
                <i class="ti ti-download me-1"></i> Unduh File Hasil Uji
            </a>
            
            <hr>

            <p class="mt-3">Jika Anda sudah mengunduh dan memeriksa hasilnya, silakan klik tombol di bawah untuk menyelesaikan order ini.</p>
            
            {{-- Form untuk menyelesaikan order --}}
            <form action="{{ route('wizard.payment.complete', $test) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="ti ti-check me-1"></i> Selesaikan Pengujian
                </button>
            </form>
        </div>
    </div>
  @endcan
</div>

@endsection
