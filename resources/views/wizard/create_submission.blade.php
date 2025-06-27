@extends('layouts/layoutMaster')

@section('title', 'Ajukan Pengujian Baru - ' . $module->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-1">Informasi Layanan & Pengajuan {{ $module->name }}</h5>
    </div>
    <div class="card-body">
        
        {{-- Menampilkan Detail dari Kolom JSON --}}
        @if($module->details)
            <div class="mb-4">
                <p class="mb-1"><strong>📌 Alat:</strong> {{ $module->details['alat'] ?? '-' }}</p>
                <p class="mb-1"><strong>📌 Metode:</strong> {{ $module->details['metode'] ?? '-' }}</p>
                <p class="mb-1"><strong>🔍 Deskripsi:</strong> {{ $module->details['deskripsi_lengkap'] ?? '-' }}</p>
            </div>
            <hr>
        @endif

        <form action="{{ route('wizard.submission.store') }}" method="POST">
            @csrf
            
            {{-- Menampilkan Biaya Pengujian (dari relasi testPackages) --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">💰 Pilih Jenis Pengujian:</label>
                @foreach($testPackages as $package)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="test_package_id" id="package-{{ $package->id }}" value="{{ $package->id }}" {{ $loop->first ? 'checked' : '' }}>
                    <label class="form-check-label" for="package-{{ $package->id }}">
                        <strong>{{ $package->name }}</strong><br>
                        <small class="text-muted">Rp{{ number_format($package->price, 0, ',', '.') }},- / sampel</small>
                    </label>
                </div>
                @endforeach
            </div>
            <hr>

            {{-- Menampilkan Jenis Sampel (dari kolom JSON) --}}
            @if(!empty($module->details['jenis_sampel']))
                <div class="mb-4">
                    <label class="form-label fw-semibold">🧪 Jenis Sampel yang Diterima:</label>
                    <ul class="list-unstyled">
                        @foreach($module->details['jenis_sampel'] as $sampel)
                            <li><strong>{{ $sampel['tipe'] }}</strong>: {{ $sampel['spek'] }}</li>
                        @endforeach
                    </ul>
                </div>
                <hr>
            @endif

            {{-- ... sisa form (catatan tambahan & tombol) ... --}}
            <div class="mb-4">
                <label for="note" class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                <textarea class="form-control" id="note" name="note" rows="3">{{ old('note') }}</textarea>
            </div>

            <div>
                <a href="{{ route('module.show', $module->code) }}" class="btn btn-label-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Ajukan Pengujian</button>
            </div>
        </form>
    </div>
</div>
@endsection