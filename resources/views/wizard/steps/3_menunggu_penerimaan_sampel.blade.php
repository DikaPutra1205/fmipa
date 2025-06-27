{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step 3: Pengiriman & Konfirmasi Sampel</h6>
    <p>Tahap terakhir dari proses pengajuan. Mohon kirimkan sampel fisik ke alamat laboratorium.</p>
  </div>

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH MITRA (INSTRUKSI) --}}
  {{-- ======================================================= --}}
  @can('view', $test)
    <div class="card bg-label-primary shadow-none">
        <div class="card-body">
            <h5 class="card-title text-primary">Ringkasan & Instruksi Pengiriman</h5>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li><strong>Layanan:</strong> {{ $test->testPackage->name ?? 'N/A' }}</li>
                        <li><strong>Jumlah Sampel:</strong> {{ $test->quantity }}</li>
                        <li><strong>Total Biaya:</strong> Rp{{ number_format($test->final_price, 0, ',', '.') }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 fw-semibold">Alamat Pengiriman:</p>
                    <p class="mb-0">
                        Laboratorium UPP – Lantai 1<br>
                        FMIPA Universitas Indonesia<br>
                        Depok 16424
                    </p>
                </div>
            </div>
            <p class="mt-3 mb-0">
                <small class="text-muted">
                    Status akan diperbarui secara otomatis setelah teknisi kami mengonfirmasi penerimaan sampel fisik Anda.
                </small>
            </p>
        </div>
    </div>
  @endcan
  
  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH TEKNISI / ADMIN (TOMBOL AKSI) --}}
  {{-- ======================================================= --}}
  @can('confirmSample', $test)
    <div class="card card-action mt-4">
        <div class="card-header">
            <h5 class="card-title">Konfirmasi Penerimaan Sampel</h5>
            <div class="card-action-element">
                <span class="badge bg-label-info">Menunggu Sampel Fisik</span>
            </div>
        </div>
        <div class="card-body">
            <p>
                Halaman ini menunggu konfirmasi penerimaan sampel fisik dari Mitra (<strong>{{ $test->mitra->name }}</strong>). 
                Klik tombol di bawah ini jika sampel sudah Anda terima di laboratorium.
            </p>
            
            <form action="{{ route('wizard.submission.confirmReceipt', $test) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="ti ti-box me-1"></i> Konfirmasi Sampel Telah Diterima
                </button>
            </form>

            <p class="mt-3 mb-0">
                <small class="text-muted">
                    Setelah dikonfirmasi, order ini akan masuk ke tahap pengujian dan akan dilanjutkan di Wizard Pelacakan Pengujian.
                </small>
            </p>
        </div>
    </div>
  @endcan
</div>

@endsection
