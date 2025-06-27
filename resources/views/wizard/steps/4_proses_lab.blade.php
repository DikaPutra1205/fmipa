{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step Pengujian Laboratorium</h6>
    <p>Halaman ini menampilkan progres pengujian dan menjadi tempat teknisi untuk mengunggah hasil.</p>
  </div>

  {{-- Menampilkan pesan jika ada revisi dari Tenaga Ahli --}}
  @if($test->status == 'revisi_diperlukan')
    <div class="alert alert-danger" role="alert">
        <h6 class="alert-heading fw-bold mb-1">Perlu Revisi!</h6>
        <p class="mb-1">Hasil uji sebelumnya ditolak oleh Tenaga Ahli. Mohon lakukan pengujian ulang atau unggah file hasil yang benar.</p>
        <hr>
        <p class="mb-0"><strong>Catatan dari Tenaga Ahli:</strong> <em>{{ $test->rejection_notes }}</em></p>
    </div>
  @endif

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH MITRA (TIMELINE/STATUS) --}}
  {{-- ======================================================= --}}
@if (auth()->user()->role === 'mitra')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Status Pengujian Anda</h5>
            <p class="text-muted">Progres akan diperbarui secara otomatis oleh tim laboratorium kami.</p>
            <ul class="timeline timeline-dashed mt-4">
                <li class="timeline-item">
                    <span class="timeline-indicator-advanced timeline-indicator-success">
                        <i class="ti ti-circle-check"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header">
                            <h6 class="mb-0">Pengajuan Disetujui & Sampel Diterima</h6>
                            <small class="text-muted">{{ $test->sample->tanggal_penerimaan ? $test->sample->tanggal_penerimaan->format('d F Y') : '' }}</small>
                        </div>
                        <p class="mb-0">Sampel Anda telah kami terima dan siap untuk proses pengujian.</p>
                    </div>
                </li>
                <li class="timeline-item">
                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                        <i class="ti ti-microscope"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header">
                            <h6 class="mb-0">Pengujian Sedang Berjalan</h6>
                        </div>
                        <p class="mb-0">
                            @if($test->status == 'revisi_diperlukan')
                                Teknisi sedang melakukan revisi berdasarkan masukan dari Tenaga Ahli.
                            @else
                                Teknisi kami sedang melakukan pengujian pada sampel Anda.
                            @endif
                        </p>
                    </div>
                </li>
                <li class="timeline-item">
                    <span class="timeline-indicator-advanced timeline-indicator-secondary">
                        <i class="ti ti-user-check"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header">
                            <h6 class="mb-0">Verifikasi Hasil oleh Tenaga Ahli</h6>
                        </div>
                        <p class="mb-0">Menunggu hasil uji untuk diverifikasi oleh tim ahli kami.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
  @endif

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH TEKNISI / ADMIN (FORM UPLOAD) --}}
  {{-- ======================================================= --}}
  @can('uploadResult', $test)
    <div class="card card-action">
        <div class="card-header">
            <h5 class="card-title">Formulir Unggah Hasil Uji</h5>
            <div class="card-action-element">
                <span class="badge bg-label-primary">Tugas: {{ $test->technician->name ?? 'Belum Ditugaskan' }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($test->status == 'revisi_diperlukan')
                <p>Silakan unggah kembali file hasil uji yang telah direvisi sesuai catatan dari Tenaga Ahli.</p>
            @else
                <p>Silakan unggah file hasil pengujian untuk order <strong>#{{ $test->id }}</strong>. File akan diteruskan ke Tenaga Ahli untuk proses verifikasi.</p>
            @endif

            <form action="{{ route('wizard.tracking.upload', $test) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="result_file" class="form-label">File Hasil Uji (PDF, JPG, PNG, ZIP - Maks 10MB)</label>
                    <input class="form-control @error('result_file') is-invalid @enderror" type="file" id="result_file" name="result_file" required>
                    @error('result_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-upload me-1"></i> Unggah dan Kirim untuk Verifikasi
                </button>
            </form>
        </div>
    </div>
  @endcan
</div>

@endsection
