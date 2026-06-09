<x-layouts.app>
    <x-slot:title>Kegiatanku</x-slot>

    <div class="px-8 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Kegiatanku
            </h1>
            <p class="text-gray-600 mt-1">
                Daftar kegiatan pelatihan di mana Anda terdaftar sebagai peserta, narasumber, atau moderator.
            </p>
        </div>

        @if ($activities->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada kegiatan</h3>
                <p class="text-gray-600">Anda belum terdaftar dalam kegiatan apapun saat ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($activities as $activity)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $activity->activityName->name ?? $activity->name ?? 'Kegiatan' }}
                                    </h3>
                                    
                                    @if ($activity->latestStatus && $activity->latestStatus->status === 'accepted')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($activity->latestStatus->status ?? 'Draft') }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-600 mb-3">
                                    <i class="fa-regular fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($activity->start_date)->locale('id')->isoFormat('D MMMM Y') }} 
                                    - {{ \Carbon\Carbon::parse($activity->end_date)->locale('id')->isoFormat('D MMMM Y') }}
                                    
                                    <span class="mx-2">•</span>
                                    
                                    <i class="fa-solid fa-location-dot mr-2"></i>
                                    {{ $activity->location ?? 'Belum ditentukan' }}
                                </p>
                            </div>

                            <div class="ml-6">
                                <a href="{{ route('my-activities.show', $activity->id) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
