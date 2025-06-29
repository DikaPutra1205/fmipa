{{-- Menggunakan layout utama wizard yang sama --}}
@extends('wizard.layouts.main')

@section('wizard-content')

<div id="step-{{ $currentStepIndex }}" class="content active">
  <div class="content-header mb-4">
    <h6 class="mb-1 fw-semibold">Step 2: Lengkapi Detail Sampel</h6>
    <p>Pengajuan Anda telah disetujui! Silakan lengkapi detail sampel di bawah ini.</p>
  </div>

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH TEKNISI / ADMIN (PESAN TUNGGU) --}}
  {{-- ======================================================= --}}
  @cannot('update', $test)
    <div class="alert alert-info" role="alert">
        <h6 class="alert-heading fw-bold mb-1">Menunggu Mitra</h6>
        <p class="mb-0">
            Menunggu Mitra ({{ $test->mitra->name }}) untuk melengkapi detail dan jumlah sampel.
        </p>
    </div>
  @endcannot

  {{-- ======================================================= --}}
  {{-- TAMPILAN YANG DILIHAT OLEH MITRA (FORM AKSI) --}}
  {{-- ======================================================= --}}
  @can('update', $test)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Formulir Detail Sampel</h5>
            <p class="text-muted">Harga akhir akan dihitung berdasarkan jumlah sampel yang Anda masukkan.</p>
            
            <form action="{{ route('wizard.submission.storeSample', $test) }}" method="POST">
                @csrf

                {{-- Field untuk Nama Sampel --}}
                <div class="mb-3">
                    <label for="nama_sampel_material" class="form-label">Nama Sampel</label>
                    <input type="text" class="form-control @error('nama_sampel_material') is-invalid @enderror" id="nama_sampel_material" name="nama_sampel_material" value="{{ old('nama_sampel_material') }}" placeholder="Contoh: Sampel Batuan A" required>
                    @error('nama_sampel_material')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Field untuk Jumlah Sampel (QTY) --}}
                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah Sampel (QTY)</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                    <div id="quantityHelp" class="form-text">Harga per sampel: Rp{{ number_format($test->testPackage->price, 0, ',', '.') }}</div>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan & Lanjutkan ke Tahap Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>
  @endcan
</div>

@endsection
