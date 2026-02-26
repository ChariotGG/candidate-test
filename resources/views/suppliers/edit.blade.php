<x-app-layout>
    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Suppliers</a>
                <span class="mx-2">/</span>
                <a href="{{ route('suppliers.show', $supplier) }}" class="hover:text-gray-900 dark:hover:text-gray-100">{{ $supplier->name }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Edit</span>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Edit Supplier</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        ID: SUP-{{ $supplier->created_at->format('Y') }}-{{ str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Supplier Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $supplier->name) }}"
                               class="block w-full px-3 py-2 border rounded-md text-sm shadow-sm
                                      bg-white dark:bg-gray-700 dark:text-gray-200
                                      placeholder-gray-400 dark:placeholder-gray-500
                                      focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F]
                                      {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300 dark:border-gray-600' }}">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                        {{-- Delete dari halaman edit juga --}}
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                              onsubmit="return confirm('Hapus supplier {{ addslashes($supplier->name) }}? Semua layup dan layer terkait akan ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 border border-red-200 dark:border-red-800 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Supplier
                            </button>
                        </form>

                        <div class="flex gap-3">
                            <a href="{{ route('suppliers.show', $supplier) }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>