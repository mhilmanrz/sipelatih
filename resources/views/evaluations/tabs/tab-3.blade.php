<div class="space-y-6">
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Form</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $level3Stats['totalForms'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Sudah Diisi</p>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $level3Stats['submittedForms'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">% Selesai</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">{{ $level3Stats['percentage'] }}%</p>
        </div>
    </div>

    {{-- TABLE --}}
    @if ($level3Stats['totalForms'] === 0)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
            <p class="text-gray-600 text-sm mb-4">Form evaluasi belum digenerate</p>
            <form action="{{ route('evaluations.toggle-level3', $activity->id) }}" method="POST" class="inline" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                <input type="hidden" name="action" value="enable">
                <button type="submit" :disabled="isSubmitting" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 disabled:opacity-50">
                    <span x-text="isSubmitting ? 'Mengaktifkan...' : 'Aktifkan Level 3'"></span>
                </button>
            </form>
        </div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Peserta</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($participantEvaluationsLevel3 as $participantId => $forms)
                        @php
                            $participant = $activity->activityParticipants->firstWhere('id', $participantId);
                            $form = $forms->first();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-900 font-medium">{{ $participant->user->name }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($form->submitted_at)
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Sudah Diisi</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Belum Diisi</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin-participant-evaluations.show', $form->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    @if ($form->submitted_at)
                                        Detail
                                    @else
                                        Isi Form
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500 text-sm">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
