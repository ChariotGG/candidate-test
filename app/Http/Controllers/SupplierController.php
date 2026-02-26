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

        return view('suppliers.show', compact('supplier', 'layups'));
    }
}