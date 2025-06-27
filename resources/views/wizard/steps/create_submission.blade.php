@extends('layouts/layoutMaster')

@section('title', 'Ajukan Pengujian Baru - ' . $module->name)

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Modul {{ $module->name }} /</span> Pengajuan Baru
</h4>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-1">Formulir Pengajuan Pengujian {{ $module->name }}</h5>
        <p class="mb-0 text-muted">Silakan pilih paket layanan yang Anda butuhkan dan berikan catatan jika perlu.</p>
      </div>
      <div class="card-body">

        {{-- Form akan dikirim ke route 'wizard.submission.store' --}}
        <form action="{{ route('wizard.submission.store') }}" method="POST">
          @csrf
          {{-- Kita juga perlu mengirim module_id untuk controller --}}
          <input type="hidden" name="module_id" value="{{ $module->id }}">

          {{-- Menampilkan deskripsi modul --}}
          <div class="mb-4">
              <p><strong>Deskripsi Modul:</strong> {{ $module->description ?? 'Tidak ada deskripsi.' }}</p>
          </div>

          {{-- Bagian untuk memilih paket pengujian --}}
          <div class="mb-4">
            <label class="form-label fw-semibold">Pilih Paket Layanan:</label>
            
            {{-- Jika tidak ada paket tersedia --}}
            @if($testPackages->isEmpty())
              <div class="alert alert-warning" role="alert">
                Saat ini tidak ada paket layanan yang tersedia untuk modul ini.
              </div>
            @else
              {{-- Loop melalui setiap paket yang tersedia --}}
              @foreach($testPackages as $package)
              <div class="form-check mb-2">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="test_package_id" 
                  id="package-{{ $package->id }}" 
                  value="{{ $package->id }}"
                  {{ $loop->first ? 'checked' : '' }} {{-- Pilih yang pertama secara default --}}
                >
                <label class="form-check-label" for="package-{{ $package->id }}">
                  <strong>{{ $package->name }}</strong><br>
                  <small class="text-muted">Rp{{ number_format($package->price, 0, ',', '.') }},- / sampel</small>
                </label>
              </div>
              @endforeach
            @endif

            @error('test_package_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          {{-- Bagian untuk catatan tambahan --}}
          <div class="mb-4">
            <label for="note" class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
            <textarea class="form-control" id="note" name="note" rows="3" placeholder="Contoh: Mohon analisis pada bagian permukaan sampel.">{{ old('note') }}</textarea>
            @error('note')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          {{-- Tombol Aksi --}}
          <div>
            <a href="{{ route('module.show', $module->code) }}" class="btn btn-label-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" {{ $testPackages->isEmpty() ? 'disabled' : '' }}>Ajukan Pengujian</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection
