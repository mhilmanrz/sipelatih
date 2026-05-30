<x-layouts.app>
    <x-slot:title>Evaluasi Saya</x-slot>

    <div class="px-8 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Evaluasi Saya
            </h1>
            <p class="text-gray-600 mt-1">
                Daftar evaluasi kegiatan yang harus Anda isi.
            </p>
        </div>

        @if ($evaluations->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada evaluasi</h3>
                <p class="text-gray-600">Belum ada evaluasi yang tersedia untuk Anda saat ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($evaluations as $evaluation)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $evaluation->participant->activity->activityName->name ?? 'Kegiatan' }}
                                    </h3>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-sm font-medium
                                        @if ($evaluation->evaluation_type === 1)
                                            bg-blue-100 text-blue-800
                                        @elseif ($evaluation->evaluation_type === 2)
                                            bg-purple-100 text-purple-800
                                        @else
                                            bg-orange-100 text-orange-800
                                        @endif">
                                        Level {{ $evaluation->evaluation_type }}
                                    </span>

                                    @if ($evaluation->isSubmitted())
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Belum Diisi
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-600 mb-3">
                                    @if ($evaluation->form_type === 'speaker')
                                        Narasumber: {{ $evaluation->speaker->user->name ?? '-' }}
                                    @elseif ($evaluation->form_type === 'activity')
                                        Evaluasi Kegiatan
                                    @else
                                        Penilaian Dampak
                                    @endif
                                </p>

                                <p class="text-xs text-gray-500">
                                    Dibuat: {{ $evaluation->created_at->locale('id')->format('d F Y') }}
                                    @if ($evaluation->isSubmitted())
                                        · Disubmit: {{ $evaluation->submitted_at->locale('id')->format('d F Y H:i') }}
                                    @endif
                                </p>
                            </div>

                            <div class="ml-6">
                                <a href="{{ route('my-evaluations.show', $evaluation->id) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    {{ $evaluation->isSubmitted() ? 'Lihat' : 'Isi' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($evaluations->hasPages())
                <div class="mt-8">
                    {{ $evaluations->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
