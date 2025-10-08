<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\Divisions;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->get('search');

        $departments = Department::with('division')->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->paginate(10);

        $divisi = Divisions::all();

        if ($request->ajax()) {
            return view('master.department.index', compact('departments','divisi'))->render();
        }
    
        return view('master.department.index', compact('departments','divisi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $department = Department::create($validated);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Department $department)
    {
        return response()->json($department->load('division'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'division_id' => 'sometimes|exists:divisions,id',
            'name' => 'sometimes|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $department->update($validated);

        return response()->json([
            'message' => 'Department updated successfully',
            'data' => $department
        ]);
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function getByDivision($divisionId)
    {
        $departments = Department::where('division_id', $divisionId)->get();

        return response()->json($departments);
    }
}
