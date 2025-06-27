<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentWizardController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan halaman utama untuk wizard pembayaran & penyelesaian.
     */
    public function show(Test $test): View
    {
        $this->authorize('view', $test);

        // Data untuk ditampilkan di header wizard
        $wizardTitle = "Wizard Pembayaran & Penyelesaian";
        $steps = [
            ['title' => 'Pembayaran', 'subtitle' => 'Konfirmasi Pembayaran', 'icon' => 'ti-cash'],
            ['title' => 'Unduh Hasil', 'subtitle' => 'Ambil Hasil Uji', 'icon' => 'ti-download'],
            ['title' => 'Selesai', 'subtitle' => 'Proses Berakhir', 'icon' => 'ti-circle-check'],
        ];

        // Pemetaan status ke index step (dimulai dari 0)
        $statusMap = [
            'menunggu_pembayaran'     => 0,
            'pembayaran_dikonfirmasi' => 1,
            'selesai'                 => 2,
        ];
        $currentStepIndex = $statusMap[$test->status] ?? 0;

        // Pemetaan status ke nama file view
        $viewMap = [
            'menunggu_pembayaran'     => 'wizard.steps.6_menunggu_pembayaran',
            'pembayaran_dikonfirmasi' => 'wizard.steps.7_pembayaran_dikonfirmasi',
            'selesai'                 => 'wizard.steps.selesai',
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
     * Menangani konfirmasi pembayaran oleh Admin.
     */
    public function confirmPayment(Test $test): RedirectResponse
    {
        $this->authorize('confirmPayment', $test);

        $test->update([
            'status' => 'pembayaran_dikonfirmasi',
        ]);

        // Kirim notifikasi ke Mitra bahwa pembayaran sudah dikonfirmasi

        return redirect()->route('wizard.payment.show', $test)->with('success', 'Pembayaran telah dikonfirmasi. Mitra kini dapat mengunduh hasil.');
    }

    /**
     * Menangani penyelesaian order oleh Mitra.
     */
    public function completeOrder(Test $test): RedirectResponse
    {
        $this->authorize('completeOrder', $test);

        // Update status order
        $test->update(['status' => 'selesai']);

        // Update status sampel
        if ($test->sample) {
            $test->sample->update(['status' => 'selesai']);
        }

        // Redirect ke halaman dashboard utama dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Pengujian untuk order #' . $test->id . ' telah berhasil diselesaikan!');
    }
}
