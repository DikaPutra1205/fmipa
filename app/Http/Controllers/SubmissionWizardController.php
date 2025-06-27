<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Test;
use App\Models\TestPackage;
use App\Models\SampelMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Import trait yang diperlukan

class SubmissionWizardController extends Controller
{
    use AuthorizesRequests; // Gunakan trait di dalam class

    /**
     * Menampilkan halaman utama untuk wizard pengajuan.
     * Ini akan me-render view yang berbeda berdasarkan status order.
     */
    public function show(Test $test): View
    {
        $this->authorize('view', $test);

        // =======================================================
        // 1. DEFINISIKAN DATA UNTUK HEADER WIZARD
        // =======================================================
        $wizardTitle = "Wizard Pengajuan & Pengiriman Sampel";
        $steps = [
            ['title' => 'Persetujuan', 'subtitle' => 'Tinjau Pengajuan', 'icon' => 'ti-file-text'],
            ['title' => 'Detail Sampel', 'subtitle' => 'Isi Detail Sampel', 'icon' => 'ti-flask'],
            ['title' => 'Pengiriman', 'subtitle' => 'Kirim Sampel Fisik', 'icon' => 'ti-truck-delivery'],
        ];

        // =======================================================
        // 2. TENTUKAN STEP AKTIF BERDASARKAN STATUS
        // =======================================================
        $statusMap = [
            'menunggu_persetujuan_awal'  => 0, // Step 1 (index 0)
            'menunggu_detail_sampel'     => 1, // Step 2 (index 1)
            'menunggu_penerimaan_sampel' => 2, // Step 3 (index 2)
            'ditolak'                    => 0, // Kembali ke step 1 jika ditolak
        ];
        $currentStepIndex = $statusMap[$test->status] ?? 0;

        // =======================================================
        // 3. TENTUKAN FILE VIEW MANA YANG AKAN DITAMPILKAN
        // =======================================================
        $viewMap = [
            'menunggu_persetujuan_awal'  => 'wizard.steps.1_menunggu_persetujuan_awal',
            'menunggu_detail_sampel'     => 'wizard.steps.2_menunggu_detail_sampel',
            'menunggu_penerimaan_sampel' => 'wizard.steps.3_menunggu_penerimaan_sampel',
            'ditolak'                    => 'wizard.steps.ditolak', // Kita perlu buat view ini nanti
        ];
        $viewName = $viewMap[$test->status] ?? 'wizard.steps.error';

        // =======================================================
        // 4. KIRIM SEMUA DATA KE VIEW
        // =======================================================
        return view($viewName, [
            'test' => $test,
            'wizardTitle' => $wizardTitle,
            'steps' => $steps,
            'currentStepIndex' => $currentStepIndex,
        ]);
        
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru.
     * Dipicu oleh tombol [+ Ajukan Pengujian Baru]
     */
    public function create(Module $module): View
    {
        // Mengambil semua paket layanan yang terkait dengan modul ini
        $testPackages = TestPackage::where('module_id', $module->id)->get();

        return view('wizard.create_submission', [
            'module' => $module,
            'testPackages' => $testPackages
        ]);
    }

    /**
     * Menyimpan pengajuan awal dari Mitra.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'test_package_id' => 'required|exists:test_packages,id',
            'note' => 'nullable|string|max:1000',
        ]);

        $test = Test::create([
            'user_id' => Auth::id(),
            'test_package_id' => $request->test_package_id,
            'module_id' => TestPackage::find($request->test_package_id)->module_id,
            'note' => $request->note,
            'status' => 'menunggu_persetujuan_awal',
        ]);

        // Kirim notifikasi ke teknisi/admin di sini (opsional)

        return redirect()->route('wizard.submission.show', $test)->with('success', 'Pengajuan berhasil dikirim. Mohon tunggu persetujuan.');
    }

    /**
     * Menangani aksi persetujuan atau penolakan dari Teknisi.
     */
    public function approveOrReject(Request $request, Test $test): RedirectResponse
    {
        $this->authorize('approve', $test);

        $action = $request->input('action');

        if ($action === 'approve') {
            $test->update([
                'status' => 'menunggu_detail_sampel',
                'assigned_teknisi_id' => Auth::id(), // Penugasan Implisit terjadi di sini!
            ]);
            // Kirim notifikasi ke Mitra bahwa pengajuan disetujui
            return redirect()->route('wizard.submission.show', $test)->with('success', 'Pengajuan telah disetujui.');
        }

        if ($action === 'reject') {
            $test->update([
                'status' => 'ditolak',
                'rejection_notes' => 'Pengajuan ditolak oleh teknisi.', // Bisa ditambahkan input alasan
            ]);
            // Kirim notifikasi ke Mitra bahwa pengajuan ditolak
            return redirect()->route('wizard.submission.show', $test)->with('warning', 'Pengajuan telah ditolak.');
        }

        return redirect()->back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Menyimpan detail sampel yang diisi oleh Mitra.
     */
    public function storeSampleDetails(Request $request, Test $test): RedirectResponse
    {
        $this->authorize('update', $test);

        $request->validate([
            'nama_sampel_material' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        // Kalkulasi harga
        $testPackage = TestPackage::find($test->test_package_id);
        $finalPrice = $testPackage->price * $request->quantity;

        // Update order
        $test->update([
            'quantity' => $request->quantity,
            'final_price' => $finalPrice,
            'status' => 'menunggu_penerimaan_sampel',
        ]);

        // Buat record sampel
        SampelMaterial::create([
            'test_id' => $test->id,
            'nama_sampel_material' => $request->nama_sampel_material,
            'jumlah_sampel' => $request->quantity,
            'status' => 'menunggu_kedatangan',
        ]);

        return redirect()->route('wizard.submission.show', $test)->with('success', 'Detail sampel berhasil disimpan. Silakan kirimkan sampel Anda.');
    }

    /**
     * Mengonfirmasi bahwa sampel fisik telah diterima oleh Teknisi.
     */
    public function confirmReceipt(Test $test): RedirectResponse
    {
        $this->authorize('confirmSample', $test);

        // Update status order
        $test->update(['status' => 'pengujian_berjalan']);

        // Update status sampel
        if ($test->sample) {
            $test->sample->update([
                'status' => 'diterima_di_lab',
                'tanggal_penerimaan' => now(),
            ]);
        }

        // Kirim notifikasi ke Mitra

        // HANDOFF KE WIZARD 2
        return redirect()->route('wizard.tracking.show', $test)->with('success', 'Sampel telah diterima. Proses pengujian dimulai.');
    }
}
