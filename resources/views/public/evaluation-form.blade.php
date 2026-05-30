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
                    Formulir Evaluasi
                </h1>
                <p class="text-gray-600">
                    {{ $evaluation->participant->activity->activityName->name ?? 'Kegiatan' }}
                </p>
                @if ($evaluation->form_type)
                    <p class="text-sm text-gray-500 mt-1">
                        @if ($evaluation->form_type === 'speaker')
                            Narasumber: {{ $evaluation->speaker->user->name ?? '-' }}
                        @else
                            Evaluasi Kegiatan
                        @endif
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
                <form action="{{ route('public-evaluations.store', $evaluation->token) }}" method="POST" class="space-y-6">
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
                                                        class="sr-only">
                                                    <span class="inline-block px-4 py-2 rounded-lg border-2 transition
                                                        @checked(optional($evaluation->answers->firstWhere('evaluation_criteria_id', $crit->id))->rating === $rating)
                                                            border-teal-500 bg-teal-50
                                                        @else
                                                            border-gray-200 bg-white hover:border-gray-300
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('supervisor_recommendation') }}</textarea>
                            @error('supervisor_recommendation')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

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
