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

    // =========================================================================
    // EXPORT
    // =========================================================================

    public function export(Supplier $supplier)
    {
        $data     = $this->importExportService->exportSupplier($supplier->id);
        $fileName = 'supplier_' . $supplier->id . '_' . now()->format('Ymd') . '_export.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    // =========================================================================
    // IMPORT — Standard
    // =========================================================================

    public function import(ImportSupplierRequest $request, Supplier $supplier)
    {
        $content = file_get_contents($request->file('import_file')->getRealPath());
        $data    = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Struktur file JSON tidak valid: ' . json_last_error_msg());
        }

        $strategy = $request->input('conflict_strategy', 'reject');
        $dryRun   = $request->boolean('dry_run');

        if ($strategy === 'manual') {

            $conflicts = $this->importExportService->getConflictDetails($supplier->id, $data);

            if (empty($conflicts)) {
                $result = $this->importExportService->importSupplier($supplier->id, $data, 'overwrite', false);
                return back()->with($result['status'] === 'success' ? 'success' : 'error', $result['message']);
            }

            session([
                'import_data_' . $supplier->id      => $data,
                'import_conflicts_' . $supplier->id => $conflicts,
            ]);

            return back()->with('show_conflict_modal', true);
        }

        $result = $this->importExportService->importSupplier(
            $supplier->id, $data, $strategy, $dryRun
        );

        if ($result['status'] === 'success') {
            return back()->with('success', $result['message']);
        }

        if ($result['status'] === 'dry_run') {
            $n   = count($result['conflicts'] ?? []);
            $msg = $n > 0
                ? "Dry run: {$n} konflik ditemukan. Tidak ada data yang disimpan."
                : 'Dry run: Tidak ada konflik. Import aman untuk dijalankan.';
            return back()->with('success', $msg);
        }

        return back()->with('error', $result['message']);
    }

    // =========================================================================
    // IMPORT PREVIEW
    // =========================================================================

    public function importPreview(Supplier $supplier)
    {
        $key = 'import_data_' . $supplier->id;

        if (!session()->has($key)) {
            return redirect()->route('suppliers.show', $supplier)
                ->with('error', 'Tidak ada data import yang menunggu review. Silakan upload file kembali.');
        }

        $importData = session($key);
        $fileName   = session('import_filename_' . $supplier->id, 'import.json');
        $conflicts  = $this->importExportService->getConflictDetails($supplier->id, $importData);
        $summary    = $this->importExportService->getImportSummary($supplier->id, $importData);

        return view('suppliers.import-preview', compact(
            'supplier', 'importData', 'fileName', 'conflicts', 'summary'
        ));
    }

    // =========================================================================
    // IMPORT RESOLVE
    // =========================================================================

    public function importResolve(Request $request, Supplier $supplier)
    {
        $key = 'import_data_' . $supplier->id;

        if (!session()->has($key)) {
            return redirect()->route('suppliers.show', $supplier)
                ->with('error', 'Session import expired. Silakan upload file kembali.');
        }

        $importData = session($key);
        
        $decisions  = $request->input('decisions', []);

        $result = $this->importExportService->importWithDecisions(
            $supplier->id, $importData, $decisions
        );

        session()->forget([$key, 'import_filename_' . $supplier->id]);

        return redirect()->route('suppliers.show', $supplier)
            ->with($result['status'] === 'success' ? 'success' : 'error', $result['message']);
    }
}