<x-layouts.app>
    <x-slot:title>Detail Evaluasi Kegiatan</x-slot>

    <div class="px-8 py-6">
        {{-- BREADCRUMB --}}
        <div class="flex justify-between items-center mb-5">
            <a href="{{ route('evaluations.index') }}"
               class="inline-flex items-center gap-2 text-white/80 hover:text-white transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Evaluasi
            </a>
            <span class="text-xs text-white/60 font-mono">ID #{{ $activity->id }}</span>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-5 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-5 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">{{ $errors->first('error') }}</span>
            </div>
        @endif

        {{-- HEADER CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-[#007a7a] border border-teal-200 mb-2">
                        {{ $activity->activityType->name ?? 'Kegiatan' }}
                    </span>
                    <h1 class="text-xl font-bold text-gray-900 leading-snug">
                        {{ $activity->activityName->name ?? 'Kegiatan Tanpa Nama' }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-3 flex-wrap">
                        <span>Kode: <span class="font-mono font-semibold text-gray-700">{{ $activity->reference_number ?? '-' }}</span></span>
                        <span class="text-gray-300">•</span>
                        <span>PIC: <span class="font-semibold text-gray-700">{{ $activity->user->name ?? '-' }}</span></span>
                    </p>
                </div>
                {{-- STATUS BADGES PER LEVEL --}}
                <div class="flex flex-wrap gap-2">
                    @for ($l = 1; $l <= 3; $l++)
                        @php $ev = $evaluations[$l] ?? null; @endphp
                        <div class="px-3 py-1.5 rounded-lg border text-xs font-semibold flex items-center gap-1.5
                            {{ $ev ? ($ev->is_passed ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200') : 'bg-gray-50 text-gray-400 border-gray-200' }}">
                            <span>Tingkat {{ $l }}:</span>
                            @if ($ev)
                                <span>{{ $ev->is_passed ? 'LULUS' : 'GAGAL' }}</span>
                            @else
                                <span class="font-normal">Belum</span>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- 2 COLUMN LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- LEFT: ACTIVITY DETAIL (4 cols) --}}
            <div class="lg:col-span-4 space-y-5">

                {{-- INFO CARD --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                        <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Informasi Kegiatan</h2>
                    </div>
                    <div class="p-4 space-y-3.5 text-sm">
                        @php
                            $infoRows = [
                                ['label' => 'Kategori & Format', 'value' => ($activity->activityCategory->name ?? '-') . ' (' . ($activity->activityFormat->name ?? '-') . ')'],
                                ['label' => 'Metode', 'value' => $activity->activityMethod->name ?? '-'],
                                ['label' => 'Tempat', 'value' => $activity->tempat ?? '-'],
                                ['label' => 'Tanggal', 'value' => \Carbon\Carbon::parse($activity->start_date)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($activity->end_date)->translatedFormat('d M Y')],
                                ['label' => 'Sumber Anggaran', 'value' => $activity->fundSource->name ?? '-'],
                                ['label' => 'Unit Kerja', 'value' => $activity->workUnit->name ?? '-'],
                            ];
                        @endphp
                        @foreach ($infoRows as $row)
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase mb-0.5">{{ $row['label'] }}</span>
                                <span class="text-gray-800 font-medium">{{ $row['value'] }}</span>
                            </div>
                        @endforeach
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase mb-0.5">Total Pagu</span>
                            <span class="text-[#007a7a] font-bold text-base">Rp {{ number_format($activity->budget_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- STATS CARD --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                        <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Statistik & Kurikulum</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-teal-50 rounded-lg p-3 text-center border border-teal-100">
                                <span class="block text-xs text-teal-600 font-semibold mb-1">Peserta</span>
                                <span class="text-2xl font-extrabold text-[#007a7a]">{{ count($activity->activityParticipants) }}</span>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-100">
                                <span class="block text-xs text-blue-600 font-semibold mb-1">Total JPL</span>
                                <span class="text-2xl font-extrabold text-blue-700">{{ $activity->activityMaterials->sum('value') }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase mb-2">Mata Pelatihan</span>
                            @if (count($activity->activityMaterials) > 0)
                                <div class="divide-y divide-gray-100 max-h-44 overflow-y-auto border border-gray-100 rounded-lg">
                                    @foreach ($activity->activityMaterials as $mat)
                                        <div class="px-3 py-2 flex justify-between gap-2 text-xs">
                                            <span class="text-gray-700 font-medium">{{ $mat->name }}</span>
                                            <span class="text-[#007a7a] font-bold shrink-0">{{ $mat->value }} JPL</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400 text-xs italic">Belum ada materi pelatihan.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: EVALUATION FORMS (8 cols) --}}
            <div class="lg:col-span-8 space-y-5">
                @for ($level = 1; $level <= 3; $level++)
                    @php
                        $isUnlocked = $unlockedLevels[$level];
                        $eval       = $evaluations[$level] ?? null;
                        $criteria   = ${'criteria' . $level};
                        $criteriaValues = $eval ? $eval->criteriaValues->keyBy('evaluation_criteria_id') : collect();
                        $levelMeta  = \App\Models\Act\ActivityEvaluation::getLevels()[$level];
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden
                        {{ $isUnlocked ? 'border-gray-100' : 'border-gray-100 opacity-70' }}">

                        {{-- Level Header --}}
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100
                            {{ $isUnlocked ? 'bg-gray-50/60' : 'bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0
                                    {{ $isUnlocked ? 'bg-[#007a7a] text-white' : 'bg-gray-200 text-gray-400' }}">
                                    {{ $level }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm {{ $isUnlocked ? 'text-gray-900' : 'text-gray-400' }}">
                                        Tingkat {{ $level }}: {{ $levelMeta['label'] }}
                                    </h3>
                                    @if ($eval)
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Dinilai oleh <span class="font-semibold">{{ $eval->evaluator->name ?? 'Sistem' }}</span>
                                            · {{ \Carbon\Carbon::parse($eval->evaluated_at)->translatedFormat('d M Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if ($eval)
                                    @if ($eval->is_passed)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Lulus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            Tidak Lulus
                                        </span>
                                    @endif
                                @elseif (!$isUnlocked)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Terkunci
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Level Body --}}
                        <div class="p-5">
                            @if (!$isUnlocked)
                                <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-500">
                                    <svg class="w-5 h-5 text-gray-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <p>Formulir dikunci. Selesaikan dan luluskan <strong>Evaluasi Tingkat {{ $level - 1 }}</strong> untuk membuka tingkat ini.</p>
                                </div>
                            @else
                                <form action="{{ route('evaluations.store', $activity->id) }}" method="POST" class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="evaluation_type" value="{{ $level }}">

                                    {{-- HASIL KELULUSAN --}}
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                                            Hasil Kelulusan Tingkat {{ $level }}
                                            <span class="text-red-500 ml-0.5">*</span>
                                        </label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" name="is_passed" value="1"
                                                    {{ (old('is_passed') === '1' || ($eval && $eval->is_passed)) ? 'checked' : '' }}
                                                    required class="h-4 w-4 text-[#007a7a] focus:ring-[#007a7a] border-gray-300">
                                                <span class="text-sm font-bold text-emerald-700 bg-emerald-50 group-hover:bg-emerald-100 px-3 py-1.5 rounded-lg border border-emerald-200 transition">
                                                    ✓ LULUS
                                                </span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" name="is_passed" value="0"
                                                    {{ (old('is_passed') === '0' || ($eval && !$eval->is_passed && $eval !== null)) ? 'checked' : '' }}
                                                    required class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300">
                                                <span class="text-sm font-bold text-red-700 bg-red-50 group-hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-200 transition">
                                                    ✗ TIDAK LULUS
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- KRITERIA PENILAIAN --}}
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                            <span>Kriteria Penilaian</span>
                                            <span class="text-xs text-gray-400 font-normal">({{ count($criteria) }} kriteria)</span>
                                        </h4>

                                        @if (count($criteria) > 0)
                                            <div class="space-y-2.5">
                                                @foreach ($criteria as $criterion)
                                                    @php
                                                        $cVal    = $criteriaValues[$criterion->id] ?? null;
                                                        $cPassed = $cVal ? $cVal->is_passed : false;
                                                        $cValue  = $cVal ? $cVal->value : '';
                                                    @endphp
                                                    <div class="flex flex-wrap items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg hover:border-[#007a7a]/30 transition-colors">
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span class="font-mono text-xs font-bold bg-[#007a7a]/10 text-[#007a7a] px-1.5 py-0.5 rounded">
                                                                    {{ $criterion->code }}
                                                                </span>
                                                                <span class="font-medium text-sm text-gray-800">{{ $criterion->name }}</span>
                                                            </div>
                                                            @if ($criterion->is_fillable)
                                                                <div class="mt-2">
                                                                    @if ($criterion->type === 'number')
                                                                        <input type="number" step="any"
                                                                            name="criteria[{{ $criterion->id }}][value]"
                                                                            id="crit_{{ $criterion->id }}_val"
                                                                            value="{{ old('criteria.' . $criterion->id . '.value', $cValue) }}"
                                                                            placeholder="Masukkan angka..."
                                                                            class="w-48 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#007a7a] focus:border-[#007a7a] bg-white">
                                                                    @else
                                                                        <input type="text"
                                                                            name="criteria[{{ $criterion->id }}][value]"
                                                                            id="crit_{{ $criterion->id }}_val"
                                                                            value="{{ old('criteria.' . $criterion->id . '.value', $cValue) }}"
                                                                            placeholder="Masukkan teks..."
                                                                            class="w-64 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#007a7a] focus:border-[#007a7a] bg-white">
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <input type="hidden" name="criteria[{{ $criterion->id }}][value]" value="">
                                                            @endif
                                                        </div>
                                                        <label class="flex items-center gap-2 cursor-pointer shrink-0">
                                                            <input type="hidden" name="criteria[{{ $criterion->id }}][is_passed]" value="0">
                                                            <input type="checkbox"
                                                                name="criteria[{{ $criterion->id }}][is_passed]"
                                                                id="crit_{{ $criterion->id }}_pass"
                                                                value="1"
                                                                {{ old('criteria.' . $criterion->id . '.is_passed', $cPassed) ? 'checked' : '' }}
                                                                class="h-4 w-4 text-[#007a7a] focus:ring-[#007a7a] border-gray-300 rounded">
                                                            <span class="text-sm font-semibold text-gray-600">Terpenuhi</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-6 text-gray-400 text-sm bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                                <p>Belum ada master kriteria evaluasi tingkat {{ $level }}.</p>
                                                <a href="{{ route('evaluation-criteria.create') }}" class="text-[#007a7a] hover:underline font-semibold mt-1 block text-xs">Tambah Kriteria →</a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- CATATAN --}}
                                    <div>
                                        <label for="notes_{{ $level }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Catatan / Rekomendasi Evaluator
                                        </label>
                                        <textarea name="notes" id="notes_{{ $level }}" rows="3"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007a7a]/30 focus:border-[#007a7a] text-sm bg-white resize-none transition"
                                            placeholder="Tuliskan catatan evaluasi di sini...">{{ old('notes', $eval?->notes) }}</textarea>
                                    </div>

                                    {{-- SUBMIT --}}
                                    <div class="flex justify-end pt-1">
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#007a7a] hover:bg-[#005f5f] active:bg-[#004d4d] text-white rounded-lg text-sm font-bold transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                            </svg>
                                            Simpan Evaluasi Tingkat {{ $level }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</x-layouts.app>
