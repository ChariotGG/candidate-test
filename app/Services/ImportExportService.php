<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Support\Facades\DB;
use Exception;

class ImportExportService
{
    // =========================================================================
    // EXPORT
    // =========================================================================

    public function exportSupplier(int $supplierId): array
    {
        $supplier = Supplier::with('layups.layers')->findOrFail($supplierId);

        return [
            'supplier' => ['id' => $supplier->id, 'name' => $supplier->name],
            'layups'   => $supplier->layups->map(function ($layup) {
                return [
                    'name'   => $layup->name,
                    'layers' => $layup->layers->sortBy('layer_order')->map(function ($layer) {
                        return [
                            'layer_order' => $layer->layer_order,
                            'thickness'   => (float) $layer->thickness,
                            'width'       => (float) $layer->width,
                            'angle'       => (float) $layer->angle,
                        ];
                    })->values()->toArray(),
                ];
            })->values()->toArray(),
        ];
    }

    // =========================================================================
    // IMPORT — Standard with strategy
    // =========================================================================

    public function importSupplier(int $supplierId, array $data, string $strategy = 'reject', bool $dryRun = false): array
    {
        $supplier    = Supplier::findOrFail($supplierId);
        $layupsData  = $data['layups'] ?? null;

        if (!is_array($layupsData) || empty($layupsData)) {
            return ['status' => 'error', 'message' => "Format JSON tidak valid atau array 'layups' kosong."];
        }

        $validationError = $this->validateStructure($layupsData);
        if ($validationError) {
            return ['status' => 'error', 'message' => $validationError];
        }

        $conflicts = $this->detectConflicts($supplier, $layupsData);

        if (!empty($conflicts) && $strategy === 'reject') {
            $detail = collect($conflicts)->map(fn($c) =>
                "Layup '{$c['layup_name']}' Layer #{$c['layer_order']}: " .
                "thickness {$c['existing']['thickness']}→{$c['incoming']['thickness']}, " .
                "width {$c['existing']['width']}→{$c['incoming']['width']}, " .
                "angle {$c['existing']['angle']}→{$c['incoming']['angle']}"
            )->implode(' | ');

            return [
                'status'    => 'error',
                'message'   => 'Import dibatalkan: ' . count($conflicts) . ' konflik ditemukan. ' . $detail,
                'conflicts' => $conflicts,
            ];
        }

        if ($dryRun) {
            $n = count($conflicts);
            return [
                'status'    => 'dry_run',
                'message'   => $n > 0 ? "{$n} konflik ditemukan." : 'Tidak ada konflik.',
                'conflicts' => $conflicts,
                'summary'   => $this->buildSummary($supplier, $layupsData),
            ];
        }

        return $this->processImport($supplier, $layupsData, $strategy);
    }

    // =========================================================================
    // IMPORT — Manual decisions 
    // =========================================================================


    public function importWithDecisions(int $supplierId, array $data, array $decisions): array
    {
        $supplier   = Supplier::findOrFail($supplierId);
        $layupsData = $data['layups'] ?? [];

        if (empty($layupsData)) {
            return ['status' => 'error', 'message' => "Data import tidak valid."];
        }

        $decisionMap = [];
        foreach ($decisions as $d) {
            $decisionMap[$d['layup_name'] . '|' . $d['layer_order']] = $d['action'] ?? 'keep';
        }

        DB::beginTransaction();
        try {
            $stats = ['created_layups' => 0, 'created_layers' => 0, 'accepted_layers' => 0, 'kept_layers' => 0];

            foreach ($layupsData as $layupData) {
                $layupName      = $layupData['name'];
                $incomingLayers = $layupData['layers'] ?? [];

                $existingLayup = CltLayup::where('supplier_id', $supplier->id)
                    ->where('name', $layupName)->first();

                if (!$existingLayup) {
                    $newLayup = $supplier->layups()->create(['name' => $layupName]);
                    $this->insertLayers($newLayup, $incomingLayers);
                    $stats['created_layups']++;
                    $stats['created_layers'] += count($incomingLayers);
                    continue;
                }

                foreach ($incomingLayers as $layerData) {
                    $existingLayer = CltLayer::where('layup_id', $existingLayup->id)
                        ->where('layer_order', $layerData['layer_order'])->first();

                    if (!$existingLayer) {
                        $existingLayup->layers()->create([
                            'layer_order' => $layerData['layer_order'],
                            'thickness'   => $layerData['thickness'],
                            'width'       => $layerData['width'],
                            'angle'       => $layerData['angle'],
                        ]);
                        $stats['created_layers']++;
                        continue;
                    }

                    if (!$this->isLayerConflict($existingLayer, $layerData)) {
                        continue; 
                    }

                    $decisionKey = $layupName . '|' . $layerData['layer_order'];
                    $action      = $decisionMap[$decisionKey] ?? 'keep';

                    if ($action === 'accept') {
                        $existingLayer->update([
                            'thickness' => $layerData['thickness'],
                            'width'     => $layerData['width'],
                            'angle'     => $layerData['angle'],
                        ]);
                        $stats['accepted_layers']++;
                    } else {
                        $stats['kept_layers']++;
                    }
                }
            }

            DB::commit();

            $parts = array_filter([
                $stats['created_layups']   ? "Layup baru: {$stats['created_layups']}"     : null,
                $stats['created_layers']   ? "Layer baru: {$stats['created_layers']}"     : null,
                $stats['accepted_layers']  ? "Layer diperbarui: {$stats['accepted_layers']}" : null,
                $stats['kept_layers']      ? "Layer dipertahankan: {$stats['kept_layers']}" : null,
            ]);

            return [
                'status'  => 'success',
                'message' => implode(', ', $parts) . '. Import selesai.',
                'stats'   => $stats,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Error saat import: ' . $e->getMessage()];
        }
    }

    // =========================================================================
    // PUBLIC — Untuk dipakai Controller (preview page)
    // =========================================================================


    public function getConflictDetails(int $supplierId, array $data): array
    {
        $supplier   = Supplier::findOrFail($supplierId);
        $layupsData = $data['layups'] ?? [];
        return $this->detectConflictsDetailed($supplier, $layupsData);
    }


    public function getImportSummary(int $supplierId, array $data): array
    {
        $supplier   = Supplier::findOrFail($supplierId);
        $layupsData = $data['layups'] ?? [];
        return $this->buildSummary($supplier, $layupsData);
    }

    // =========================================================================
    // PRIVATE — Core Import
    // =========================================================================

    private function processImport(Supplier $supplier, array $layupsData, string $strategy): array
    {
        DB::beginTransaction();
        try {
            $stats = ['created_layups' => 0, 'created_layers' => 0, 'updated_layers' => 0, 'skipped_layers' => 0, 'duplicate_layups' => 0];

            foreach ($layupsData as $layupData) {
                $layupName      = $layupData['name'];
                $incomingLayers = $layupData['layers'] ?? [];

                $existingLayup = CltLayup::where('supplier_id', $supplier->id)
                    ->where('name', $layupName)->first();

                if (!$existingLayup) {
                    $new = $supplier->layups()->create(['name' => $layupName]);
                    $this->insertLayers($new, $incomingLayers);
                    $stats['created_layups']++;
                    $stats['created_layers'] += count($incomingLayers);
                    continue;
                }

                if ($this->layupHasConflict($existingLayup, $incomingLayers) && $strategy === 'duplicate') {
                    $dupName = $layupName . ' (imported)';
                    $i = 1;
                    while (CltLayup::where('supplier_id', $supplier->id)->where('name', $dupName)->exists()) {
                        $dupName = $layupName . ' (imported ' . $i++ . ')';
                    }
                    $new = $supplier->layups()->create(['name' => $dupName]);
                    $this->insertLayers($new, $incomingLayers);
                    $stats['duplicate_layups']++;
                    $stats['created_layers'] += count($incomingLayers);
                    continue;
                }

                foreach ($incomingLayers as $layerData) {
                    $existingLayer = CltLayer::where('layup_id', $existingLayup->id)
                        ->where('layer_order', $layerData['layer_order'])->first();

                    if (!$existingLayer) {
                        $existingLayup->layers()->create([
                            'layer_order' => $layerData['layer_order'],
                            'thickness'   => $layerData['thickness'],
                            'width'       => $layerData['width'],
                            'angle'       => $layerData['angle'],
                        ]);
                        $stats['created_layers']++;
                        continue;
                    }

                    if (!$this->isLayerConflict($existingLayer, $layerData)) continue;

                    if ($strategy === 'overwrite') {
                        $existingLayer->update(['thickness' => $layerData['thickness'], 'width' => $layerData['width'], 'angle' => $layerData['angle']]);
                        $stats['updated_layers']++;
                    } else {
                        $stats['skipped_layers']++;
                    }
                }
            }

            DB::commit();

            $parts = array_filter([
                $stats['created_layups']   ? "Layup baru: {$stats['created_layups']}"         : null,
                $stats['duplicate_layups'] ? "Duplikat: {$stats['duplicate_layups']}"         : null,
                $stats['created_layers']   ? "Layer baru: {$stats['created_layers']}"         : null,
                $stats['updated_layers']   ? "Layer diperbarui: {$stats['updated_layers']}"   : null,
                $stats['skipped_layers']   ? "Layer dilewati: {$stats['skipped_layers']}"     : null,
            ]);

            return ['status' => 'success', 'message' => (empty($parts) ? 'Tidak ada perubahan.' : implode(', ', $parts) . '.') . ' Import selesai.', 'stats' => $stats];

        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // =========================================================================
    // PRIVATE — Conflict Detection
    // =========================================================================

    private function detectConflicts(Supplier $supplier, array $layupsData): array
    {
        $conflicts = [];
        foreach ($layupsData as $layupData) {
            $existing = CltLayup::where('supplier_id', $supplier->id)->where('name', $layupData['name'])->first();
            if (!$existing) continue;

            foreach ($layupData['layers'] ?? [] as $layerData) {
                $existingLayer = CltLayer::where('layup_id', $existing->id)->where('layer_order', $layerData['layer_order'])->first();
                if ($existingLayer && $this->isLayerConflict($existingLayer, $layerData)) {
                    $conflicts[] = [
                        'layup_name'  => $layupData['name'],
                        'layer_order' => $layerData['layer_order'],
                        'existing'    => ['thickness' => (float)$existingLayer->thickness, 'width' => (float)$existingLayer->width, 'angle' => (float)$existingLayer->angle],
                        'incoming'    => ['thickness' => (float)$layerData['thickness'], 'width' => (float)$layerData['width'], 'angle' => (float)$layerData['angle']],
                    ];
                }
            }
        }
        return $conflicts;
    }


    private function detectConflictsDetailed(Supplier $supplier, array $layupsData): array
    {
        $result = [];

        foreach ($layupsData as $layupData) {
            $existingLayup = CltLayup::where('supplier_id', $supplier->id)
                ->where('name', $layupData['name'])->first();

            if (!$existingLayup) continue; 

            $layupConflicts = [];
            $existingLayers = $existingLayup->layers->keyBy('layer_order');

            foreach ($layupData['layers'] ?? [] as $layerData) {
                $existingLayer = $existingLayers->get($layerData['layer_order']);
                if ($existingLayer && $this->isLayerConflict($existingLayer, $layerData)) {
                    $layupConflicts[] = [
                        'layer_order' => $layerData['layer_order'],
                        'existing'    => ['thickness' => (float)$existingLayer->thickness, 'width' => (float)$existingLayer->width, 'angle' => (float)$existingLayer->angle],
                        'incoming'    => ['thickness' => (float)$layerData['thickness'], 'width' => (float)$layerData['width'], 'angle' => (float)$layerData['angle']],
                    ];
                }
            }

            if (!empty($layupConflicts)) {

                $result[] = [
                    'layup_name'       => $layupData['name'],
                    'layup_id'         => $existingLayup->id,
                    'conflict_count'   => count($layupConflicts),
                    'layer_conflicts'  => $layupConflicts,
                    'existing_layers'  => $existingLayup->layers->sortBy('layer_order')->map(fn($l) => [
                        'layer_order' => $l->layer_order,
                        'thickness'   => (float)$l->thickness,
                        'width'       => (float)$l->width,
                        'angle'       => (float)$l->angle,
                    ])->values()->toArray(),
                    'incoming_layers'  => collect($layupData['layers'] ?? [])->sortBy('layer_order')->map(fn($l) => [
                        'layer_order' => $l['layer_order'],
                        'thickness'   => (float)$l['thickness'],
                        'width'       => (float)$l['width'],
                        'angle'       => (float)$l['angle'],
                    ])->values()->toArray(),
                ];
            }
        }

        return $result;
    }

    private function layupHasConflict(CltLayup $existingLayup, array $incomingLayers): bool
    {
        foreach ($incomingLayers as $layerData) {
            $existing = CltLayer::where('layup_id', $existingLayup->id)->where('layer_order', $layerData['layer_order'])->first();
            if ($existing && $this->isLayerConflict($existing, $layerData)) return true;
        }
        return false;
    }

    private function isLayerConflict(CltLayer $existingLayer, array $incomingLayer): bool
    {
        return (float)$existingLayer->thickness !== (float)$incomingLayer['thickness']
            || (float)$existingLayer->width     !== (float)$incomingLayer['width']
            || (float)$existingLayer->angle     !== (float)$incomingLayer['angle'];
    }

    // =========================================================================
    // PRIVATE — Helpers
    // =========================================================================

    private function validateStructure(array $layupsData): ?string
    {
        foreach ($layupsData as $i => $layup) {
            if (empty($layup['name'])) return "Layup ke-" . ($i+1) . " tidak memiliki 'name'.";
            foreach ($layup['layers'] ?? [] as $j => $layer) {
                foreach (['layer_order', 'thickness', 'width', 'angle'] as $field) {
                    if (!isset($layer[$field])) return "Layup '{$layup['name']}', layer ke-" . ($j+1) . " tidak memiliki '{$field}'.";
                }
            }
        }
        return null;
    }

    private function insertLayers(CltLayup $layup, array $layers): void
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

    private function buildSummary(Supplier $supplier, array $layupsData): array
    {
        return collect($layupsData)->map(function ($layupData) use ($supplier) {
            $existing = CltLayup::where('supplier_id', $supplier->id)->where('name', $layupData['name'])->first();
            $layers   = collect($layupData['layers'] ?? [])->map(function ($layerData) use ($existing) {
                $status = 'new';
                if ($existing) {
                    $existingLayer = CltLayer::where('layup_id', $existing->id)->where('layer_order', $layerData['layer_order'])->first();
                    if ($existingLayer) $status = $this->isLayerConflict($existingLayer, $layerData) ? 'conflict' : 'unchanged';
                }
                return ['layer_order' => $layerData['layer_order'], 'status' => $status];
            })->values()->toArray();

            return ['name' => $layupData['name'], 'status' => $existing ? 'existing' : 'new', 'layers' => $layers];
        })->values()->toArray();
    }
}