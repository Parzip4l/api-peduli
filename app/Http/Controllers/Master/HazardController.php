<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Model
use App\Models\Master\Hazard;

class HazardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $hazard = Hazard::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('deskripsi', 'like', '%' . $search . '%');
        })->paginate(20);

        if ($request->ajax()) {
            return view('master.hazard.index', compact('hazard'))->render();
        }

        return view('master.hazard.index', compact('hazard'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'deskripsi' => 'required|string|max:100',
            ]);

            Hazard::create([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
                'klasifikasi_point' => $request->klasifikasi_point,
            ]);

            return redirect()->back()->with('success', 'Data Potensi cedera berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data potensi cedera: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'deskripsi' => 'required|string|max:100',
            ]);

            $hazard = Hazard::findOrFail($id);
            $hazard->update([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
                'klasifikasi_point' => $request->klasifikasi_point,
            ]);

            return redirect()->back()->with('success', 'Data potensi cedera berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data potensi cedera: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
         try {
            $hazard = Hazard::findOrFail($id);
            $hazard->delete();

            return response()->json([
                'success' => true,
                'message' => 'data potensi cedera berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data potensi cedera: ' . $e->getMessage()
            ]);
        }
    }
}
