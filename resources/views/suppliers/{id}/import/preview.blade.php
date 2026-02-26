<x-app-layout>
    <div x-data="{
            currentIndex: 0,
            decisions: {},
            get current() { return conflicts[this.currentIndex] ?? null; },
            get total() { return conflicts.length; },
            isConflictLayer(layerOrder) {
                if (!this.current) return false;
                return this.current.layer_conflicts.some(c => c.layer_order == layerOrder);
            },
            getDecision(layupName, layerOrder) {
                return this.decisions[layupName + '|' + layerOrder] ?? 'keep';
            },
            setDecision(layupName, layerOrder, action) {
                this.decisions[layupName + '|' + layerOrder] = action;
            },
            allResolved() {
                for (let c of conflicts) {
                    for (let lc of c.layer_conflicts) {
                        if (!this.decisions[c.layup_name + '|' + lc.layer_order]) return false;
                    }
                }
                return true;
            },
            acceptAll() {
                if (!this.current) return;
                for (let lc of this.current.layer_conflicts) {
                    this.setDecision(this.current.layup_name, lc.layer_order, 'accept');
                }
            },
            keepAll() {
                if (!this.current) return;
                for (let lc of this.current.layer_conflicts) {
                    this.setDecision(this.current.layup_name, lc.layer_order, 'keep');
                }
            },
            isFullyResolved(conflict) {
                return conflict.layer_conflicts.every(lc => !!this.decisions[conflict.layup_name + '|' + lc.layer_order]);
            }
         }"
         x-init="conflicts = {{ json_encode($conflicts) }}"
         class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">

    {{-- Pass conflicts to Alpine --}}
    <script>var conflicts = @json($conflicts);</script>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Suppliers</a>
            <span class="mx-2">/</span>
            <a href="{{ route('suppliers.show', $supplier) }}" class="hover:text-gray-900">{{ $supplier->name }}</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium">Conflict Resolution</span>
        </div>

        {{-- Page Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;">
                            Conflict Resolution
                        </h1>
                        <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded-full border border-orange-200">
                            {{ count($conflicts) }} Layup Conflicting
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">
                        File: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $fileName }}</span>
                        &nbsp;→&nbsp; Supplier: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $supplier->name }}</span>
                    </p>
                </div>
                <a href="{{ route('suppliers.show', $supplier) }}"
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel Import
                </a>
            </div>
        </div>

        @if(empty($conflicts))
            {{-- No conflicts — show summary and auto-submit --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center mb-6">
                <svg class="h-12 w-12 text-green-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-lg font-bold text-green-800 mb-1">Tidak Ada Konflik</h2>
                <p class="text-sm text-green-700 mb-4">Semua data import aman untuk diproses.</p>
                <form action="{{ route('suppliers.import.resolve', $supplier) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md font-medium text-sm transition-colors">
                        Lanjutkan Import
                    </button>
                </form>
            </div>
        @else
            <div class="flex gap-6">

                {{-- Left Sidebar: List of conflicting layups --}}
                <div class="w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <div class="flex items-center gap-2 text-red-600 text-sm font-bold">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Conflicting Layups ({{ count($conflicts) }})
                            </div>
                        </div>
                        <div class="p-3 space-y-2">
                            @foreach($conflicts as $i => $conflict)
                                <button @click="currentIndex = {{ $i }}"
                                        class="w-full text-left rounded-lg p-3 border transition-colors relative"
                                        :class="currentIndex === {{ $i }}
                                            ? 'border-[#2D6A4F] bg-green-50 dark:bg-green-900/10'
                                            : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'">
                                    <div class="absolute top-3 right-3">
                                        <template x-if="isFullyResolved(conflicts[{{ $i }}])">
                                            <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                                        </template>
                                        <template x-if="!isFullyResolved(conflicts[{{ $i }}])">
                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                        </template>
                                    </div>
                                    <div class="font-bold text-sm pr-4"
                                         :class="currentIndex === {{ $i }} ? 'text-[#1B4332] dark:text-green-300' : 'text-gray-800 dark:text-gray-200'">
                                        {{ $conflict['layup_name'] }}
                                    </div>
                                    <div class="text-xs mt-0.5"
                                         :class="currentIndex === {{ $i }} ? 'text-[#2D6A4F]' : 'text-gray-500'">
                                        {{ $conflict['conflict_count'] }} layer conflict
                                    </div>
                                </button>
                            @endforeach

                            {{-- Non-conflicting layups from summary --}}
                            @php
                                $newLayups = collect($summary)->where('status', 'new');
                            @endphp
                            @if($newLayups->count() > 0)
                                <div class="pt-2 pb-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">New (No Conflict)</p>
                                </div>
                                @foreach($newLayups as $nl)
                                    <div class="rounded-lg p-3 border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex justify-between items-center opacity-70">
                                        <span class="font-bold text-sm text-gray-600 dark:text-gray-400">{{ $nl['name'] }}</span>
                                        <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Main Content: Side-by-side comparison --}}
                <div class="flex-1">
                    <template x-if="current">
                        <div>
                            {{-- Layup header --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" style="font-family: ui-serif, Georgia, serif;"
                                        x-text="current.layup_name + ' — Comparison'"></h2>
                                    <span class="bg-[#E6D5B8] text-[#6D5438] text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider"
                                          x-text="current.layer_conflicts.length + ' conflict(s)'"></span>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="keepAll()"
                                            class="px-3 py-1.5 border-2 border-gray-300 text-gray-600 rounded text-xs font-bold hover:border-gray-400 transition-colors">
                                        Keep All Existing
                                    </button>
                                    <button @click="acceptAll()"
                                            class="px-3 py-1.5 border-2 border-[#2D6A4F] text-[#2D6A4F] rounded text-xs font-bold hover:bg-green-50 transition-colors">
                                        Accept All Incoming
                                    </button>
                                </div>
                            </div>

                            {{-- Side-by-side table --}}
                            <div class="grid grid-cols-2 gap-4 mb-6">

                                {{-- Existing Version --}}
                                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                                    <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 flex items-center gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-gray-400"></div>
                                        <div>
                                            <div class="font-bold text-sm text-gray-700 dark:text-gray-300">Existing Version</div>
                                            <div class="text-xs text-gray-400">Current data in database</div>
                                        </div>
                                    </div>
                                    <table class="min-w-full text-sm text-center">
                                        <thead class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                                            <tr>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Order</th>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Thickness</th>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Width</th>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Angle</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            <template x-for="layer in current.existing_layers" :key="layer.layer_order">
                                                <tr :class="isConflictLayer(layer.layer_order) ? 'bg-red-50 dark:bg-red-900/10' : ''">
                                                    <td class="py-2.5 px-3 text-gray-500" x-text="layer.layer_order"></td>
                                                    <td class="py-2.5 px-3"
                                                        :class="isConflictLayer(layer.layer_order) ? 'font-bold text-red-600' : 'font-medium text-gray-800 dark:text-gray-200'"
                                                        x-text="layer.thickness + 'mm'"></td>
                                                    <td class="py-2.5 px-3 text-gray-500" x-text="layer.width + 'mm'"></td>
                                                    <td class="py-2.5 px-3 text-gray-500" x-text="layer.angle + '°'"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Incoming Version --}}
                                <div class="bg-white dark:bg-gray-800 rounded-xl border-2 border-[#2D6A4F] shadow-sm overflow-hidden">
                                    <div class="p-4 border-b border-green-100 bg-green-50/30 flex items-center gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#2D6A4F]"></div>
                                        <div>
                                            <div class="font-bold text-sm text-[#1B4332] dark:text-green-300">Incoming Version</div>
                                            <div class="text-xs text-[#2D6A4F]">Data from import file</div>
                                        </div>
                                    </div>
                                    <table class="min-w-full text-sm text-center">
                                        <thead class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                                            <tr>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Order</th>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Thickness</th>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Width</th>
                                                <th class="px-3 py-2 text-xs font-bold text-gray-400 uppercase">Angle</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            <template x-for="layer in current.incoming_layers" :key="layer.layer_order">
                                                <tr :class="isConflictLayer(layer.layer_order) ? 'bg-red-50 dark:bg-red-900/10' : ''">
                                                    <td class="py-2.5 px-3"
                                                        :class="isConflictLayer(layer.layer_order) ? 'font-bold text-red-500' : 'text-gray-500'"
                                                        x-text="layer.layer_order"></td>
                                                    <td class="py-2.5 px-3"
                                                        :class="isConflictLayer(layer.layer_order) ? 'font-bold text-red-700' : 'font-medium text-gray-800 dark:text-gray-200'"
                                                        x-text="layer.thickness + 'mm'"></td>
                                                    <td class="py-2.5 px-3"
                                                        :class="isConflictLayer(layer.layer_order) ? 'text-red-500' : 'text-gray-500'"
                                                        x-text="layer.width + 'mm'"></td>
                                                    <td class="py-2.5 px-3"
                                                        :class="isConflictLayer(layer.layer_order) ? 'text-red-500' : 'text-gray-500'"
                                                        x-text="layer.angle + '°'"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Per-layer decisions --}}
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden mb-4">
                                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50">
                                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Resolve Each Conflict</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Choose which version to keep for each conflicting layer.</p>
                                </div>
                                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <template x-for="lc in current.layer_conflicts" :key="lc.layer_order">
                                        <div class="p-4 flex items-center justify-between gap-4">
                                            <div class="text-sm">
                                                <span class="font-bold text-gray-700 dark:text-gray-300">Layer #<span x-text="lc.layer_order"></span></span>
                                                <span class="text-gray-400 mx-2">—</span>
                                                <span class="text-gray-500">
                                                    Existing: <span class="font-medium text-gray-700" x-text="lc.existing.thickness + 'mm / ' + lc.existing.width + 'mm / ' + lc.existing.angle + '°'"></span>
                                                </span>
                                                <span class="text-gray-400 mx-2">vs</span>
                                                <span class="text-gray-500">
                                                    Incoming: <span class="font-medium text-[#2D6A4F]" x-text="lc.incoming.thickness + 'mm / ' + lc.incoming.width + 'mm / ' + lc.incoming.angle + '°'"></span>
                                                </span>
                                            </div>
                                            <div class="flex gap-2 flex-shrink-0">
                                                <button @click="setDecision(current.layup_name, lc.layer_order, 'keep')"
                                                        :class="getDecision(current.layup_name, lc.layer_order) === 'keep'
                                                            ? 'bg-gray-700 text-white border-gray-700'
                                                            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-gray-400'"
                                                        class="px-3 py-1.5 border-2 rounded text-xs font-bold transition-all">
                                                    ✓ Keep Existing
                                                </button>
                                                <button @click="setDecision(current.layup_name, lc.layer_order, 'accept')"
                                                        :class="getDecision(current.layup_name, lc.layer_order) === 'accept'
                                                            ? 'bg-[#2D6A4F] text-white border-[#2D6A4F]'
                                                            : 'bg-white dark:bg-gray-800 text-[#2D6A4F] border-[#2D6A4F] hover:bg-green-50'"
                                                        class="px-3 py-1.5 border-2 rounded text-xs font-bold transition-all">
                                                    ✓ Accept Incoming
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Navigation --}}
                            <div class="flex items-center justify-between">
                                <button @click="currentIndex = Math.max(0, currentIndex - 1)"
                                        :disabled="currentIndex === 0"
                                        :class="currentIndex === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:text-gray-800 dark:hover:text-gray-100'"
                                        class="text-gray-500 dark:text-gray-400 flex items-center gap-2 text-sm font-bold transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Previous
                                </button>

                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest"
                                      x-text="(currentIndex + 1) + ' of ' + total + ' Conflicting Layups'"></span>

                                <button @click="currentIndex = Math.min(total - 1, currentIndex + 1)"
                                        :disabled="currentIndex >= total - 1"
                                        :class="currentIndex >= total - 1 ? 'opacity-40 cursor-not-allowed' : 'hover:text-[#1B4332]'"
                                        class="text-[#2D6A4F] flex items-center gap-2 text-sm font-bold transition-colors">
                                    Next
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Footer Submit --}}
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span x-text="Object.keys(decisions).length"></span> of
                    <span>{{ collect($conflicts)->sum('conflict_count') }}</span>
                    conflicts resolved.
                </div>

                <form action="{{ route('suppliers.import.resolve', $supplier) }}" method="POST" id="resolveForm">
                    @csrf
                    {{-- Hidden inputs will be injected by Alpine before submit --}}
                    <div id="decisionInputs"></div>
                    <button type="button"
                            @click="
                                const container = document.getElementById('decisionInputs');
                                container.innerHTML = '';
                                let i = 0;
                                for (const [key, action] of Object.entries(decisions)) {
                                    const [layupName, layerOrder] = key.split('|');
                                    container.innerHTML +=
                                        '<input type=\"hidden\" name=\"decisions[' + i + '][layup_name]\" value=\"' + layupName + '\">' +
                                        '<input type=\"hidden\" name=\"decisions[' + i + '][layer_order]\" value=\"' + layerOrder + '\">' +
                                        '<input type=\"hidden\" name=\"decisions[' + i + '][action]\" value=\"' + action + '\">';
                                    i++;
                                }
                                document.getElementById('resolveForm').submit();
                            "
                            class="px-6 py-2 bg-[#2D6A4F] hover:bg-[#1B4332] text-white rounded-md font-medium text-sm transition-colors flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Apply Decisions & Import
                    </button>
                </form>
            </div>
        @endif
    </div>
    </div>
</x-app-layout>