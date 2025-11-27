<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by salary type
        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
            'salary_type' => 'required|in:per_kg,per_day',
            'base_salary_per_kg' => 'nullable|numeric|min:0',
            'base_salary_per_day' => 'nullable|numeric|min:0',
        ]);

        // Validate salary based on type
        if ($validated['salary_type'] === 'per_kg' && empty($validated['base_salary_per_kg'])) {
            return back()->withErrors(['base_salary_per_kg' => 'Gaji per kg harus diisi untuk tipe gaji per kg.'])->withInput();
        }

        if ($validated['salary_type'] === 'per_day' && empty($validated['base_salary_per_day'])) {
            return back()->withErrors(['base_salary_per_day' => 'Gaji per hari harus diisi untuk tipe gaji per hari.'])->withInput();
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
            'salary_type' => 'required|in:per_kg,per_day',
            'base_salary_per_kg' => 'nullable|numeric|min:0',
            'base_salary_per_day' => 'nullable|numeric|min:0',
        ]);

        // Validate salary based on type
        if ($validated['salary_type'] === 'per_kg' && empty($validated['base_salary_per_kg'])) {
            return back()->withErrors(['base_salary_per_kg' => 'Gaji per kg harus diisi untuk tipe gaji per kg.'])->withInput();
        }

        if ($validated['salary_type'] === 'per_day' && empty($validated['base_salary_per_day'])) {
            return back()->withErrors(['base_salary_per_day' => 'Gaji per hari harus diisi untuk tipe gaji per hari.'])->withInput();
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}