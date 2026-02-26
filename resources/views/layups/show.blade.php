<x-app-layout>
    <div x-data="{
            showAddLayer: false,
            editLayerId: null,
            editLayerData: {},
            openEdit(layer) {
                this.editLayerId = layer.id;
                this.editLayerData = { ...layer };
            },
            closeEdit() {
                this.editLayerId = null;
                this.editLayerData = {};
            }
         }" 
         class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb & Actions --}}
            <div class="flex justify-between items-end mb-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Suppliers</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('suppliers.show', $layup->supplier) }}" class="hover:text-gray-900 dark:hover:text-gray-100">{{ $layup->supplier->name }}</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $layup->name }}</span>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('layups.edit', $layup) }}"
                       class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Layup
                    </a>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">
                            Layup Specification: {{ $layup->name }}
                        </h1>
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded border border-green-200">Active</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Panel configuration for structural assembly.</p>
                </div>

                <div class="flex gap-8 border-l border-gray-200 dark:border-gray-700 pl-8">
                    <div class="hidden md:block">
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Last Modified</div>
                        <div class="text-sm text-gray-800 dark:text-gray-200 font-medium">{{ $layup->updated_at->format('M d, Y') }}</div>
                    </div>
                    <div class="border-l border-gray-200 dark:border-gray-700 pl-8">
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Thickness</div>
                        <div class="text-lg text-[#2D6A4F] font-bold">{{ $totalThickness }}mm</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Layers</div>
                        <div class="text-lg text-[#2D6A4F] font-bold">{{ $layup->layers->count() }} Layers</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- Left: Layer Composition Table --}}
                <div class="lg:col-span-7">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Layer Composition</h2>
                        <button @click="showAddLayer = !showAddLayer"
                                class="text-[#2D6A4F] hover:text-[#1B4332] text-sm font-bold flex items-center gap-1 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Layer
                        </button>
                    </div>

                    {{-- Add Layer Form --}}
                    <div x-show="showAddLayer"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         style="display:none;"
                         class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-[#2D6A4F] p-4 mb-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Add New Layer</h3>
                        <form action="{{ route('layers.store', $layup) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-4 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Order <span class="text-red-500">*</span></label>
                                    <input type="number" name="layer_order" min="1"
                                           value="{{ old('layer_order', $layup->layers->count() + 1) }}"
                                           class="block w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] @error('layer_order') border-red-400 @enderror">
                                    @error('layer_order')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Thickness (mm) <span class="text-red-500">*</span></label>
                                    <input type="number" name="thickness" step="0.1" min="0.1"
                                           value="{{ old('thickness') }}"
                                           placeholder="e.g. 40"
                                           class="block w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] @error('thickness') border-red-400 @enderror">
                                    @error('thickness')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Width (mm) <span class="text-red-500">*</span></label>
                                    <input type="number" name="width" step="0.1" min="0.1"
                                           value="{{ old('width') }}"
                                           placeholder="e.g. 150"
                                           class="block w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] @error('width') border-red-400 @enderror">
                                    @error('width')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Angle (°) <span class="text-red-500">*</span></label>
                                    <select name="angle"
                                            class="block w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F]">
                                        <option value="0" {{ old('angle') == '0' ? 'selected' : '' }}>0° (Longitudinal)</option>
                                        <option value="90" {{ old('angle') == '90' ? 'selected' : '' }}>90° (Transverse)</option>
                                    </select>
                                    @error('angle')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showAddLayer = false"
                                        class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-3 py-1.5 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded text-sm font-medium transition-colors flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Layer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Layers Table --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Order</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Thickness</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Width</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Angle</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                                @forelse ($layup->layers as $layer)
                                    {{-- Normal row --}}
                                    <tr x-show="editLayerId !== {{ $layer->id }}"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3">
                                            <span class="text-sm font-medium text-gray-500">{{ $layer->layer_order }}</span>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ floatval($layer->thickness) }}mm</td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ floatval($layer->width) }}mm</td>
                                        <td class="px-4 py-3">
                                            @if($layer->angle == 0)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                                                    {{ floatval($layer->angle) }}°
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-50 text-orange-700 border border-orange-100 text-xs font-bold rounded">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                    {{ floatval($layer->angle) }}°
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-3">
                                                <button @click="openEdit({{ json_encode(['id' => $layer->id, 'layer_order' => $layer->layer_order, 'thickness' => floatval($layer->thickness), 'width' => floatval($layer->width), 'angle' => floatval($layer->angle)]) }})"
                                                        class="text-gray-400 hover:text-[#2D6A4F] transition-colors text-xs font-medium">
                                                    Edit
                                                </button>
                                                <form action="{{ route('layers.destroy', $layer) }}" method="POST"
                                                      onsubmit="return confirm('Hapus Layer #{{ $layer->layer_order }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors text-xs font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Edit row (inline) --}}
                                    <tr x-show="editLayerId === {{ $layer->id }}"
                                        style="display:none;"
                                        class="bg-green-50 dark:bg-green-900/10 border-l-4 border-[#2D6A4F]">
                                        <td colspan="5" class="px-4 py-3">
                                            <form action="{{ route('layers.update', $layer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="grid grid-cols-4 gap-3 mb-3">
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Order</label>
                                                        <input type="number" name="layer_order" min="1"
                                                               x-model="editLayerData.layer_order"
                                                               class="block w-full px-2 py-1.5 border border-[#2D6A4F] rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F]">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Thickness (mm)</label>
                                                        <input type="number" name="thickness" step="0.1" min="0.1"
                                                               x-model="editLayerData.thickness"
                                                               class="block w-full px-2 py-1.5 border border-[#2D6A4F] rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F]">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Width (mm)</label>
                                                        <input type="number" name="width" step="0.1" min="0.1"
                                                               x-model="editLayerData.width"
                                                               class="block w-full px-2 py-1.5 border border-[#2D6A4F] rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F]">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Angle (°)</label>
                                                        <select name="angle"
                                                                x-model="editLayerData.angle"
                                                                class="block w-full px-2 py-1.5 border border-[#2D6A4F] rounded text-sm bg-white dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F]">
                                                            <option value="0">0° (Longitudinal)</option>
                                                            <option value="90">90° (Transverse)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="closeEdit()"
                                                            class="px-3 py-1.5 border border-gray-300 rounded text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded text-sm font-medium transition-colors flex items-center gap-1">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Save
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                            Belum ada layer. Klik <strong>Add Layer</strong> untuk menambahkan.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                        <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center text-sm">
                            <span class="text-gray-500">{{ $layup->layers->count() }} layers</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                Total: <span class="font-bold text-[#2D6A4F]">{{ $totalThickness }} mm</span>
                            </span>
                        </div>
                    </div>

                    {{-- Engineering Note --}}
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 flex gap-3 shadow-sm">
                        <svg class="h-5 w-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold text-orange-800">Engineering Note</h4>
                            <p class="text-xs text-orange-700 mt-1">Ensure bonding pressure is adjusted for varying layer grades. Verify alignment of 90° transverse layers.</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Structure Visualizer --}}
                <div class="lg:col-span-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Structure Visualizer</h2>
                        <div class="flex gap-3 text-xs text-gray-500 font-medium">
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#E6D5B8] inline-block border border-[#D5C1A0]"></span> 0°</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#C8A070] inline-block border border-[#B58B5B]"></span> 90°</span>
                        </div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 relative flex flex-col items-center justify-center min-h-[400px] overflow-hidden">

                        <div class="absolute left-4 top-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right z-20">Top<br>(Outside)</div>
                        <div class="absolute left-4 bottom-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right z-20">Bottom<br>(Inside)</div>

                        <div class="bg-white shadow-xl rounded-xl w-64 flex flex-col gap-1 relative z-10 p-6">
                            @forelse ($layup->layers as $layer)
                                @if($layer->angle == 0)
                                    <div class="bg-[#E6D5B8] rounded border border-[#D5C1A0] flex justify-between items-center px-4 shadow-sm"
                                         style="height: {{ max(24, min(64, $layer->thickness * 1.5)) }}px;">
                                        <span class="text-xs font-bold text-[#6D5438]">L{{ $layer->layer_order }} ({{ floatval($layer->thickness) }}mm)</span>
                                        <svg class="h-3 w-3 text-[#8C7456]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                                    </div>
                                @else
                                    <div class="bg-[#C8A070] rounded border border-[#B58B5B] flex justify-between items-center px-4 shadow-sm"
                                         style="height: {{ max(24, min(64, $layer->thickness * 1.5)) }}px;">
                                        <span class="text-xs font-bold text-[#5B4226]">L{{ $layer->layer_order }} ({{ floatval($layer->thickness) }}mm)</span>
                                        <svg class="h-3 w-3 text-[#7A6145]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-8 text-gray-400 text-sm">No layers to visualize</div>
                            @endforelse
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-sm font-bold text-gray-500 mb-1">Cross-Laminated Structural Assembly</p>
                            <p class="text-[10px] text-gray-400 italic">All layers bonded with industrial-grade adhesives.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>