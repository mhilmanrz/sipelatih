<x-layouts.app>
    <x-slot:title>Evaluasi - {{ $activity->activityName->name ?? 'Kegiatan' }}</x-slot>

    <div class="px-8 py-6">
        {{-- BREADCRUMB --}}
        <a href="{{ route('evaluations.index') }}"
           class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Evaluasi
        </a>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-red-700 text-sm font-medium">{{ $errors->first('error') }}</p>
            </div>
        @endif

        {{-- SECTION ATAS: ACTIVITY INFO & SUMMARY --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $activity->activityName->name ?? 'Kegiatan' }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Kode: {{ $activity->reference_number ?? '-' }} ·
                        {{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('d M Y') }} -
                        {{ \Carbon\Carbon::parse($activity->end_date)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Kategori & Format</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $activity->activityCategory->name ?? '-' }} / {{ $activity->activityFormat->name ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Peserta</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $activity->activityParticipants->count() }} orang</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Narasumber</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $activity->activityMaterials->flatMap(fn($m) => $m->speakers)->unique('id')->count() }} orang</p>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase">Status Evaluasi</span>
                    <div class="mt-1 flex flex-wrap gap-2">
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                            @if (isset($evaluations[1]) && $evaluations[1]->is_passed)
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            L1 {{ isset($evaluations[1]) && $evaluations[1]->is_passed ? '✓' : '-' }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                            @if (isset($evaluations[2]) && $evaluations[2]->is_passed)
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            L2 {{ isset($evaluations[2]) && $evaluations[2]->is_passed ? '✓' : '-' }}
                        </span>
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                            @if (isset($evaluations[3]))
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-600
                            @endif">
                            L3 {{ isset($evaluations[3]) ? '✓' : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION BAWAH: EVALUATION TABS --}}
        @php
            $activeTab = (int) request()->query('tab', 1);
            if (! in_array($activeTab, [1, 2, 3])) {
                $activeTab = 1;
            }
        @endphp
        <div x-data="{ activeTab: {{ $activeTab }} }" class="space-y-6">
            {{-- TAB NAVIGATION --}}
            <div class="border-b border-gray-200">
                <div class="flex gap-8">
                    <button @click="activeTab = 1; window.history.pushState({}, '', '{{ route('evaluations.show', $activity->id) }}?tab=1')"
                            :class="activeTab === 1 ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900'"
                            class="py-4 font-medium text-sm transition">
                        Tab 1: Kepuasan Peserta
                    </button>
                    <button @click="activeTab = 2; window.history.pushState({}, '', '{{ route('evaluations.show', $activity->id) }}?tab=2')"
                            :class="activeTab === 2 ? 'border-b-2 border-purple-600 text-purple-600' : 'text-gray-600 hover:text-gray-900'"
                            class="py-4 font-medium text-sm transition">
                        Tab 2: Pre/Post Test
                    </button>
                    <button @click="activeTab = 3; window.history.pushState({}, '', '{{ route('evaluations.show', $activity->id) }}?tab=3')"
                            :class="activeTab === 3 ? 'border-b-2 border-orange-600 text-orange-600' : 'text-gray-600 hover:text-gray-900'"
                            class="py-4 font-medium text-sm transition">
                        Tab 3: Evaluasi Dampak
                    </button>
                </div>
            </div>

            {{-- TAB 1: KEPUASAN PESERTA --}}
            <div x-show="activeTab === 1" class="space-y-6">
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

            {{-- TAB 2: PRE/POST TEST --}}
            <div x-show="activeTab === 2" class="space-y-6">
                {{-- STATS CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase">Rata-rata Pre Test</p>
                        <p class="text-2xl font-bold text-blue-600 mt-2">{{ $level2Stats['avgPre'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase">Rata-rata Post Test</p>
                        <p class="text-2xl font-bold text-purple-600 mt-2">{{ $level2Stats['avgPost'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase">Rata-rata Peningkatan</p>
                        <p class="text-2xl font-bold {{ $level2Stats['avgDelta'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                            {{ $level2Stats['avgDelta'] >= 0 ? '+' : '' }}{{ $level2Stats['avgDelta'] }}
                        </p>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Peserta</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Pre Test</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Post Test</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($activity->activityParticipants as $participant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $participant->user->name }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $preScore = $participant->score?->pre_test_score ?? '-';
                                        @endphp
                                        {{ $preScore }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $postScore = $participant->score?->post_test_score ?? '-';
                                        @endphp
                                        {{ $postScore }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($preScore !== '-' && $postScore !== '-')
                                            @php
                                                $delta = $postScore - $preScore;
                                            @endphp
                                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                                @if ($delta > 0)
                                                    bg-green-100 text-green-800
                                                @elseif ($delta < 0)
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $delta > 0 ? '+' : '' }}{{ $delta }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">Tidak ada peserta</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 3: EVALUASI DAMPAK --}}
            <div x-show="activeTab === 3" class="space-y-6">
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
                                            @if ($form->submitted_at)
                                                <a href="{{ route('admin-participant-evaluations.show', $form->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Detail</a>
                                            @else
                                                <a href="{{ route('public-evaluations.show', $form->token) }}" target="_blank" class="text-green-600 hover:text-green-700 text-sm font-medium">Isi Form</a>
                                            @endif
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
        </div>
    </div>
</x-layouts.app>
