<x-layouts.app>
    <x-slot:title>Evaluasi - {{ $activity->activityName->name ?? 'Kegiatan' }}</x-slot>

    <div class="px-8 py-6">
        {{-- BREADCRUMB --}}
        <a href="{{ route('evaluations.index') }}"
           class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Evaluasi
        </a>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-red-700 text-sm font-medium">{{ $errors->first('error') }}</p>
            </div>
        @endif

        {{-- SECTION ATAS: ACTIVITY INFO & SUMMARY --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $activity->activityName->name ?? 'Kegiatan' }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Kode: {{ $activity->reference_number ?? '-' }} ·
                        {{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('d M Y') }} -
                        {{ \Carbon\Carbon::parse($activity->end_date)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Kategori & Format</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $activity->activityCategory->name ?? '-' }} / {{ $activity->activityFormat->name ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Peserta</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $activity->activityParticipants->count() }} orang</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Narasumber</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $activity->activityMaterials->flatMap(fn($m) => $m->speakers)->unique('id')->count() }} orang</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Status Evaluasi</span>
                    <div class="mt-1 flex flex-wrap gap-2">
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                            @if (isset($evaluations[1]) && $evaluations[1]->is_passed)
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            L1 {{ isset($evaluations[1]) && $evaluations[1]->is_passed ? '✓' : '-' }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                            @if (isset($evaluations[2]) && $evaluations[2]->is_passed)
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            L2 {{ isset($evaluations[2]) && $evaluations[2]->is_passed ? '✓' : '-' }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                            @if (isset($evaluations[3]))
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            L3 {{ isset($evaluations[3]) ? '✓' : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION BAWAH: EVALUATION TABS --}}
        @php
            $activeTab = (int) request()->query('tab', 1);
            if (! in_array($activeTab, [1, 2, 3])) {
                $activeTab = 1;
            }
        @endphp
        <div x-data="{
            activeTab: {{ $activeTab }},
            loading: false,
            loadTab(tab) {
                this.loading = true;
                this.activeTab = tab;
                window.history.pushState({}, '', '{{ route('evaluations.show', $activity->id) }}?tab=' + tab);
                fetch('{{ route('evaluations.load-tab', $activity->id) }}?tab=' + tab)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('tab-content').innerHTML = html;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error('Error loading tab:', err);
                        this.loading = false;
                    });
            }
        }" class="space-y-6">
            {{-- TAB NAVIGATION --}}
            <div class="border-b border-gray-200">
                <div class="flex gap-8">
                    <button @click="loadTab(1)"
                            :class="activeTab === 1 ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                            class="py-4 font-medium text-sm transition">
                        Tab 1: Kepuasan Peserta
                    </button>
                    <button @click="loadTab(2)"
                            :class="activeTab === 2 ? 'border-b-2 border-purple-600 text-purple-600' : 'text-gray-600 hover:text-gray-900'"
                            class="py-4 font-medium text-sm transition">
                        Tab 2: Pre/Post Test
                    </button>
                    <button @click="loadTab(3)"
                            :class="activeTab === 3 ? 'border-b-2 border-orange-600 text-orange-600' : 'text-gray-600 hover:text-gray-900'"
                            class="py-4 font-medium text-sm transition">
                        Tab 3: Evaluasi Dampak
                    </button>
                </div>
            </div>

            {{-- TAB CONTENT CONTAINER --}}
            <div id="tab-content" x-show="!loading" class="space-y-6">
                {!! $tabContent !!}
            </div>

            {{-- LOADING INDICATOR --}}
            <div x-show="loading" class="flex justify-center py-12">
                <div class="inline-flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-600 rounded-full animate-pulse"></div>
                    <p class="text-sm text-gray-600">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
