<x-layouts.app>
    <x-slot:title>Evaluasi Kegiatan</x-slot>

    <div class="px-8 py-6">

        {{-- TITLE & HEADER BAR --}}
        <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
            <div>
                <x-page-title>Evaluasi Kegiatan Bertingkat</x-page-title>
                <p class="text-sm text-white/80 mt-1">Lakukan evaluasi penjaminan mutu kegiatan pelatihan secara berjenjang (3 Tingkat).</p>
            </div>

            {{-- YEAR FILTER --}}
            <form action="{{ route('evaluations.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <label for="year" class="text-sm font-semibold text-white/90 whitespace-nowrap">Tahun:</label>
                <select name="year" id="year" onchange="this.form.submit()"
                    class="px-3 py-2 bg-white border border-white/30 rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-white/50 shadow-sm">
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- STEPPER / TABS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            @php
                $steps = \App\Models\Act\ActivityEvaluation::getLevels();
            @endphp
            @foreach ($steps as $num => $step)
                <a href="{{ route('evaluations.index', ['year' => $selectedYear, 'tab' => $num, 'q' => request('q')]) }}"
                    class="group relative flex items-center gap-4 p-4 rounded-xl border-2 transition-all duration-200 
                        {{ $activeTab === $num
                            ? 'bg-white border-[#007a7a] shadow-md'
                            : 'bg-white/80 border-white/60 hover:bg-white hover:border-[#007a7a]/40 hover:shadow-sm' }}">
                    {{-- Step Number Badge --}}
                    <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 transition-colors duration-200
                        {{ $activeTab === $num ? 'bg-[#007a7a] text-white shadow-lg' : 'bg-gray-100 text-gray-400 group-hover:bg-[#007a7a]/10 group-hover:text-[#007a7a]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold px-1.5 py-0.5 rounded 
                                {{ $activeTab === $num ? 'bg-[#007a7a]/10 text-[#007a7a]' : 'bg-gray-100 text-gray-400' }}">
                                {{ $num }}
                            </span>
                            <h3 class="font-bold text-sm truncate {{ $activeTab === $num ? 'text-[#007a7a]' : 'text-gray-700' }}">
                                {{ $step['label'] }}
                            </h3>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $step['sub'] }}</p>
                    </div>
                    @if ($activeTab === $num)
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <div class="w-2 h-2 bg-[#007a7a] rounded-full"></div>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- SEARCH & COUNT BAR --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5 flex flex-wrap items-center justify-between gap-3">
            <form action="{{ route('evaluations.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="year" value="{{ $selectedYear }}">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kegiatan..."
                        class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-700 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#007a7a]/30 focus:border-[#007a7a] w-64 transition">
                </div>
                <button type="submit" class="px-4 py-2 bg-[#007a7a] hover:bg-[#005f5f] text-white text-sm font-semibold rounded-lg transition">
                    Cari
                </button>
                @if (request('q'))
                    <a href="{{ route('evaluations.index', ['year' => $selectedYear, 'tab' => $activeTab]) }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition">
                        Reset
                    </a>
                @endif
            </form>

            <div class="text-sm text-gray-500">
                Menampilkan <span class="font-bold text-gray-800">{{ $activities->total() }}</span> kegiatan
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 800px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3.5 px-4 font-semibold text-xs uppercase tracking-wide">No.</th>
                            <th class="text-left py-3.5 px-4 font-semibold text-xs uppercase tracking-wide">Nama Kegiatan</th>
                            <th class="text-left w-44 py-3.5 px-4 font-semibold text-xs uppercase tracking-wide">Tanggal Pelaksanaan</th>
                            <th class="text-right w-40 py-3.5 px-4 font-semibold text-xs uppercase tracking-wide">Anggaran</th>
                            <th class="text-center w-44 py-3.5 px-4 font-semibold text-xs uppercase tracking-wide">Status Eval. {{ $activeTab }}</th>
                            <th class="text-center w-36 py-3.5 px-4 font-semibold text-xs uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($activities as $index => $activity)
                            @php
                                $eval = $activity->evaluations->first();
                            @endphp
                            <tr class="hover:bg-teal-50/30 transition-colors group">
                                <td class="text-center py-4 px-4 text-gray-400 font-medium text-xs">
                                    {{ $activities->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-gray-900 leading-tight group-hover:text-[#007a7a] transition-colors">
                                        {{ $activity->activityName->name ?? 'Kegiatan Tanpa Nama' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                                        <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-500">{{ $activity->reference_number ?? '-' }}</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $activity->quota_participant ?? '0' }} Peserta</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-gray-600 text-xs leading-relaxed">
                                        <span class="block">{{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('d M Y') }}</span>
                                        <span class="text-gray-300 text-[10px]">s/d</span>
                                        <span class="block">{{ \Carbon\Carbon::parse($activity->end_date)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <span class="font-semibold text-gray-800 text-xs">
                                        Rp {{ number_format($activity->budget_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if ($eval)
                                        @if ($eval->is_passed)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Lulus
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Tidak Lulus
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                                <path d="M12 8v4l3 3" stroke-width="2" stroke-linecap="round"></path>
                                            </svg>
                                            Belum Dievaluasi
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('evaluations.show', $activity->id) }}"
                                       class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm
                                            {{ $eval ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-[#007a7a] hover:bg-[#005f5f] text-white' }}">
                                        @if ($eval)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            Buka
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 px-4">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <p class="text-base font-semibold text-gray-600">Tidak ada kegiatan ditemukan</p>
                                        <p class="text-sm text-gray-400 max-w-xs text-center">
                                            Kegiatan mungkin belum berstatus <span class="font-semibold">disetujui (accepted)</span> atau belum lulus evaluasi tingkat sebelumnya.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $activities->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-layouts.app>
