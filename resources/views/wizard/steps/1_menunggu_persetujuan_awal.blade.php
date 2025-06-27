@extends('wizard.layouts.main')

@section('wizard-content')

{{-- PERBAIKAN KUNCI: Tambahkan class 'active' di sini --}}
<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step 1: Persetujuan Pengajuan</h6>
    <p>Pengajuan Anda sedang ditinjau oleh pihak laboratorium.</p>
  </div>

  {{-- Tampilan untuk Mitra --}}
  @can('view', $test)
    <div class="alert alert-info" role="alert">
      <h6 class="alert-heading fw-bold mb-1">Pengajuan Anda Telah Terkirim!</h6>
      <p class="mb-0">
        Pengajuan untuk layanan <strong>{{ $test->testPackage->name ?? 'N/A' }}</strong> sedang menunggu persetujuan dari teknisi kami.
      </p>
    </div>
  @endcan

  {{-- Tampilan untuk Teknisi / Admin --}}
  @can('approve', $test)
    <div class="card card-action">
        <div class="card-header">
            <h5 class="card-title">Tinjau Pengajuan Baru</h5>
        </div>
        <div class="card-body">
            <p>Mitra <strong>{{ $test->mitra->name }}</strong> telah mengajukan pengujian.</p>
            <p><strong>Catatan:</strong><br><em>{{ $test->note ?? 'Tidak ada catatan.' }}</em></p>
            <form action="{{ route('wizard.submission.action', $test) }}" method="POST">
                @csrf
                <button type="submit" name="action" value="approve" class="btn btn-success me-2">Setujui</button>
                <button type="submit" name="action" value="reject" class="btn btn-danger">Tolak</button>
            </form>
        </div>
    </div>
  @endcan
</div>

@endsection
