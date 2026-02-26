<x-app-layout>
    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumb & Actions --}}
            <div class="flex justify-between items-end mb-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Home</a> 
                    <span class="mx-2">/</span> 
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Suppliers</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('suppliers.show', $layup->supplier->id ?? 1) }}" class="hover:text-gray-900 dark:hover:text-gray-100">{{ $layup->supplier->name ?? 'Supplier' }}</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $layup->name }}</span>
                </div>
                <div class="flex gap-3">
                    <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Duplicate
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md text-sm font-medium transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Save Changes
                    </button>
                </div>
            </div>

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
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Created By</div>
                        <div class="text-sm text-gray-800 dark:text-gray-200 font-medium">System Admin</div>
                    </div>
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
                        <button class="text-[#2D6A4F] hover:text-[#1B4332] text-sm font-bold flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Add Layer
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Order</th>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Thickness</th>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Width</th>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Angle</th>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                
                                @forelse ($layup->layers as $layer)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="h-4 w-4 text-gray-300 cursor-move" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                                            <span class="text-sm font-medium text-gray-500">{{ $layer->layer_order }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-900">{{ floatval($layer->thickness) }}mm</td>
                                    <td class="px-4 py-3 text-gray-500">{{ floatval($layer->width) }}mm</td>
                                    <td class="px-4 py-3">
                                        @if($layer->angle == 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded">
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
                                    <td class="px-4 py-3 text-gray-400 hover:text-gray-900 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-4 py-6 text-gray-500">No layers found for this layup.</td></tr>
                                @endforelse

                            </tbody>
                        </table>
                        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex justify-between items-center text-sm">
                            <span class="text-gray-500">Showing {{ $layup->layers->count() }} layers</span>
                            <span class="font-medium text-gray-700">Calculated Sum: <span class="font-bold">{{ $totalThickness }} mm</span></span>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 flex gap-3 shadow-sm">
                        <svg class="h-5 w-5 text-orange-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#E6D5B8] inline-block"></span> Longitudinal (0°)</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-[#C8A070] inline-block"></span> Transverse (90°)</span>
                        </div>
                    </div>

                    <div class="bg-gray-100 border border-gray-200 rounded-lg p-8 relative flex flex-col items-center justify-center min-h-[400px] overflow-hidden">
                        
                        <div class="absolute left-4 top-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right z-20">Top<br>(Outside)</div>
                        <div class="absolute left-4 bottom-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right z-20">Bottom<br>(Inside)</div>

                        <div class="bg-white p-6 shadow-xl rounded-xl w-64 flex flex-col gap-1 relative z-10">
                            
                            @forelse ($layup->layers as $layer)
                                @if($layer->angle == 0)
                                    <div class="bg-[#E6D5B8] rounded border border-[#D5C1A0] flex justify-between items-center px-4 shadow-sm"
                                         style="height: {{ max(24, min(64, $layer->thickness * 1.5)) }}px;">
                                        <span class="text-sm font-bold text-[#6D5438]">L{{ $layer->layer_order }} ({{ floatval($layer->thickness) }}mm)</span>
                                        <svg class="h-4 w-4 text-[#8C7456]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                                    </div>
                                @else
                                    <div class="bg-[#C8A070] rounded border border-[#B58B5B] flex justify-between items-center px-4 shadow-sm"
                                         style="height: {{ max(24, min(64, $layer->thickness * 1.5)) }}px;">
                                        <span class="text-sm font-bold text-[#5B4226]">L{{ $layer->layer_order }} ({{ floatval($layer->thickness) }}mm)</span>
                                        <svg class="h-4 w-4 text-[#7A6145]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-4 text-gray-400 text-sm">No data to visualize</div>
                            @endforelse

                        </div>

                        <div class="mt-8 text-center">
                            <p class="text-sm font-bold text-gray-500 mb-1">Cross-Laminated Structural Assembly</p>
                            <p class="text-[10px] text-gray-400 italic">Note: 3D orientation is for schematic purposes. All layers bonded with industrial-grade adhesives.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>