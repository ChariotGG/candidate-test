<x-app-layout>
    <div x-data="conflictManager" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Suppliers</a> 
                <span class="mx-2">/</span> 
                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $supplier->name }}</span>
            </div>

            {{-- Supplier Header Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">
                                    {{ $supplier->name }}
                                </h1>
                                <span class="bg-[#2D6A4F] text-white text-xs font-bold px-2 py-1 rounded">Active Partner</span>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                ID: SUP-{{ $supplier->created_at->format('Y') }}-{{ str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('suppliers.edit', $supplier) }}" 
                               class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Supplier
                            </a>

                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                  onsubmit="return confirm('Hapus supplier {{ $supplier->name }}? Semua layup dan layer terkait akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center gap-2 px-4 py-2 border border-red-200 dark:border-red-800 rounded-md text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-gray-700">
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Primary Contact</div>
                        <div class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200 font-medium text-wrap">
                            engineering@nordic.ca
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Location</div>
                        <div class="text-sm text-gray-800 dark:text-gray-200 font-medium">Montreal, QC, Canada</div>
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Material Certs</div>
                        <div class="text-sm text-gray-800 dark:text-gray-200 font-medium">SPF No. 1/2, D. Fir-L</div>
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Last Audit</div>
                        <div class="text-sm text-gray-800 dark:text-gray-200 font-medium">Oct 12, 2023</div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">{{ session('error') }}</div>
            @endif

            {{-- Associated Layups Header --}}
            <div class="flex justify-between items-center mb-4 mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Associated Layups</h2>
                <div class="flex gap-3">
                    <button @click="isImportModalOpen = true" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        Import
                    </button>
                    <a href="{{ route('suppliers.export', $supplier) }}" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Export
                    </a>
                    <a href="{{ route('layups.create', $supplier) }}" class="flex items-center gap-2 px-4 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Layup
                    </a>
                </div>
            </div>

            {{-- Layups Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Layup ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Total Thickness</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($layups as $layup)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">L-{{ $supplier->id }}-{{ str_pad($layup->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                <a href="{{ route('layups.show', $layup) }}" class="hover:underline text-[#2D6A4F]">{{ $layup->name }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $layup->layers_sum_thickness ?? 0 }} mm</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('layups.edit', $layup) }}" class="text-gray-400 hover:text-[#2D6A4F] text-xs">Edit</a>
                                    <form action="{{ route('layups.destroy', $layup) }}" method="POST" onsubmit="return confirm('Hapus layup?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No layups found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $layups->links() }}
                </div>
            </div>
        </div>

        {{-- ===================== MODAL 1: IMPORT ===================== --}}
        <div x-show="isImportModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="isImportModalOpen = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-xl font-bold text-gray-900" style="font-family: ui-serif, Georgia, serif;">Import Layup Data</h3>
                        <button @click="isImportModalOpen = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form action="{{ route('suppliers.import', $supplier) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div x-data="{ fileName: '' }" class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 cursor-pointer">
                            <input type="file" name="import_file" accept=".json" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0]?.name ?? ''">
                            <svg class="mx-auto h-8 w-8 text-[#2D6A4F] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            <p class="text-sm font-medium text-gray-700" x-text="fileName || 'Click to upload or drag and drop'"></p>
                        </div>

                        <div class="mt-5">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Conflict Strategy</label>
                            <select name="conflict_strategy" class="block w-full border-gray-300 rounded-md text-sm">
                                <option value="reject">Reject Entire Import (Default)</option>
                                <option value="skip">Skip conflicts — keep existing</option>
                                <option value="overwrite">Overwrite existing</option>
                                <option value="duplicate">Duplicate Layup</option>
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="isImportModalOpen = false" class="px-4 py-2 border rounded-md text-sm">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-[#2D6A4F] text-white rounded-md text-sm font-medium hover:bg-[#1B4332]">Confirm Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===================== MODAL 2: CONFLICT RESOLUTION ===================== --}}
        <div x-show="isConflictModalOpen" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto">
            <div x-show="isConflictModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="isConflictModalOpen = false"></div>

            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <form action="{{ route('suppliers.import.resolve', $supplier->id) }}" method="POST"
                     x-show="isConflictModalOpen"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full max-w-6xl flex flex-col max-h-[90vh]">
                    @csrf
                    
                    {{-- Hidden Inputs for Decisions --}}
                    <template x-for="(decision, index) in decisions" :key="index">
                        <div>
                            <input type="hidden" :name="`decisions[${index}][layup_name]`" :value="decision.layup_name">
                            <input type="hidden" :name="`decisions[${index}][layer_order]`" :value="decision.layer_order">
                            <input type="hidden" :name="`decisions[${index}][action]`" :value="decision.action">
                        </div>
                    </template>

                    {{-- Header --}}
                    <div class="bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-xl font-bold text-gray-900" style="font-family: ui-serif, Georgia, serif;">Conflict Resolution</h3>
                                <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded-full border border-orange-200">Needs Review</span>
                            </div>
                        </div>
                        <button type="button" @click="isConflictModalOpen = false" class="text-gray-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-1 overflow-hidden bg-gray-50">
                        {{-- Sidebar --}}
                        <div class="w-1/4 bg-white border-r border-gray-200 overflow-y-auto">
                            <div class="p-4 border-b text-red-600 text-sm font-bold">Conflicting Layups (<span x-text="conflicts.length"></span>)</div>
                            <div class="p-4 space-y-3">
                                <template x-for="(layup, index) in conflicts" :key="index">
                                    <div @click="currentIndex = index"
                                         :class="currentIndex === index ? 'border-[#2D6A4F] bg-green-50' : 'border-gray-200 bg-white'"
                                         class="rounded-lg p-3 cursor-pointer border shadow-sm transition-colors">
                                        <h4 class="font-bold text-sm" x-text="layup.layup_name"></h4>
                                        <p class="text-xs text-gray-500" x-text="layup.conflict_count + ' layer conflicts'"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Comparison --}}
                        <div class="flex-1 p-6 overflow-y-auto" x-show="currentLayup">
                            <div class="grid grid-cols-2 gap-6">
                                {{-- Existing --}}
                                <div class="bg-white rounded-xl border-2 overflow-hidden" :class="getLayupAction() === 'keep' ? 'border-[#2D6A4F]' : 'border-gray-200 opacity-60'">
                                    <div class="p-4 bg-gray-50 font-bold text-sm">Existing Version</div>
                                    <table class="w-full text-xs text-center divide-y">
                                        <thead><tr class="bg-gray-50"><th>Ord</th><th>Thick</th><th>Width</th></tr></thead>
                                        <tbody>
                                            <template x-if="currentLayup">
                                                <template x-for="layer in currentLayup.existing_layers">
                                                    <tr>
                                                        <td class="p-2" x-text="layer.layer_order"></td>
                                                        <td class="p-2 font-bold" x-text="layer.thickness"></td>
                                                        <td class="p-2" x-text="layer.width"></td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                    <div class="p-4"><button type="button" @click="setLayupAction('keep')" :class="getLayupAction() === 'keep' ? 'bg-[#2D6A4F] text-white' : 'border-[#2D6A4F] text-[#2D6A4F]'" class="w-full py-2 rounded-lg font-bold border-2">Keep Existing</button></div>
                                </div>
                                {{-- Incoming --}}
                                <div class="bg-white rounded-xl border-2 overflow-hidden" :class="getLayupAction() === 'accept' ? 'border-[#2D6A4F]' : 'border-gray-200 opacity-60'">
                                    <div class="p-4 bg-green-50 font-bold text-sm">Importing Version</div>
                                    <table class="w-full text-xs text-center divide-y">
                                        <thead><tr class="bg-gray-50"><th>Ord</th><th>Thick</th><th>Width</th></tr></thead>
                                        <tbody>
                                            <template x-if="currentLayup">
                                                <template x-for="layer in currentLayup.incoming_layers">
                                                    <tr>
                                                        <td class="p-2" x-text="layer.layer_order"></td>
                                                        <td class="p-2 font-bold" x-text="layer.thickness"></td>
                                                        <td class="p-2" x-text="layer.width"></td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                    <div class="p-4"><button type="button" @click="setLayupAction('accept')" :class="getLayupAction() === 'accept' ? 'bg-[#2D6A4F] text-white' : 'border-[#2D6A4F] text-[#2D6A4F]'" class="w-full py-2 rounded-lg font-bold border-2">Accept New</button></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-white border-t p-4 flex justify-between items-center">
                        <button type="button" @click="isConflictModalOpen = false" class="text-sm font-bold text-gray-500">Cancel</button>
                        <div class="flex items-center gap-4">
                            <button type="button" @click="if(currentIndex > 0) currentIndex--" :disabled="currentIndex === 0" class="text-sm font-bold disabled:opacity-30">&larr; Prev</button>
                            <span class="text-xs font-bold" x-text="`${currentIndex + 1} of ${conflicts.length}`"></span>
                            <button type="button" x-show="currentIndex < conflicts.length - 1" @click="currentIndex++" class="text-[#2D6A4F] font-bold text-sm">Next &rarr;</button>
                            <button type="submit" x-show="currentIndex === conflicts.length - 1" class="bg-[#2D6A4F] text-white px-6 py-2 rounded-lg font-bold text-sm">Finalize Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('conflictManager', () => ({
                isImportModalOpen: false,
                isConflictModalOpen: {{ session('show_conflict_modal') ? 'true' : 'false' }},
                conflicts: @json($conflicts ?? []),
                currentIndex: 0,
                decisions: [],

                init() {
                    this.conflicts.forEach(layup => {
                        layup.layer_conflicts.forEach(conflict => {
                            this.decisions.push({
                                layup_name: layup.layup_name,
                                layer_order: conflict.layer_order,
                                action: 'keep' 
                            });
                        });
                    });
                },

                get currentLayup() {
                    return this.conflicts.length > 0 ? this.conflicts[this.currentIndex] : null;
                },

                setLayupAction(action) {
                    if(!this.currentLayup) return;
                    this.currentLayup.layer_conflicts.forEach(conflict => {
                        let item = this.decisions.find(d => d.layup_name === this.currentLayup.layup_name && d.layer_order === conflict.layer_order);
                        if (item) item.action = action;
                    });
                },

                getLayupAction() {
                    if(!this.currentLayup || this.currentLayup.layer_conflicts.length === 0) return 'keep';
                    let first = this.currentLayup.layer_conflicts[0];
                    let item = this.decisions.find(d => d.layup_name === this.currentLayup.layup_name && d.layer_order === first.layer_order);
                    return item ? item.action : 'keep';
                }
            }));
        });
    </script>
</x-app-layout>