<x-layouts.app>
    <x-slot:title>Evaluasi - {{ $evaluation->participant->activity->activityName->name ?? 'Kegiatan' }}</x-slot>

    <div class="px-8 py-6">
        <div class="mb-6">
            <a href="{{ route('my-evaluations.index') }}"
               class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Evaluasi
            </a>
        </div>

        @if ($evaluation->isSubmitted())
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="font-medium text-green-900">Evaluasi Terselesaikan</h3>
                    <p class="text-sm text-green-700">Evaluasi Anda telah disubmit pada {{ $evaluation->submitted_at->locale('id')->format('d F Y H:i') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                {{ $evaluation->participant->activity->activityName->name ?? 'Kegiatan' }}
            </h1>
            <p class="text-gray-600">
                Level {{ $evaluation->evaluation_type }}
                @if ($evaluation->form_type)
                    · {{ $evaluation->form_type === 'speaker' ? 'Narasumber: ' . ($evaluation->speaker->user->name ?? '-') : 'Kegiatan' }}
                @endif
            </p>
        </div>

        <form action="{{ route('my-evaluations.store', $evaluation->id) }}" method="POST" class="space-y-6">
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
                                <div class="flex gap-2">
                                    @for ($rating = 1; $rating <= 4; $rating++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="answers[{{ $crit->id }}]" value="{{ $rating }}"
                                                @checked(optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating)
                                                @disabled($evaluation->isSubmitted())
                                                class="sr-only">
                                            <span class="inline-block px-4 py-2 rounded-lg border-2 transition
                                                @disabled($evaluation->isSubmitted()) opacity-60 cursor-not-allowed @enddisabled
                                                @checked(optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating)
                                                    border-teal-500 bg-teal-50
                                                @else
                                                    border-gray-200 bg-white {{ $evaluation->isSubmitted() ? '' : 'hover:border-gray-300' }}
                                                @endchecked">
                                                @for ($star = 1; $star <= $rating; $star++)
                                                    ★
                                                @endfor
                                            </span>
                                        </label>
                                    @endfor
                                </div>
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
                        @disabled($evaluation->isSubmitted())
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @disabled($evaluation->isSubmitted()) opacity-60 cursor-not-allowed @enddisabled">{{ old('supervisor_recommendation', $evaluation->supervisor_recommendation) }}</textarea>
                    @error('supervisor_recommendation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            @if (!$evaluation->isSubmitted())
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                        Simpan Evaluasi
                    </button>
                    <a href="{{ route('my-evaluations.index') }}"
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            @endif
        </form>
    </div>
</x-layouts.app>
