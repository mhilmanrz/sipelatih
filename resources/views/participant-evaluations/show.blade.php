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

        <form action="{{ route('my-evaluations.store', $evaluation->id) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf

            @php
                $ratingLabels = $evaluation->evaluation_type === 3
                    ? [1 => 'Tidak Setuju', 2 => 'Kurang Setuju', 3 => 'Setuju', 4 => 'Sangat Setuju']
                    : [1 => 'Sangat Kurang', 2 => 'Kurang', 3 => 'Baik', 4 => 'Sangat Baik'];
            @endphp

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
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        @foreach ($ratingLabels as $rating => $label)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="answers[{{ $crit->id }}]" value="{{ $rating }}"
                                                    @checked(optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating)
                                                    @disabled($evaluation->isSubmitted())
                                                    class="sr-only peer">
                                                <span class="block h-full px-3 py-3 rounded-xl border-2 transition text-center
                                                    @disabled($evaluation->isSubmitted()) opacity-60 cursor-not-allowed @enddisabled
                                                    peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-800
                                                    border-gray-200 bg-white {{ $evaluation->isSubmitted() ? '' : 'hover:border-teal-300 hover:bg-teal-50/50 text-gray-700' }}">
                                                    <div class="text-teal-500 mb-1">
                                                        @for ($star = 1; $star <= $rating; $star++) ★ @endfor
                                                    </div>
                                                    <div class="text-xs font-medium leading-tight">{{ $rating }} - {{ $label }}</div>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif ($crit->type === 'file')
                                    @php $existingFile = $evaluation->files->firstWhere('evaluation_criteria_id', $crit->id); @endphp
                                    <div class="space-y-2">
                                        @if ($existingFile)
                                            <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/></svg>
                                                File sudah diupload: {{ $existingFile->file_name ?? $existingFile->original_name }}
                                            </div>
                                        @endif
                                        @if (! $evaluation->isSubmitted())
                                            <input type="file" name="files[{{ $crit->id }}]"
                                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-teal-600 file:text-white hover:file:bg-teal-700"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx">
                                            <p class="text-xs text-gray-500">Maks. 10 MB. Format: PDF, Word, Excel, atau gambar.</p>
                                        @endif
                                    </div>
                                    @error("files.{$crit->id}")
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                                @else
                                    <div>
                                        <textarea name="answers[{{ $crit->id }}]" rows="3"
                                            @disabled($evaluation->isSubmitted())
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @disabled($evaluation->isSubmitted()) opacity-60 cursor-not-allowed @enddisabled"
                                            placeholder="Tulis jawaban Anda di sini...">{{ old("answers.{$crit->id}", optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->answer_text) }}</textarea>
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
