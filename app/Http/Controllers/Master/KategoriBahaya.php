<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Model
use App\Models\Master\KategoriBahaya as Bahaya;

class KategoriBahaya extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $kategoribahaya = Bahaya::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('deskripsi', 'like', '%' . $search . '%');
        })->paginate(50);

        if ($request->ajax()) {
            return view('master.bahaya.index', compact('kategoribahaya'))->render();
        }

        return view('master.bahaya.index', compact('kategoribahaya'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'deskripsi' => 'required|string|max:100',
            ]);

            Bahaya::create([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Data Potensi bahaya berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data potensi bahaya: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'deskripsi' => 'required|string|max:100',
            ]);

            $hazard = Bahaya::findOrFail($id);
            $hazard->update([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->back()->with('success', 'Data potensi bahaya berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data potensi bahaya: ' . $e->getMessage());
        }
    }
}
