<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('layups')->latest()->paginate(5);

        return view('dashboard', compact('suppliers'));
    }

    public function show(Supplier $supplier)
    {
        $layups = $supplier->layups()
            ->withCount('layers')
            ->withSum('layers', 'thickness')
            ->latest()
            ->paginate(10);

        $conflicts = session('import_conflicts_' . $supplier->id, []);

        return view('suppliers.show', compact('supplier', 'layups', 'conflicts'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name'],
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'name.unique'   => 'Nama supplier sudah terdaftar.',
        ]);

        Supplier::create($validated);

        return redirect()->route('dashboard')
            ->with('success', "Supplier '{$validated['name']}' berhasil ditambahkan.");
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name,' . $supplier->id],
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'name.unique'   => 'Nama supplier sudah terdaftar.',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', "Supplier berhasil diperbarui.");
    }

    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->delete();

        return redirect()->route('dashboard')
            ->with('success', "Supplier '{$name}' berhasil dihapus.");
    }
}