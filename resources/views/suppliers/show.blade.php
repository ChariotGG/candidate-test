<x-app-layout>
    <div x-data="{ isImportModalOpen: false }" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen relative">
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

                        {{-- FIX 1: Edit Supplier pakai <a> bukan <button> --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('suppliers.edit', $supplier) }}" 
                               class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Supplier
                            </a>

                            {{-- Delete Supplier --}}
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

                {{-- Supplier Meta Info (static placeholder) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-gray-700">
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Primary Contact</div>
                        <div class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            engineering@nordic.ca
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Location</div>
                        <div class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Montreal, QC, Canada
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Material Certifications</div>
                        <div class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            SPF No. 1/2, D. Fir-L
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Last Audit Date</div>
                        <div class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Oct 12, 2023
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @error('import_file')
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    {{ $message }}
                </div>
            @enderror

            {{-- Associated Layups Header --}}
            <div class="flex justify-between items-center mb-4 mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Associated Layups</h2>
                
                <div class="flex gap-3">
                    <button @click="isImportModalOpen = true" 
                            class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Import
                    </button>
                    
                    <a href="{{ route('suppliers.export', $supplier) }}" 
                       class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </a>

                    {{-- FIX 3: Add Layup pakai <a> dengan route yang benar --}}
                    <a href="{{ route('layups.create', $supplier) }}" 
                       class="flex items-center gap-2 px-4 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Layup
                    </a>
                </div>
            </div>

            {{-- Layups Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Layup ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Thickness</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ply Count</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        
                        @forelse ($layups as $layup)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                L-{{ $supplier->id }}-{{ str_pad($layup->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                {{-- FIX 2: Link layup ke layups.show --}}
                                <a href="{{ route('layups.show', $layup) }}" class="hover:underline text-[#2D6A4F]">
                                    {{ $layup->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $layup->layers_sum_thickness ?? 0 }} mm
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                {{ $layup->layers_count }}
                            </td>
                            {{-- FIX 4: Actions dengan Edit dan Delete --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('layups.edit', $layup) }}" 
                                       class="text-gray-400 hover:text-[#2D6A4F] transition-colors text-xs font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('layups.destroy', $layup) }}" method="POST"
                                          onsubmit="return confirm('Hapus layup {{ addslashes($layup->name) }}? Semua layer terkait akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors text-xs font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada layup untuk supplier ini. Silakan 
                                <a href="{{ route('layups.create', $supplier) }}" class="text-[#2D6A4F] font-medium hover:underline">Add Layup</a> 
                                atau gunakan fitur Import.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                
                {{-- Pagination --}}
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-medium text-gray-900 dark:text-white">{{ $layups->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium text-gray-900 dark:text-white">{{ $layups->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium text-gray-900 dark:text-white">{{ $layups->total() }}</span> layups
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ $layups->previousPageUrl() ?? '#' }}" 
                           class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-500 bg-white dark:bg-gray-700 hover:bg-gray-50 {{ $layups->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                            &lt;
                        </a>
                        <a href="{{ $layups->nextPageUrl() ?? '#' }}" 
                           class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 bg-white dark:bg-gray-700 hover:bg-gray-50 {{ !$layups->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">
                            &gt;
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- =====================================================================
             IMPORT MODAL
             ===================================================================== --}}
        <div x-show="isImportModalOpen" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             role="dialog" 
             aria-modal="true">
             
            {{-- Backdrop --}}
            <div x-show="isImportModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" 
                 @click="isImportModalOpen = false"></div>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="isImportModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full relative z-10">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-xl font-bold text-gray-900" style="font-family: ui-serif, Georgia, serif;">Import Layup Data</h3>
                            <button @click="isImportModalOpen = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('suppliers.import', $supplier) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- File Upload --}}
                            <div x-data="{ fileName: '' }" 
                                 class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                                <input type="file" name="import_file" accept=".json" required 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       @change="fileName = $event.target.files[0]?.name ?? ''">
                                
                                <svg class="mx-auto h-8 w-8 text-[#2D6A4F] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                
                                <p class="text-sm font-medium text-gray-700" x-text="fileName || 'Click to upload or drag and drop'"></p>
                                <p class="text-xs text-gray-400 mt-1" x-show="!fileName">JSON up to 10MB</p>
                            </div>

                            {{-- Conflict Strategy --}}
                            <div class="mt-5">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Conflict Resolution Strategy</label>
                                <select name="conflict_strategy" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-[#2D6A4F] focus:border-[#2D6A4F] sm:text-sm rounded-md">
                                    <option value="reject">Reject Entire Import (Default)</option>
                                    <option value="skip">Skip conflicts — keep existing</option>
                                    <option value="overwrite">Overwrite existing with incoming</option>
                                    <option value="duplicate">Duplicate Layup (suffix "imported")</option>
                                </select>
                            </div>

                            {{-- Dry Run --}}
                            <div class="mt-4 flex items-start border border-gray-200 rounded p-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="dry_run" value="1"
                                           class="focus:ring-[#2D6A4F] h-4 w-4 text-[#2D6A4F] border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm flex-1">
                                    <label class="font-bold text-gray-700">Run as Dry Run</label>
                                    <p class="text-gray-500 text-xs mt-1">Simulate the import process without saving any changes to the database.</p>
                                </div>
                            </div>

                            {{-- Warning --}}
                            <div class="mt-4 bg-amber-50 border border-amber-200 rounded p-3 flex gap-3">
                                <svg class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-amber-800">Format JSON yang diharapkan</h4>
                                    <p class="text-xs text-amber-700 mt-1">File harus mengandung key <code class="bg-amber-100 px-1 rounded">layups</code> dengan array layup dan <code class="bg-amber-100 px-1 rounded">layers</code> di dalamnya.</p>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" 
                                        @click="isImportModalOpen = false" 
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>

                                {{-- FIX: type="submit" bukan @click modal --}}
                                <button type="submit" 
                                        class="px-4 py-2 bg-[#2D6A4F] text-white rounded-md text-sm font-medium hover:bg-[#1B4332] transition-colors flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Confirm Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>