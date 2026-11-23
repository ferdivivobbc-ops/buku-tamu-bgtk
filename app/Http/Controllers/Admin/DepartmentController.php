<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('guests')->orderBy('name')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:departments,name',
            'code' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:150',
            'room_location' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_available'] = $request->boolean('is_available', true);

        Department::create($validated);

        return back()->with('success', 'Bagian / Tim Kerja baru berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:departments,name,' . $department->id,
            'code' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:150',
            'room_location' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_available'] = $request->boolean('is_available');

        $department->update($validated);

        return back()->with('success', 'Data Bagian / Tim Kerja berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        if ($department->guests()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus Bagian / Tim Kerja yang memiliki data riwayat tamu.');
        }

        $department->delete();
        return back()->with('success', 'Bagian / Tim Kerja berhasil dihapus.');
    }
}
