<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TestPackage;
use App\Models\Module; // Import the Module model

class TestPackageController extends Controller
{
    /**
     * Display a listing of the TestPackages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $user = Auth::user();
        // Query the TestPackage model and eager load the 'module' relationship
        $data = TestPackage::with('module')->select(['id', 'module_id', 'name', 'price', 'created_at', 'updated_at']);

        // If the authenticated user is an admin, add action columns for edit and delete
        if ($user && $user->role === 'admin') {
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('price', function ($row) {
                    // Format price as currency (e.g., IDR)
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('module_id', function ($row) {
                    // Display the name of the module
                    return $row->module ? $row->module->name : 'N/A';
                })
                ->addColumn('module_name', function ($row) {
                    // Menampilkan nama modul atau 'N/A' jika tidak ada
                    return $row->module ? $row->module->name : 'N/A';
                })
                ->addColumn('aksi', function ($row) {
                    // Define URLs for edit and delete actions
                    $editUrl = url('/test_package/' . $row->id . '/edit');
                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l3 3l-9 9h-3v-3z" />
                                <path d="M18 3l3 3" />
                            </svg>
                        </a>
                        <button class="btn btn-sm btn-icon btn-danger btn-delete-TestPackage" data-id="' . $row->id . '" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7l0 -3h6l0 3" />
                            </svg>
                        </button>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } else {
            // For non-admin users, just return the data without action columns
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('price', function ($row) {
                    // Format price as currency (e.g., IDR)
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('module_id', function ($row) {
                    // Display the name of the module
                    return $row->module ? $row->module->name : 'N/A';
                })
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new TestPackage.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Pass modules to the view for a dropdown
        $modules = Module::all();
        return view('test_package.create', compact('modules'));
    }

    /**
     * Store a newly created TestPackage in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming data based on the table structure
        $request->validate([
            'module_id' => 'required|integer|exists:modules,id', // Ensure module_id exists in modules table
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Create a new TestPackage record in the database
        TestPackage::create([
            'module_id' => $request->module_id,
            'name' => $request->name,
            'price' => $request->price,
            // created_at and updated_at will be handled automatically by Laravel
        ]);

        // Redirect to the TestPackages dashboard with a success message
        return redirect()->route('test_package.dashboard')->with('success', 'Data Layanan berhasil ditambahkan!');
    }

    /**
     * Remove the specified TestPackage from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Only admin users are authorized to delete TestPackages
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk menghapus layanan.'], 403);
        }

        // Find the TestPackage by ID or throw a 404 exception
        $TestPackage = TestPackage::findOrFail($id);
        $TestPackage->delete(); // Delete the TestPackage record

        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Data layanan berhasil dihapus.']);
    }

    /**
     * Show the form for editing the specified TestPackage.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Only admin users are authorized to edit TestPackages
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk mengedit layanan.'], 403);
        }

        // Find the TestPackage by ID or throw a 404 exception
        $TestPackage = TestPackage::findOrFail($id);
        $modules = Module::all(); // Get all modules for the dropdown
        // Pass the TestPackage data and modules to the 'test_package.edit' view
        return view('test_package.edit', compact('TestPackage', 'modules'));
    }

    /**
     * Update the specified TestPackage in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Only admin users are authorized to update TestPackages
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Anda tidak memiliki akses untuk memperbarui layanan.'], 403);
        }

        // Find the TestPackage to be updated by ID
        $TestPackage = TestPackage::findOrFail($id);

        // Validate incoming data
        $request->validate([
            'module_id' => 'required|integer|exists:modules,id', // Ensure module_id exists in modules table
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Update the TestPackage data
        $TestPackage->module_id = $request->module_id;
        $TestPackage->name = $request->name;
        $TestPackage->price = $request->price;

        $TestPackage->save(); // Save the changes to the database

        // Redirect to the TestPackages dashboard with a success message
        return redirect()->route('test_package.dashboard')->with('success', 'Data layanan berhasil diperbarui!');
    }
}
