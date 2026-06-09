<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluasi - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-2xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    @if ($evaluation->evaluation_type === 1)
                        Formulir Evaluasi Penyelenggaraan
                    @else
                        Formulir Evaluasi Implementasi Pelatihan
                    @endif
                </h1>
                <p class="text-gray-600">
                    {{ $evaluation->participant->activity->activityName->name ?? 'Kegiatan' }}
                </p>
                @if ($evaluation->form_type === 'speaker')
                    <p class="text-sm text-gray-500 mt-1">
                        Narasumber: {{ $evaluation->speaker->user->name ?? '-' }}
                    </p>
                @endif
            </div>

            @if ($evaluation->isSubmitted())
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-green-900 mb-1">Evaluasi Terselesaikan</h2>
                    <p class="text-green-700">Terima kasih telah mengisi evaluasi ini.</p>
                </div>
            @else
                <form action="{{ route('public-evaluations.store', $evaluation->token) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @php
                        // Rating labels differ by evaluation type
                        $ratingLabels = $evaluation->evaluation_type === 3
                            ? [1 => 'Tidak Setuju', 2 => 'Kurang Setuju', 3 => 'Setuju', 4 => 'Sangat Setuju']
                            : [1 => 'Sangat Kurang', 2 => 'Kurang', 3 => 'Baik', 4 => 'Sangat Baik'];
                    @endphp

                    @foreach ($criteria as $categoryId => $categoryItems)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                                <h2 class="font-bold text-gray-700">
                                    @php $cat = $categoryItems->first()?->category; @endphp
                                    {{ $cat?->name ?? 'Umum' }}
                                </h2>
                            </div>

                            <div class="p-6 space-y-6">
                                @foreach ($categoryItems as $crit)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                                            {{ $crit->name }}
                                            @if ($crit->type === 'rating') <span class="text-red-500">*</span> @endif
                                        </label>

                                        @if ($crit->type === 'rating')
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                @foreach ($ratingLabels as $rating => $label)
                                                    @php $checked = optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating; @endphp
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="answers[{{ $crit->id }}]" value="{{ $rating }}"
                                                            @checked($checked) class="sr-only peer">
                                                        <span class="block px-3 py-3 rounded-xl border-2 transition text-center
                                                            peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-800
                                                            border-gray-200 bg-white hover:border-teal-300 hover:bg-teal-50/50 text-gray-700">
                                                            <div class="text-teal-500 mb-1 text-base">
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
                                                <input type="file" name="files[{{ $crit->id }}]"
                                                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-teal-600 file:text-white hover:file:bg-teal-700"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx">
                                                <p class="text-xs text-gray-500">Maks. 10 MB. Format: PDF, Word, Excel, atau gambar.</p>
                                            </div>

                                        @else
                                            <textarea name="answers[{{ $crit->id }}]" rows="3"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                                placeholder="Tulis jawaban Anda di sini...">{{ old("answers.{$crit->id}", optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->answer_text) }}</textarea>
                                        @endif

                                        @error("answers.{$crit->id}")
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        @error("files.{$crit->id}")
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                            Kirim Evaluasi
                        </button>
                    </div>

                    @if ($errors->has('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-700 text-sm">{{ $errors->first('error') }}</p>
                        </div>
                    @endif
                </form>
            @endif
        </div>
    </div>
</body>
</html>

