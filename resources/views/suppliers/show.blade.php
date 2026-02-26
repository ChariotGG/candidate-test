<x-app-layout>
    <div x-data="{ isImportModalOpen: false, isConflictModalOpen: false }" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Suppliers</a> 
                <span class="mx-2">/</span> 
                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $supplier->name ?? 'Nordic Structures Inc.' }}</span>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">
                                    {{ $supplier->name ?? 'Nordic Structures Inc.' }}
                                </h1>
                                <span class="bg-[#2D6A4F] text-white text-xs font-bold px-2 py-1 rounded">Active Partner</span>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">ID: SUP-2024-{{ str_pad($supplier->id ?? 1, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Supplier
                        </button>
                    </div>
                </div>

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

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif
            @error('import_file')
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex justify-between items-center mb-4 mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Associated Layups</h2>
                
                <div class="flex gap-3">
                    <button @click="isImportModalOpen = true" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Import
                    </button>
                    
                    <a href="{{ isset($supplier) ? route('suppliers.export', $supplier->id) : '#' }}" class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </a>

                    <button class="flex items-center gap-2 px-4 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Layup
                    </button>
                </div>
            </div>

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
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                L-{{ $supplier->id }}-{{ str_pad($layup->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                <a href="#" class="hover:underline text-[#2D6A4F]">{{ $layup->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $layup->layers_sum_thickness ?? 0 }} mm
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                {{ $layup->layers_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-gray-400 hover:text-[#2D6A4F] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada layup untuk supplier ini. Silakan Add Layup atau gunakan fitur Import.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-medium text-gray-900 dark:text-white">{{ $layups->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium text-gray-900 dark:text-white">{{ $layups->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium text-gray-900 dark:text-white">{{ $layups->total() }}</span> layups
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ $layups->previousPageUrl() ?? '#' }}" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-500 bg-white dark:bg-gray-700 hover:bg-gray-50 {{ $layups->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                            &lt;
                        </a>
                        <a href="{{ $layups->nextPageUrl() ?? '#' }}" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 bg-white dark:bg-gray-700 hover:bg-gray-50 {{ !$layups->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">
                            &gt;
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="isImportModalOpen" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
             
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
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-xl font-bold text-gray-900" style="font-family: ui-serif, Georgia, serif;">Import Layup Data</h3>
                            <button @click="isImportModalOpen = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form action="{{ isset($supplier) ? route('suppliers.import', $supplier->id) : '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                                <input type="file" name="import_file" accept=".json" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Click to upload JSON file">
                                
                                <svg class="mx-auto h-8 w-8 text-[#2D6A4F] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                
                                <p class="text-sm font-medium text-gray-700">
                                    <span class="text-[#2D6A4F] font-bold group-hover:underline">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-400 mt-1">JSON up to 10MB</p>
                            </div>

                            <div class="mt-5">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Conflict Resolution Strategy</label>
                                <select name="conflict_strategy" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#2D6A4F] focus:border-[#2D6A4F] sm:text-sm rounded-md">
                                    <option value="reject">Reject Entire Import (Default)</option>
                                    <option value="skip">Skip conflicts</option>
                                    <option value="overwrite">Overwrite existing</option>
                                    <option value="duplicate">Duplicate Layup</option>
                                    <option value="manual">Manual Review (Conflict Modal)</option>
                                </select>
                            </div>

                            <div class="mt-4 flex items-start border border-gray-200 rounded p-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="dry_run" class="focus:ring-[#2D6A4F] h-4 w-4 text-[#2D6A4F] border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm flex-1">
                                    <label class="font-bold text-gray-700">Run as Dry Run</label>
                                    <p class="text-gray-500 text-xs mt-1">Simulate the import process without saving changes to the database.</p>
                                </div>
                                <div class="ml-2 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="mt-4 bg-red-50 border border-red-200 rounded p-3 flex gap-3">
                                <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-red-800">Note on Conflicts</h4>
                                    <p class="text-xs text-red-600 mt-1">If structural discrepancies are found, the selected strategy will be applied automatically.</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="isImportModalOpen = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>

                                <button type="button" 
                                        @click="isImportModalOpen = false; $nextTick(() => isConflictModalOpen = true)"
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

        <div x-show="isConflictModalOpen" 
             style="display: none;" 
             class="fixed inset-0 z-[60] overflow-y-auto" 
             aria-labelledby="conflict-modal-title" 
             role="dialog" 
             aria-modal="true">
             
            <div x-show="isConflictModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" 
                 @click="isConflictModalOpen = false"></div>

            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div x-show="isConflictModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full max-w-6xl flex flex-col max-h-[90vh]">
                    
                    <div class="bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-xl font-bold text-gray-900" style="font-family: ui-serif, Georgia, serif;" id="conflict-modal-title">Conflict Resolution: Import [2023-10-CLT-Specs.json]</h3>
                                <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded-full border border-orange-200">Needs Review</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Please review discrepancies between incoming data and existing records.</p>
                        </div>
                        <button @click="isConflictModalOpen = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-1 overflow-hidden bg-gray-50">
                        
                        <div class="w-1/3 lg:w-1/4 bg-white border-r border-gray-200 overflow-y-auto flex flex-col">
                            
                            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <div class="flex items-center gap-2 text-red-600 text-sm font-bold">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    Conflicting Layups (3)
                                </div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>

                            <div class="p-4 space-y-3">
                                <div class="border border-[#2D6A4F] bg-green-50 rounded-lg p-3 cursor-pointer relative shadow-sm">
                                    <div class="absolute top-3 right-3 w-2 h-2 rounded-full bg-red-500"></div>
                                    <h4 class="font-bold text-[#1B4332]">CLT-5-150-L</h4>
                                    <p class="text-xs text-[#2D6A4F] mt-1">Conflict in layers 2 & 4</p>
                                </div>

                                <div class="border border-gray-200 bg-white hover:bg-gray-50 rounded-lg p-3 cursor-pointer relative transition-colors">
                                    <div class="absolute top-3 right-3 w-2 h-2 rounded-full bg-red-500"></div>
                                    <h4 class="font-bold text-gray-800">CLT-3-120-S</h4>
                                    <p class="text-xs text-gray-500 mt-1">Thickness mismatch</p>
                                </div>

                                <div class="border border-gray-200 bg-white hover:bg-gray-50 rounded-lg p-3 cursor-pointer relative transition-colors">
                                    <div class="absolute top-3 right-3 w-2 h-2 rounded-full bg-red-500"></div>
                                    <h4 class="font-bold text-gray-800">CLT-7-200-H</h4>
                                    <p class="text-xs text-gray-500 mt-1">Angle deviation</p>
                                </div>
                                
                                <div class="pt-4 pb-2">
                                    <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Resolved</h5>
                                </div>

                                <div class="border border-gray-100 bg-gray-50/50 rounded-lg p-3 flex justify-between items-center opacity-70">
                                    <h4 class="font-bold text-gray-600 line-through">CLT-3-100-std</h4>
                                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 p-6 overflow-y-auto flex flex-col">
                            
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center gap-3">
                                    <h2 class="text-2xl font-bold text-gray-900" style="font-family: ui-serif, Georgia, serif;">CLT-5-150-L Comparison</h2>
                                    <span class="bg-[#E6D5B8] text-[#6D5438] text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">5 Layers</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-red-600 font-bold">
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    Differences highlighted in Red
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 flex-1">
                                
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
                                    <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 font-bold text-gray-800">
                                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                                Existing Version
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Last updated: Oct 12, 2023</p>
                                        </div>
                                        <div class="w-2 h-2 rounded-full bg-gray-300 mt-1"></div>
                                    </div>
                                    
                                    <div class="flex-1 p-0 overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm text-center">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs w-16">Order</th>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs">Thickness<br>(mm)</th>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs">Width<br>(mm)</th>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs">Angle<br>(°)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                <tr><td class="py-3 text-gray-500">1</td><td class="py-3 font-medium">40</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">0</td></tr>
                                                <tr><td class="py-3 text-gray-500">2</td><td class="py-3 font-bold text-red-600 bg-red-50/50">30</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">90</td></tr>
                                                <tr><td class="py-3 text-gray-500">3</td><td class="py-3 font-medium">40</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">0</td></tr>
                                                <tr><td class="py-3 text-gray-500">4</td><td class="py-3 font-bold text-red-600 bg-red-50/50">30</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">90</td></tr>
                                                <tr><td class="py-3 text-gray-500">5</td><td class="py-3 font-medium">40</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">0</td></tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-4 border-t border-gray-100 bg-white">
                                        <button class="w-full py-2.5 border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-green-50 rounded-lg font-bold text-sm transition-colors flex justify-center items-center gap-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Keep Existing
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl border-2 border-[#2D6A4F] shadow-sm flex flex-col overflow-hidden relative">
                                    <div class="p-4 border-b border-green-100 bg-green-50/30 flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 font-bold text-[#1B4332]">
                                                <svg class="h-4 w-4 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                Importing Version
                                            </div>
                                            <p class="text-xs text-[#2D6A4F] mt-1">Source: Line 34 in JSON</p>
                                        </div>
                                        <div class="w-2 h-2 rounded-full bg-[#2D6A4F] mt-1"></div>
                                    </div>
                                    
                                    <div class="flex-1 p-0 overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm text-center">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs w-16">Order</th>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs">Thickness<br>(mm)</th>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs">Width<br>(mm)</th>
                                                    <th class="px-3 py-3 font-bold text-gray-500 uppercase text-xs">Angle<br>(°)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                <tr><td class="py-3 text-gray-500">1</td><td class="py-3 font-medium">40</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">0</td></tr>
                                                <tr class="bg-red-50"><td class="py-3 text-red-400 font-bold">2</td><td class="py-3 font-bold text-red-700">35</td><td class="py-3 text-red-500">150</td><td class="py-3 text-red-500">90</td></tr>
                                                <tr><td class="py-3 text-gray-500">3</td><td class="py-3 font-medium">40</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">0</td></tr>
                                                <tr class="bg-red-50"><td class="py-3 text-red-400 font-bold">4</td><td class="py-3 font-bold text-red-700">35</td><td class="py-3 text-red-500">150</td><td class="py-3 text-red-500">90</td></tr>
                                                <tr><td class="py-3 text-gray-500">5</td><td class="py-3 font-medium">40</td><td class="py-3 text-gray-500">150</td><td class="py-3 text-gray-500">0</td></tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-4 border-t border-gray-100 bg-white">
                                        <button class="w-full py-2.5 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-lg font-bold text-sm transition-colors flex justify-center items-center gap-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            Accept New
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
                        <button @click="isConflictModalOpen = false" class="text-gray-500 hover:text-gray-800 font-bold text-sm px-4 py-2 rounded-md hover:bg-gray-100 transition-colors">
                            Cancel Import
                        </button>
                        
                        <div class="flex items-center gap-6">
                            <button class="text-gray-400 hover:text-gray-600 flex items-center gap-2 text-sm font-bold transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                                Previous Conflict
                            </button>
                            
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                1 of 3 Discrepancies
                            </span>

                            <button class="text-[#2D6A4F] hover:text-[#1B4332] flex items-center gap-2 text-sm font-bold transition-colors">
                                Next Conflict
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>