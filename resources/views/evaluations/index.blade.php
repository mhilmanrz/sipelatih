<x-layouts.app>
    <x-slot:title>Evaluasi Kegiatan</x-slot>

    <div class="px-8 py-6">
        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Evaluasi Kegiatan</h1>
            <p class="text-gray-600 mt-1">Kelola evaluasi kegiatan dalam 3 level independent.</p>
        </div>

        {{-- FILTERS --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form action="{{ route('evaluations.index') }}" method="GET" class="flex flex-wrap gap-4">
                {{-- YEAR FILTER --}}
                <div class="flex-1 min-w-[200px]">
                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select id="year" name="year"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" @selected($yr == $selectedYear)>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- SEARCH FILTER --}}
                <div class="flex-1 min-w-[200px]">
                    <label for="q" class="block text-sm font-semibold text-gray-700 mb-2">Cari</label>
                    <input type="text" id="q" name="q" value="{{ request('q') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="Nama kegiatan...">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- TABS --}}
        <div class="mb-6">
            <div class="flex gap-4 border-b border-gray-200">
                @for ($level = 1; $level <= 3; $level++)
                    <a href="{{ route('evaluations.index', ['year' => $selectedYear, 'tab' => $level, 'q' => request('q')]) }}"
                       class="px-6 py-3 font-medium transition
                        @if ($activeTab === $level)
                            text-teal-600 border-b-2 border-teal-600
                        @else
                            text-gray-600 hover:text-gray-900
                        @endif">
                        Level {{ $level }}
                        @if ($level === 1)
                            (Kepuasan Peserta)
                        @elseif ($level === 2)
                            (Pre/Post Test)
                        @else
                            (Penilaian Dampak)
                        @endif
                    </a>
                @endfor
            </div>
        </div>

        {{-- CONTENT AREA --}}
        @if ($activities->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada kegiatan</h3>
                <p class="text-gray-600">
                    @if ($activeTab === 2)
                        Belum ada kegiatan yang lulus Level 1. Selesaikan evaluasi Level 1 terlebih dahulu.
                    @elseif ($activeTab === 3)
                        Belum ada kegiatan yang lulus Level 2. Selesaikan evaluasi Level 2 terlebih dahulu.
                    @else
                        Belum ada kegiatan untuk ditampilkan.
                    @endif
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($activities as $activity)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $activity->activityName->name ?? 'Kegiatan' }}
                                    </h3>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $activity->activityType->name ?? '-' }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-3">
                                    Kode: <span class="font-mono font-semibold">{{ $activity->reference_number ?? '-' }}</span>
                                    · Peserta: <span class="font-semibold">{{ count($activity->activityParticipants) }}</span>
                                    · PIC: <span class="font-semibold">{{ $activity->user->name ?? '-' }}</span>
                                </p>

                                <div class="flex flex-wrap gap-2 text-xs">
                                    @php
                                        $levelEval = $activity->evaluations->firstWhere('evaluation_type', $activeTab);
                                    @endphp

                                    @if ($activeTab === 1)
                                        <span class="inline-block px-2.5 py-1 rounded-full font-medium
                                            @if ($levelEval && $levelEval->is_passed)
                                                bg-green-100 text-green-800
                                            @else
                                                bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $levelEval && $levelEval->is_passed ? '✓ Sudah Dinilai' : '○ Belum Dinilai' }}
                                        </span>
                                    @elseif ($activeTab === 2)
                                        <span class="inline-block px-2.5 py-1 rounded-full font-medium bg-purple-100 text-purple-800">
                                            Pre/Post Test
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 rounded-full font-medium
                                            @if ($activity->evaluations->firstWhere('evaluation_type', 3) !== null)
                                                bg-green-100 text-green-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $activity->evaluations->firstWhere('evaluation_type', 3) !== null ? '✓ Aktif' : '○ Nonaktif' }}
                                        </span>
                                    @endif

                                    <span class="text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($activity->end_date)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="ml-6">
                                <a href="{{ route('evaluations.show', $activity->id) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($activities->hasPages())
                <div class="mt-8">
                    {{ $activities->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
