{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step Verifikasi Hasil oleh Tenaga Ahli</h6>
    <p>Hasil uji dari laboratorium sedang ditinjau oleh tim ahli kami.</p>
  </div>

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH MITRA & TEKNISI (TIMELINE/STATUS) --}}
  {{-- ======================================================= --}}
  @if (auth()->user()->role === 'mitra' || auth()->user()->role === 'teknisi_lab')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Status Pengujian</h5>
            <p class="text-muted">Menunggu verifikasi dari Tenaga Ahli.</p>
            <ul class="timeline timeline-dashed mt-4">
                <li class="timeline-item">
                    <span class="timeline-indicator-advanced timeline-indicator-success">
                        <i class="ti ti-circle-check"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header"><h6 class="mb-0">Sampel Diterima & Diuji</h6></div>
                        <p class="mb-0">Proses pengujian di laboratorium telah selesai.</p>
                    </div>
                </li>
                <li class="timeline-item">
                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                        <i class="ti ti-user-check"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header"><h6 class="mb-0">Verifikasi Hasil oleh Tenaga Ahli</h6></div>
                        <p class="mb-0">Tenaga Ahli kami sedang mereview file hasil uji. Anda akan segera menerima notifikasi.</p>
                    </div>
                </li>
                 <li class="timeline-item">
                    <span class="timeline-indicator-advanced timeline-indicator-secondary">
                        <i class="ti ti-cash"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header"><h6 class="mb-0">Pembayaran</h6></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
  @endif

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH TENAGA AHLI (FORM AKSI) --}}
  {{-- ======================================================= --}}
  @can('verifyResult', $test)
    <div class="card card-action">
        <div class="card-header">
            <h5 class="card-title">Formulir Verifikasi Hasil Uji</h5>
            <div class="card-action-element">
                <span class="badge bg-label-warning">Butuh Tindakan</span>
            </div>
        </div>
        <div class="card-body">
            <p>Silakan tinjau file hasil uji yang diunggah oleh Teknisi (<strong>{{ $test->technician->name ?? 'N/A' }}</strong>) untuk order <strong>#{{ $test->id }}</strong>.</p>
            
            {{-- Tombol untuk melihat file hasil --}}
            <div class="mb-3">
                <a href="{{ asset('storage/' . $test->result_file_path) }}" target="_blank" class="btn btn-label-primary">
                    <i class="ti ti-file-search me-1"></i> Lihat File Hasil Uji
                </a>
            </div>
            
            <hr>

            <p class="mb-2">Berikan persetujuan jika hasil sudah sesuai, atau tolak dan berikan catatan jika diperlukan revisi.</p>

            <form action="{{ route('wizard.tracking.verify', $test) }}" method="POST">
                @csrf
                
                {{-- Area untuk catatan penolakan, hanya muncul jika "Tolak" dipilih (via JS nanti) --}}
                <div class="mb-3" id="rejection-notes-container" style="display: none;">
                    <label for="rejection_notes" class="form-label">Catatan Revisi (Wajib diisi jika menolak)</label>
                    <textarea class="form-control @error('rejection_notes') is-invalid @enderror" id="rejection_notes" name="rejection_notes" rows="3"></textarea>
                    @error('rejection_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <button type="submit" name="action" value="approve" class="btn btn-success me-2">
                    <i class="ti ti-check me-1"></i> Setujui Hasil
                </button>
                <button type="button" id="reject-btn" class="btn btn-danger">
                    <i class="ti ti-x me-1"></i> Tolak / Minta Revisi
                </button>
                
                {{-- Tombol submit tersembunyi untuk aksi tolak --}}
                <button type="submit" name="action" value="reject" id="submit-reject-btn" class="btn btn-danger" style="display: none;">Kirim Catatan Revisi</button>
            </form>
        </div>
    </div>

    {{-- Sedikit JS untuk menampilkan/menyembunyikan field catatan --}}
    <script>
        document.getElementById('reject-btn').addEventListener('click', function() {
            document.getElementById('rejection-notes-container').style.display = 'block';
            this.style.display = 'none'; // Sembunyikan tombol "Tolak" awal
            document.querySelector('button[name="action"][value="approve"]').style.display = 'none'; // Sembunyikan tombol "Setujui"
            document.getElementById('submit-reject-btn').style.display = 'inline-block'; // Tampilkan tombol submit revisi
        });
    </script>
  @endcan
</div>

@endsection
