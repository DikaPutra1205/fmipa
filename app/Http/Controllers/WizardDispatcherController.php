<?php

namespace App\Http\Controllers;

use App\Models\Test; // Pastikan model Test di-import
use Illuminate\Http\RedirectResponse;

class WizardDispatcherController extends Controller
{
    /**
     * Mengarahkan pengguna ke wizard yang sesuai berdasarkan status order pengujian.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dispatch(Test $test): RedirectResponse
    {
        // Ambil status dari order yang diakses
        $status = $test->status;

        // Definisikan grup status untuk setiap wizard
        $wizard1_statuses = [
            'menunggu_persetujuan_awal', 
            'menunggu_detail_sampel', 
            'menunggu_penerimaan_sampel',
            'ditolak' // Status ditolak juga ditampilkan di wizard pertama
        ];

        $wizard2_statuses = [
            'pengujian_berjalan', 
            'menunggu_verifikasi_ahli', 
            'revisi_diperlukan'
        ];
        
        $wizard3_statuses = [
            'menunggu_pembayaran', 
            'pembayaran_dikonfirmasi', 
            'selesai'
        ];

        // Lakukan redirect berdasarkan grup status
        if (in_array($status, $wizard1_statuses)) {
            // Jika status cocok, arahkan ke Wizard 1 (Pengajuan)
            return redirect()->route('wizard.submission.show', $test);
        }

        if (in_array($status, $wizard2_statuses)) {
            // Jika status cocok, arahkan ke Wizard 2 (Pelacakan)
            return redirect()->route('wizard.tracking.show', $test);
        }

        if (in_array($status, $wizard3_statuses)) {
            // Jika status cocok, arahkan ke Wizard 3 (Pembayaran)
            return redirect()->route('wizard.payment.show', $test);
        }

        // Jika status tidak dikenali (sebagai pengaman), kembalikan ke dashboard
        return redirect()->route('dashboard')->with('error', 'Status pengujian tidak valid atau tidak ditemukan.');
    }
}
