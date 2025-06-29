<?php

// File: app/Http/Controllers/SampelMaterialController.php (Versi Baru)

namespace App\Http\Controllers;

use App\Models\SampelMaterial;
use App\Models\Test; // Import model Test
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SampelMaterialController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan halaman dashboard untuk monitoring semua sampel.
     */
    public function index(): View
    {
        // View ini sekarang adalah dashboard monitoring, bukan CRUD biasa.
        return view('sample_material.dashboard');
    }

    /**
     * Menyediakan data untuk DataTables di halaman dashboard.
     */
    public function getData(Request $request)
    {
        // Mengambil data sampel beserta relasi ke order (test) dan pemilik order (mitra)
        $query = SampelMaterial::with(['test', 'test.mitra']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('test_id', function ($row) {
                // Menampilkan ID order terkait
                return $row->getFormattedTestId();
            })
            ->addColumn('mitra_name', function ($row) {
                // Menampilkan nama mitra pemilik order
                return $row->getFormattedMitraName();
            })
            ->editColumn('status', function ($row) {
                // Memberi warna pada status sampel
                $badges = [
                    'menunggu_kedatangan' => 'bg-label-secondary',
                    'diterima_di_lab' => 'bg-label-info',
                    'sedang_diuji' => 'bg-label-primary',
                    'pengujian_selesai' => 'bg-label-warning',
                    'selesai' => 'bg-label-success',
                ];
                $badgeClass = $badges[$row->status] ?? 'bg-label-dark';
                $statusText = ucwords(str_replace('_', ' ', $row->status));
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->editColumn('tanggal_penerimaan', function ($row) {
                return $row->tanggal_penerimaan ? $row->tanggal_penerimaan->format('d M Y') : '-';
            })
            ->addColumn('aksi', function ($row) {
                // Tombol aksi hanya untuk admin
                if (Auth::user()->role === 'admin') {
                    $editUrl = route('sample_material.edit', $row->id);
                    $buttons = '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete-sample"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                data-id="' . $row->id . '"
                                data-sample-name="' . htmlspecialchars($row->nama_sampel_material) . '" title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    ';
                    return $buttons;
                }
                return '-';
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    /**
     * [BARU] Menampilkan form untuk membuat Sampel Material baru.
     */
    public function create(): View
    {
<<<<<<< HEAD
=======
        $this->authorize('admin'); // Asumsi hanya admin yang bisa menambah manual
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
        return view('sample_material.create');
    }

    /**
     * [BARU] Menyimpan Sampel Material yang baru dibuat.
     */
    public function store(Request $request)
    {
<<<<<<< HEAD
=======
        $this->authorize('admin');

>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
        $request->validate([
            'nama_sampel_material' => 'required|string|max:255',
            'jumlah_sampel' => 'required|integer|min:1',
            'status' => 'required|in:menunggu_kedatangan,diterima_di_lab,sedang_diuji,pengujian_selesai,selesai',
            'tanggal_penerimaan' => 'nullable|date',
        ]);
<<<<<<< HEAD
        SampelMaterial::create($request->all());
        return redirect()->route('sample_material.dashboard')->with('success', 'Data sampel & material berhasil ditambahkan!');
=======

        // Membuat record baru. `test_id` akan otomatis NULL karena tidak ada di form.
        SampelMaterial::create($request->all());

        return redirect()->route('sample_material.dashboard')->with('success', 'Data sampel manual berhasil ditambahkan!');
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
    }
    
    /**
     * Menampilkan form untuk mengedit Sampel Material tertentu.
     */
    public function edit($id): View
    {
        $this->authorize('admin');
        $sampelMaterial = SampelMaterial::findOrFail($id);
        return view('sample_material.edit', compact('sampelMaterial'));
    }

    /**
     * Menyimpan perubahan (update) pada Sampel Material.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin');

        $sampelMaterial = SampelMaterial::findOrFail($id);

        $request->validate([
            'nama_sampel_material' => 'required|string|max:255',
            'jumlah_sampel' => 'required|integer|min:1',
            'status' => 'required|in:menunggu_kedatangan,diterima_di_lab,sedang_diuji,pengujian_selesai,selesai',
            'tanggal_penerimaan' => 'nullable|date',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_penerimaan',
        ]);

        $sampelMaterial->update($request->all());

        return redirect()->route('sample_material.dashboard')->with('success', 'Data sampel berhasil diperbarui!');
    }

    /**
     * Menghapus data Sampel Material.
     */
    public function destroy($id)
    {
        $this->authorize('admin');
        
        $sampelMaterial = SampelMaterial::findOrFail($id);
        
        $sampelMaterial->delete();
        return response()->json(['success' => true, 'message' => 'Data sampel berhasil dihapus.']);
    }
}