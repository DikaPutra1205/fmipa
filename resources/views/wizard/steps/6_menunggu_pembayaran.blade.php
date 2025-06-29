{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step Pembayaran</h6>
    <p>Hasil pengujian telah disetujui. Silakan selesaikan proses pembayaran.</p>
  </div>

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH MITRA (DETAIL TAGIHAN) --}}
  {{-- ======================================================= --}}
  @if (auth()->user()->role === 'mitra')
    <div class="card bg-label-success shadow-none">
        <div class="card-body">
            <h5 class="card-title text-success">Detail Tagihan & Instruksi Pembayaran</h5>
            <p>Silakan lakukan pembayaran sejumlah total biaya ke rekening di bawah ini. Proses akan dilanjutkan setelah pembayaran Anda dikonfirmasi oleh admin.</p>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2">Ringkasan Biaya:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Layanan:</strong> {{ $test->testPackage->name ?? 'N/A' }}</li>
                        <li><strong>Harga per Sampel:</strong> Rp{{ number_format($test->testPackage->price ?? 0, 0, ',', '.') }}</li>
                        <li><strong>Jumlah Sampel:</strong> {{ $test->quantity }}</li>
                        <hr class="my-2">
                        <li class="fw-bold"><strong>Total Tagihan:</strong> Rp{{ number_format($test->final_price, 0, ',', '.') }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-2">Tujuan Transfer:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Bank:</strong> Bank Central Asia (BCA)</li>
                        <li><strong>No. Rekening:</strong> 123-456-7890</li>
                        <li><strong>Atas Nama:</strong> Laboratorium FMIPA UI</li>
                    </ul>
                    <p class="mt-3 mb-0">
                        <small class="text-muted">
                            Mohon sertakan kode order <strong>#{{ $test->id }}</strong> pada berita transfer dan kirimkan bukti pembayaran ke admin kami.
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
  @endif
  
  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH ADMIN (TOMBOL KONFIRMASI) --}}
  {{-- ======================================================= --}}
  @can('confirmPayment', $test)
    <div class="card card-action mt-4">
        <div class="card-header">
            <h5 class="card-title">Konfirmasi Pembayaran</h5>
            <div class="card-action-element">
                <span class="badge bg-label-warning">Butuh Tindakan</span>
            </div>
        </div>
        <div class="card-body">
            <p>
                Order ini sedang menunggu konfirmasi pembayaran dari Mitra (<strong>{{ $test->mitra->name }}</strong>). 
                Klik tombol di bawah ini jika Anda sudah menerima dan memverifikasi bukti pembayaran.
            </p>
            <p><strong>Total Tagihan:</strong> <span class="fw-bold">Rp{{ number_format($test->final_price, 0, ',', '.') }}</span></p>
            
            <form action="{{ route('wizard.payment.confirm', $test) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="ti ti-check me-1"></i> Konfirmasi Pembayaran
                </button>
            </form>

            <p class="mt-3 mb-0">
                <small class="text-muted">
                    Setelah dikonfirmasi, Mitra akan dapat mengunduh file hasil pengujian.
                </small>
            </p>
        </div>
    </div>
  @endcan
</div>

@endsection
