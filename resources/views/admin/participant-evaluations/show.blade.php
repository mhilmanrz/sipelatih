<x-layouts.app>
    <x-slot:title>Isi Evaluasi - {{ $evaluation->participant->user->name }}</x-slot>

    <div class="px-8 py-6">
        <div class="mb-6">
            <a href="{{ route('evaluations.show', $evaluation->participant->activity_id) }}"
               class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail Kegiatan
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                {{ $evaluation->participant->user->name }}
            </h1>
            <p class="text-gray-600">
                {{ $evaluation->participant->activity->activityName->name ?? 'Kegiatan' }} ·
                Level {{ $evaluation->evaluation_type }}
                @if ($evaluation->form_type)
                    · {{ $evaluation->form_type === 'speaker' ? 'Narasumber: ' . ($evaluation->speaker->user->name ?? '-') : 'Kegiatan' }}
                @endif
            </p>
        </div>

        <form action="{{ route('admin-participant-evaluations.store', $evaluation->id) }}" method="POST" class="space-y-6">
            @csrf

            @foreach ($criteria as $categoryId => $categoryItems)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h2 class="font-bold text-gray-700">
                            @php
                                $cat = $categoryItems->first()?->category;
                            @endphp
                            {{ $cat?->name ?? 'Uncategorized' }}
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        @foreach ($categoryItems as $crit)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ $crit->name }}
                                </label>
                                @if ($crit->type === 'rating')
                                    @php
                                        $ratingLabels = [
                                            1 => 'Sangat Kurang/Tidak Memuaskan',
                                            2 => 'Kurang/Kurang Memuaskan',
                                            3 => 'Baik/Memuaskan',
                                            4 => 'Sangat Baik/Sangat Memuaskan',
                                        ];
                                    @endphp
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                        @foreach ($ratingLabels as $rating => $label)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="answers[{{ $crit->id }}]" value="{{ $rating }}"
                                                    @checked(optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating)
                                                    disabled
                                                    class="sr-only">
                                                <span class="block h-full px-4 py-3 rounded-xl border-2 transition text-center
                                                    opacity-80 cursor-not-allowed
                                                    @if(optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating)
                                                        border-teal-500 bg-teal-50 text-teal-800
                                                    @else
                                                        border-gray-200 bg-white text-gray-700
                                                    @endif">
                                                    <div class="text-teal-500 mb-1">
                                                        @for ($star = 1; $star <= $rating; $star++)
                                                            ★
                                                        @endfor
                                                    </div>
                                                    <div class="text-sm font-medium">{{ $rating }} - {{ $label }}</div>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div>
                                        <textarea name="answers[{{ $crit->id }}]" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                            placeholder="Tulis jawaban di sini...">{{ old("answers.{$crit->id}", optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->answer_text) }}</textarea>
                                    </div>
                                @endif
                                @error("answers.{$crit->id}")
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if ($evaluation->evaluation_type === 3)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Rekomendasi/Saran Atasan Langsung
                    </label>
                    <textarea name="supervisor_recommendation" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('supervisor_recommendation', $evaluation->supervisor_recommendation) }}</textarea>
                    @error('supervisor_recommendation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                    Simpan Evaluasi
                </button>
                <a href="{{ route('evaluations.show', $evaluation->participant->activity_id) }}"
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
