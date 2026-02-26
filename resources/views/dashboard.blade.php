<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Suppliers Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1" style="font-family: ui-serif, Georgia, serif;">Suppliers</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Manage timber suppliers and material sourcing.</p>
                </div>

                {{-- FIX 1: Add Supplier pakai <a> ke route suppliers.create --}}
                <a href="{{ route('suppliers.create') }}" 
                   class="bg-[#2D6A4F] hover:bg-[#1B4332] text-white px-4 py-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Supplier
                </a>
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

            <div class="flex justify-between items-center mb-6">
                <div class="relative w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] sm:text-sm transition duration-150 ease-in-out" placeholder="Search suppliers by name...">
                </div>

                <div class="flex gap-3">
                    <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Layups</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created At</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-sm uppercase">
                                            {{ substr($supplier->name, 0, 2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">
                                                <a href="{{ route('suppliers.show', $supplier) }}" class="hover:underline">{{ $supplier->name }}</a>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">ID: SUP-{{ $supplier->created_at->format('Y') }}-{{ str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $supplier->layups_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $supplier->created_at->format('M d, Y') }}
                                </td>

                                {{-- FIX 2: Actions dengan View, Edit, Delete --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-4">
                                        <a href="{{ route('suppliers.show', $supplier) }}" 
                                           class="text-gray-400 hover:text-[#2D6A4F] transition-colors text-xs font-medium">
                                            View
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" 
                                           class="text-gray-400 hover:text-[#2D6A4F] transition-colors text-xs font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                              onsubmit="return confirm('Hapus supplier {{ addslashes($supplier->name) }}? Semua layup dan layer terkait akan ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-gray-400 hover:text-red-500 transition-colors text-xs font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data Supplier. 
                                    <a href="{{ route('suppliers.create') }}" class="text-[#2D6A4F] font-medium hover:underline">Tambahkan supplier pertama</a>.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

                {{-- Pagination Footer --}}
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-medium text-gray-900 dark:text-white">{{ $suppliers->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium text-gray-900 dark:text-white">{{ $suppliers->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium text-gray-900 dark:text-white">{{ $suppliers->total() }}</span> results
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ $suppliers->previousPageUrl() ?? '#' }}" 
                           class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-500 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $suppliers->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                            &lt;
                        </a>
                        <a href="{{ $suppliers->nextPageUrl() ?? '#' }}" 
                           class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-gray-700 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ !$suppliers->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                            &gt;
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>