<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Support\Facades\DB;
use Exception;

class ImportExportService
{
    /**
     * EXPORT
     */
    public function exportSupplier(int $supplierId)
    {
        $supplier = Supplier::with('layups.layers')->findOrFail($supplierId);
        
        return $supplier->toArray();
    }

    /**
     * IMPORT
     */
    public function importSupplier(int $supplierId, array $data)
    {
        $supplier = Supplier::findOrFail($supplierId);

        DB::beginTransaction();

        try {
            if (!isset($data['layups']) || !is_array($data['layups'])) {
                throw new Exception("Format data import tidak valid. Key 'layups' tidak ditemukan.");
            }

            foreach ($data['layups'] as $layupData) {
                $existingLayup = CltLayup::where('supplier_id', $supplier->id)
                                         ->where('name', $layupData['name'])
                                         ->first();

                if (!$existingLayup) {
                    $newLayup = $supplier->layups()->create([
                        'name' => $layupData['name']
                    ]);
                    

                    $this->insertLayers($newLayup, $layupData['layers'] ?? []);
                    continue; 
                }

                if (isset($layupData['layers']) && is_array($layupData['layers'])) {
                    foreach ($layupData['layers'] as $layerData) {
                        $existingLayer = CltLayer::where('layup_id', $existingLayup->id)
                                                 ->where('layer_order', $layerData['layer_order'])
                                                 ->first();

                        if ($existingLayer) {
                            $isConflict = (
                                $existingLayer->thickness != $layerData['thickness'] ||
                                $existingLayer->width != $layerData['width'] ||
                                $existingLayer->angle != $layerData['angle']
                            );

                            if ($isConflict) {
                                throw new Exception(
                                    "Konflik terdeteksi pada Layup '{$existingLayup->name}', Layer Order '{$layerData['layer_order']}'. Data ketebalan, lebar, atau sudut berbeda dengan database."
                                );
                            }
                        } else {
                            $existingLayup->layers()->create([
                                'layer_order' => $layerData['layer_order'],
                                'thickness'   => $layerData['thickness'],
                                'width'       => $layerData['width'],
                                'angle'       => $layerData['angle'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Import berhasil diselesaikan tanpa konflik.'];

        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Helper function untuk insert array layers
     */
    private function insertLayers(CltLayup $layup, array $layers)
    {
        foreach ($layers as $layer) {
            $layup->layers()->create([
                'layer_order' => $layer['layer_order'],
                'thickness'   => $layer['thickness'],
                'width'       => $layer['width'],
                'angle'       => $layer['angle'],
            ]);
        }
    }
}