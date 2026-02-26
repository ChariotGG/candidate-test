<?php

namespace App\Http\Controllers;

use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\Request;

class LayerController extends Controller
{
    public function store(Request $request, CltLayup $layup)
    {
        $validated = $request->validate([
            'layer_order' => ['required', 'integer', 'min:1'],
            'thickness'   => ['required', 'numeric', 'min:0.1'],
            'width'       => ['required', 'numeric', 'min:0.1'],
            'angle'       => ['required', 'numeric', 'in:0,90'],
        ], [
            'layer_order.required' => 'Layer order wajib diisi.',
            'layer_order.unique'   => 'Layer order sudah digunakan dalam layup ini.',
            'thickness.required'   => 'Thickness wajib diisi.',
            'width.required'       => 'Width wajib diisi.',
            'angle.in'             => 'Angle hanya boleh 0° atau 90°.',
        ]);

        $exists = $layup->layers()->where('layer_order', $validated['layer_order'])->exists();
        if ($exists) {
            return back()->withErrors(['layer_order' => 'Layer order sudah digunakan dalam layup ini.'])->withInput();
        }

        $layup->layers()->create($validated);

        return redirect()->route('layups.show', $layup)
            ->with('success', "Layer #{$validated['layer_order']} berhasil ditambahkan.");
    }

    public function update(Request $request, CltLayer $layer)
    {
        $validated = $request->validate([
            'layer_order' => ['required', 'integer', 'min:1'],
            'thickness'   => ['required', 'numeric', 'min:0.1'],
            'width'       => ['required', 'numeric', 'min:0.1'],
            'angle'       => ['required', 'numeric', 'in:0,90'],
        ]);

        // Cek duplikasi layer_order dalam layup yang sama (kecuali diri sendiri)
        $exists = $layer->layup->layers()
            ->where('layer_order', $validated['layer_order'])
            ->where('id', '!=', $layer->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['layer_order' => 'Layer order sudah digunakan dalam layup ini.'])->withInput();
        }

        $layer->update($validated);

        return redirect()->route('layups.show', $layer->layup)
            ->with('success', "Layer berhasil diperbarui.");
    }

    public function destroy(CltLayer $layer)
    {
        $layup = $layer->layup;
        $order = $layer->layer_order;
        $layer->delete();

        return redirect()->route('layups.show', $layup)
            ->with('success', "Layer #{$order} berhasil dihapus.");
    }
}