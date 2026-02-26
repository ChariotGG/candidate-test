<x-app-layout>
    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Suppliers</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Add New Supplier</span>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">Add New Supplier</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Register a new timber supplier to the system.</p>
                </div>

                <form action="{{ route('suppliers.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Supplier Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="e.g. Nordic Structures Inc."
                               class="block w-full px-3 py-2 border rounded-md text-sm shadow-sm
                                      bg-white dark:bg-gray-700 dark:text-gray-200
                                      placeholder-gray-400 dark:placeholder-gray-500
                                      focus:outline-none focus:ring-1 focus:ring-[#2D6A4F] focus:border-[#2D6A4F]
                                      {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300 dark:border-gray-600' }}">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Save Supplier
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>