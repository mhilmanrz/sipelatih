<div class="space-y-6">
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Form</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $level1Stats['totalForms'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Sudah Diisi</p>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $level1Stats['submittedForms'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Belum Diisi</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">{{ $level1Stats['pendingForms'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">% Selesai</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $level1Stats['percentage'] }}%</p>
        </div>
    </div>

    {{-- TABLE --}}
    @if ($level1Stats['totalForms'] === 0)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
            <p class="text-gray-600 text-sm mb-4">Form evaluasi belum digenerate</p>
            <form action="{{ route('evaluations.generate-forms', $activity->id) }}" method="POST" class="inline" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                <input type="hidden" name="evaluation_type" value="1">
                <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                    <span x-text="isSubmitting ? 'Membuat Form...' : 'Buat Form Level 1'"></span>
                </button>
            </form>
        </div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jenis Form</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Narasumber</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($participantEvaluationsLevel1 as $participantId => $forms)
                        @php
                            $participant = $activity->activityParticipants->firstWhere('id', $participantId);
                        @endphp
                        @foreach ($forms as $form)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $participant->user->name }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    @if ($form->form_type === 'speaker')
                                        Narasumber
                                    @else
                                        Kegiatan
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    @if ($form->speaker)
                                        {{ $form->speaker->user->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($form->submitted_at)
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Sudah Diisi</span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Belum Diisi</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($form->submitted_at)
                                        <a href="{{ route('admin-participant-evaluations.show', $form->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Detail</a>
                                    @else
                                        <a href="{{ route('public-evaluations.show', $form->token) }}" target="_blank" class="text-green-600 hover:text-green-700 text-sm font-medium">Isi Form</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-sm">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
