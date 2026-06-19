<x-layouts.app>
    <x-slot:title>Detail Kegiatan: {{ $activity->activityName->name ?? $activity->name ?? 'Kegiatan' }}</x-slot>

    <div class="px-8 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('my-activities.index') }}" class="text-teal-600 hover:text-teal-700 flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Kegiatanku
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $activity->activityName->name ?? $activity->name ?? 'Kegiatan' }}
                </h1>
            </div>
            
            <div class="flex gap-2">
                @if ($isParticipant)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fa-solid fa-user-check"></i> Peserta
                    </span>
                @endif
                @if ($isSpeaker)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <i class="fa-solid fa-microphone"></i> Narasumber
                    </span>
                @endif
                @if ($isModerator)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                        <i class="fa-solid fa-users-viewfinder"></i> Moderator
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Activity Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Informasi Kegiatan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal Mulai</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($activity->start_date)->locale('id')->isoFormat('D MMMM Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal Selesai</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($activity->end_date)->locale('id')->isoFormat('D MMMM Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Lokasi</p>
                            <p class="font-medium">{{ $activity->location ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipe / Kategori</p>
                            <p class="font-medium">
                                {{ $activity->activityType->name ?? '-' }} / {{ $activity->activityCategory->name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    @if($activity->description)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-1">Deskripsi</p>
                            <p class="text-gray-800">{{ $activity->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Materials Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Materi Kegiatan</h3>
                    
                    @if($activity->materials && $activity->materials->count() > 0)
                        <div class="space-y-3">
                            @foreach($activity->materials as $material)
                                <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $material->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $material->speaker->user->name ?? 'Materi Umum' }}</p>
                                        </div>
                                    </div>
                                    @if($material->file_path)
                                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" 
                                           class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded text-sm font-medium hover:bg-blue-100 transition">
                                            Unduh
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Belum ada materi yang diunggah untuk kegiatan ini.</p>
                    @endif
                </div>
            </div>

            <!-- Right Column: Status & Certificate -->
            <div class="space-y-6">
                <!-- Status & Certificate Card -->
                @if($isParticipant)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Status Kelulusan</h3>
                        
                        @if($participantData && $participantData->is_passed)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-check-circle text-2xl"></i>
                                </div>
                                <h4 class="font-bold text-green-800 mb-1">Lulus</h4>
                                <p class="text-sm text-green-700 mb-4">Selamat! Anda telah lulus kegiatan ini.</p>
                                
                                @if($participantData->certificate_number)
                                    <p class="text-xs text-gray-500 mb-2">No. Sertifikat: {{ $participantData->certificate_number }}</p>
                                @endif
                                
                                <!-- Placeholder for certificate download -->
                                <button disabled class="w-full py-2 bg-green-600 text-white rounded-lg font-medium opacity-50 cursor-not-allowed">
                                    <i class="fa-solid fa-award mr-2"></i> Unduh Sertifikat
                                </button>
                                <p class="text-xs text-gray-400 mt-2 italic">*Sertifikat akan tersedia segera</p>
                            </div>
                        @elseif($participantData && $participantData->is_passed === false)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-times-circle text-2xl"></i>
                                </div>
                                <h4 class="font-bold text-red-800 mb-1">Tidak Lulus</h4>
                                <p class="text-sm text-red-700">Maaf, Anda belum memenuhi kriteria kelulusan.</p>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                                <div class="w-12 h-12 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-hourglass-half text-xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-700 mb-1">Menunggu Penilaian</h4>
                                <p class="text-sm text-gray-500">Status kelulusan belum ditentukan.</p>
                            </div>
                        @endif
                        
                        <!-- Evaluations Reminder -->
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <h4 class="font-medium text-gray-900 mb-2">Evaluasi Kegiatan</h4>
                            <p class="text-sm text-gray-600 mb-3">Jangan lupa untuk mengisi form evaluasi kegiatan.</p>
                            <a href="{{ route('my-evaluations.index') }}" class="block w-full py-2 text-center bg-teal-50 text-teal-700 rounded-lg font-medium hover:bg-teal-100 transition">
                                Buka Evaluasi Saya
                            </a>
                        </div>
                    </div>
                @endif
                
                @if($isSpeaker)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Menu Narasumber</h3>
                        <p class="text-sm text-gray-600 mb-3">Sebagai narasumber, Anda dapat melihat sertifikat atau surat tugas Anda (jika tersedia).</p>
                        <!-- Add speaker-specific links if necessary in the future -->
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
