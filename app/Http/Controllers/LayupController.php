<?php

namespace App\Http\Controllers;

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
}
