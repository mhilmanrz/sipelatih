<x-layouts.app>
    <x-slot:title>Evaluasi Saya</x-slot>

    <div class="px-8 py-6">
        <div class="mb-8">
            <x-page-title>Evaluasi Saya</x-page-title>
            <p class="text-teal-100 -mt-4">
                Daftar evaluasi kegiatan yang harus Anda isi.
            </p>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <!-- Total Evaluations -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500">Total Evaluasi</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>

            <!-- Completed Evaluations -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500">Sudah Diisi</span>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed'] }}</h3>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Pending Evaluations -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500">Belum Diisi</span>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-500">Tingkat Penyelesaian</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $stats['rate'] }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $stats['rate'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('my-evaluations.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-3">
                    <label for="activity_id" class="text-sm font-semibold text-gray-700 min-w-[120px]">
                        Pilih Kegiatan:
                    </label>
                    <select name="activity_id" id="activity_id" onchange="this.form.submit()" 
                            class="w-full sm:w-[400px] rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 border bg-gray-50 text-gray-800 font-medium">
                        <option value="">Semua Kegiatan / Diklat</option>
                        @foreach ($filterActivities as $act)
                            <option value="{{ $act->id }}" {{ $selectedActivityId == $act->id ? 'selected' : '' }}>
                                {{ $act->activityName->name ?? 'Kegiatan' }} ({{ \Carbon\Carbon::parse($act->start_date)->format('Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if ($selectedActivityId)
                    <div class="flex justify-end">
                        <a href="{{ route('my-evaluations.index') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if ($evaluations->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada evaluasi</h3>
                <p class="text-gray-600">
                    @if ($selectedActivityId)
                        Tidak ada evaluasi yang cocok dengan filter kegiatan yang Anda pilih.
                    @else
                        Belum ada evaluasi yang tersedia untuk Anda saat ini.
                    @endif
                </p>
                @if ($selectedActivityId)
                    <div class="mt-4">
                        <a href="{{ route('my-evaluations.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition">
                            Semua Kegiatan
                        </a>
                    </div>
                @endif
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
