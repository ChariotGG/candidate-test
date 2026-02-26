<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\ImportExportService;
use App\Http\Requests\ImportSupplierRequest;
use Illuminate\Http\Request;

class SupplierImportExportController extends Controller
{
    protected $importExportService;

    public function __construct(ImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    public function export(Supplier $supplier)
    {
        $data = $this->importExportService->exportSupplier($supplier->id);
        
        $fileName = 'supplier_' . $supplier->id . '_export.json';

        return response()->json($data)
                         ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function import(ImportSupplierRequest $request, Supplier $supplier)
    {
        $file = $request->file('import_file');
        
        $data = json_decode(file_get_contents($file->getRealPath()), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Struktur file JSON tidak valid.');
        }

        $result = $this->importExportService->importSupplier($supplier->id, $data);

        if ($result['status'] === 'success') {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']); 
        }
    }
}