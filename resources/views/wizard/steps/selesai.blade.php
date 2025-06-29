{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Proses Selesai</h6>
    <p>Seluruh rangkaian proses pengujian untuk order ini telah selesai.</p>
  </div>

  <div class="card text-center">
    <div class="card-body">
        <i class="ti ti-square-check ti-lg text-success mb-3"></i>
        <h5 class="card-title">Pengujian Telah Selesai!</h5>
        <p class="card-text">
            Terima kasih telah menggunakan layanan kami. Order <strong>#{{ $test->id }}</strong> telah berhasil diselesaikan pada tanggal {{ $test->updated_at->format('d F Y, H:i') }}.
        </p>
        <p class="card-text">
            Anda dapat melihat kembali hasil uji kapan saja melalui halaman riwayat pengujian atau di modul hasil uji material.
        </p>
        
        {{-- Tombol untuk download file hasil uji --}}
        <a href="{{ asset('storage/' . $test->result_file_path) }}" target="_blank" class="btn btn-label-secondary mt-3">
            <i class="ti ti-download me-1"></i> Download Ulang Hasil Uji
        </a>

        {{-- Tombol untuk kembali ke dashboard atau halaman modul --}}
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
            <i class="ti ti-home me-1"></i> Kembali ke Dashboard
        </a>
    </div>
  </div>

</div>

@endsection
