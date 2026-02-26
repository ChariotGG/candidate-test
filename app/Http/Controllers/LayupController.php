<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use Illuminate\Http\Request;

class LayupController extends Controller
{
    public function show(CltLayup $layup)
    {
        $layup->load(['supplier', 'layers' => function ($query) {
            $query->orderBy('layer_order', 'asc');
        }]);

        $totalThickness = $layup->layers->sum('thickness');

        return view('layups.show', compact('layup', 'totalThickness'));
    }

    public function create(Supplier $supplier)
    {
        return view('layups.create', compact('supplier'));
    }

    public function store(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama layup wajib diisi.',
        ]);

        // Cek duplikasi nama dalam supplier yang sama
        $exists = $supplier->layups()->where('name', $validated['name'])->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'Nama layup sudah ada untuk supplier ini.'])->withInput();
        }

        $layup = $supplier->layups()->create($validated);

        return redirect()->route('layups.show', $layup)
            ->with('success', "Layup '{$layup->name}' berhasil ditambahkan.");
    }

    public function edit(CltLayup $layup)
    {
        return view('layups.edit', compact('layup'));
    }

    public function update(Request $request, CltLayup $layup)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Cek duplikasi nama dalam supplier yang sama (kecuali diri sendiri)
        $exists = $layup->supplier->layups()
            ->where('name', $validated['name'])
            ->where('id', '!=', $layup->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Nama layup sudah ada untuk supplier ini.'])->withInput();
        }

        $layup->update($validated);

        return redirect()->route('layups.show', $layup)
            ->with('success', "Layup berhasil diperbarui.");
    }

    public function destroy(CltLayup $layup)
    {
        $name     = $layup->name;
        $supplier = $layup->supplier;
        $layup->delete();

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', "Layup '{$name}' berhasil dihapus.");
    }
}