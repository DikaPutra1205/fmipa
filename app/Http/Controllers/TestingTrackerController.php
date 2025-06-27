<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TestingTrackerController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan halaman utama untuk wizard pelacakan & verifikasi.
     * Ini akan me-render view yang berbeda berdasarkan status order.
     */
    public function show(Test $test): View
    {
        $this->authorize('view', $test);

        // Data untuk ditampilkan di header wizard
        $wizardTitle = "Wizard Pelacakan & Verifikasi Hasil";
        $steps = [
            ['title' => 'Pengujian', 'subtitle' => 'Proses di Laboratorium', 'icon' => 'ti-microscope'],
            ['title' => 'Verifikasi', 'subtitle' => 'Persetujuan Hasil', 'icon' => 'ti-user-check'],
            ['title' => 'Selesai', 'subtitle' => 'Menuju Pembayaran', 'icon' => 'ti-circle-check'],
        ];

        // Pemetaan status ke index step (dimulai dari 0)
        $statusMap = [
            'pengujian_berjalan'       => 0,
            'revisi_diperlukan'        => 0,
            'menunggu_verifikasi_ahli' => 1,
        ];
        $currentStepIndex = $statusMap[$test->status] ?? 0;

        // Pemetaan status ke nama file view
        // Perhatikan, kita akan membuat satu view '4_proses_lab' yang cerdas
        $viewMap = [
            'pengujian_berjalan'       => 'wizard.steps.4_proses_lab',
            'revisi_diperlukan'        => 'wizard.steps.4_proses_lab',
            'menunggu_verifikasi_ahli' => 'wizard.steps.5_verifikasi_ahli',
        ];
        $viewName = $viewMap[$test->status] ?? 'wizard.steps.error';

        // Kirim semua data yang dibutuhkan ke view
        return view($viewName, [
            'test' => $test,
            'wizardTitle' => $wizardTitle,
            'steps' => $steps,
            'currentStepIndex' => $currentStepIndex,
        ]);
    }

    /**
     * Menangani upload file hasil uji oleh Teknisi Lab.
     */
    public function uploadResult(Request $request, Test $test): RedirectResponse
    {
        $this->authorize('uploadResult', $test);

        $request->validate([
            'result_file' => 'required|file|mimes:pdf,jpg,png,zip|max:10240', // Maks 10MB
        ]);

        // Simpan file ke storage (contoh: di dalam folder 'test_results')
        $filePath = $request->file('result_file')->store('test_results', 'public');

        // Update order
        $test->update([
            'result_file_path' => $filePath,
            'status' => 'menunggu_verifikasi_ahli',
        ]);

        // Update status sampel
        if ($test->sample) {
            $test->sample->update(['status' => 'sedang_diuji']);
        }

        // Kirim notifikasi ke Tenaga Ahli

        return redirect()->route('wizard.tracking.show', $test)->with('success', 'File hasil uji berhasil diunggah. Menunggu verifikasi.');
    }

    /**
     * Menangani verifikasi hasil oleh Tenaga Ahli.
     */
    public function verifyResult(Request $request, Test $test): RedirectResponse
    {
        $this->authorize('verifyResult', $test);

        $action = $request->input('action');

        if ($action === 'approve') {
            // Update order
            $test->update([
                'status' => 'menunggu_pembayaran',
                'verified_by_ahli_id' => Auth::id(),
            ]);

            // Update status sampel
            if ($test->sample) {
                $test->sample->update(['status' => 'pengujian_selesai']);
            }

            // Kirim notifikasi ke Mitra

            // HANDOFF KE WIZARD 3
            return redirect()->route('wizard.payment.show', $test)->with('success', 'Hasil uji telah disetujui. Menunggu pembayaran dari Mitra.');
        }

        if ($action === 'reject') {
            $request->validate(['rejection_notes' => 'required|string|max:1000']);
            
            // Hapus file lama jika ada (opsional, bisa juga diarsipkan)
            // Storage::disk('public')->delete($test->result_file_path);

            $test->update([
                'status' => 'revisi_diperlukan',
                'rejection_notes' => $request->rejection_notes,
                'result_file_path' => null, // Kosongkan path file agar bisa diupload ulang
            ]);

            // Kirim notifikasi ke Teknisi Lab

            return redirect()->route('wizard.tracking.show', $test)->with('warning', 'Hasil uji ditolak. Teknisi perlu melakukan revisi.');
        }

        return redirect()->back()->with('error', 'Aksi tidak valid.');
    }
}
