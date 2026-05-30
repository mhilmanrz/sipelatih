<x-layouts.app>
    <x-slot:title>Detail Evaluasi - {{ $activity->activityName->name ?? 'Kegiatan' }}</x-slot>

    <div class="px-8 py-6">
        {{-- BREADCRUMB & HEADER --}}
        <div class="mb-8">
            <a href="{{ route('evaluations.index') }}"
               class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Evaluasi
            </a>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ $activity->activityName->name ?? 'Kegiatan' }}
            </h1>
            <p class="text-gray-600">
                Kode: {{ $activity->reference_number ?? '-' }} · PIC: {{ $activity->user->name ?? '-' }}
            </p>
        </div>

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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT: ACTIVITY INFO --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-700 text-sm uppercase mb-4">Informasi Kegiatan</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Kategori & Format</span>
                            <p class="text-gray-900 font-medium">{{ $activity->activityCategory->name ?? '-' }} ({{ $activity->activityFormat->name ?? '-' }})</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Tanggal</span>
                            <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($activity->end_date)->translatedFormat('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Peserta</span>
                            <p class="text-gray-900 font-medium">{{ count($activity->activityParticipants) }} orang</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Narasumber</span>
                            <p class="text-gray-900 font-medium">{{ $activity->activityMaterials->flatMap(fn($m) => $m->speakers)->unique('id')->count() }} orang</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: EVALUATION SECTIONS --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- LEVEL 1: KEPUASAN PESERTA --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-blue-50 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-sm">1</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">Level 1: Kepuasan Peserta</h3>
                                    <p class="text-xs text-gray-600 mt-0.5">Peserta mengisi form evaluasi narasumber & kegiatan</p>
                                </div>
                            </div>
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Aktif
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4 mb-6">
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-gray-700">
                                    <strong>Status:</strong>
                                    @if (isset($evaluations[1]) && $evaluations[1]->is_passed)
                                        <span class="inline-block ml-2 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Sudah Dinilai</span>
                                    @else
                                        <span class="inline-block ml-2 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Belum Dinilai</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Buat Form Evaluasi Peserta</label>
                                <form action="{{ route('evaluations.generate-forms', $activity->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="evaluation_type" value="1">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                                        Buat Form Level 1
                                    </button>
                                </form>
                                <p class="text-xs text-gray-600 mt-2">
                                    Akan membuat {{ count($activity->activityParticipants) }} × ({{ $activity->activityMaterials->flatMap(fn($m) => $m->speakers)->unique('id')->count() }} narasumber + 1 kegiatan) form
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- LEVEL 2: PRE/POST TEST --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-purple-50 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-sm">2</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">Level 2: Pre/Post Test Scores</h3>
                                    <p class="text-xs text-gray-600 mt-0.5">Tampilan hasil penilaian pre dan post test</p>
                                </div>
                            </div>
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                                @if (isset($evaluations[1]) && $evaluations[1]->is_passed)
                                    bg-green-100 text-green-800
                                @else
                                    bg-gray-100 text-gray-500
                                @endif">
                                {{ isset($evaluations[1]) && $evaluations[1]->is_passed ? 'Terbuka' : 'Terkunci' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        @if (!(isset($evaluations[1]) && $evaluations[1]->is_passed))
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                                <p>Level 2 akan terbuka setelah Level 1 dinilai dan lulus.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                <p class="text-sm text-gray-700 font-medium">Rata-rata Skor Peserta:</p>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-200">
                                                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Peserta</th>
                                                <th class="px-4 py-2 text-center text-gray-700 font-semibold">Pre Test</th>
                                                <th class="px-4 py-2 text-center text-gray-700 font-semibold">Post Test</th>
                                                <th class="px-4 py-2 text-center text-gray-700 font-semibold">Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activity->activityParticipants as $participant)
                                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $participant->user->name }}</td>
                                                    <td class="px-4 py-3 text-center">
                                                        @php
                                                            $preScore = $participant->activityScores->where('score_type', 'pre_test')->first()?->score ?? '-';
                                                        @endphp
                                                        {{ $preScore }}
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @php
                                                            $postScore = $participant->activityScores->where('score_type', 'post_test')->first()?->score ?? '-';
                                                        @endphp
                                                        {{ $postScore }}
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @if ($preScore !== '-' && $postScore !== '-')
                                                            @php
                                                                $progress = $postScore - $preScore;
                                                            @endphp
                                                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                                                @if ($progress > 0)
                                                                    bg-green-100 text-green-800
                                                                @elseif ($progress < 0)
                                                                    bg-red-100 text-red-800
                                                                @else
                                                                    bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ $progress > 0 ? '+' : '' }}{{ $progress }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400 text-xs">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- LEVEL 3: PENILAIAN DAMPAK --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-orange-50 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-600 text-white font-bold text-sm">3</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">Level 3: Penilaian Dampak</h3>
                                    <p class="text-xs text-gray-600 mt-0.5">Peserta mengisi form penilaian dampak & data dukung</p>
                                </div>
                            </div>
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                                @if (isset($evaluations[3]))
                                    bg-green-100 text-green-800
                                @else
                                    bg-gray-100 text-gray-500
                                @endif">
                                {{ isset($evaluations[3]) ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        @if (!(isset($evaluations[2]) && $evaluations[2]->is_passed))
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                                <p>Level 3 akan tersedia setelah Level 2 dinilai dan lulus.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <strong>Status:</strong>
                                        @if (isset($evaluations[3]))
                                            <span class="inline-block ml-2 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Sudah Diaktifkan</span>
                                        @else
                                            <span class="inline-block ml-2 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Belum Diaktifkan</span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    @if (!isset($evaluations[3]))
                                        <form action="{{ route('evaluations.toggle-level3', $activity->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="enable">
                                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition">
                                                Aktifkan Level 3
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('evaluations.toggle-level3', $activity->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menonaktifkan Level 3? Form peserta akan dihapus.');">
                                            @csrf
                                            <input type="hidden" name="action" value="disable">
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                                                Nonaktifkan Level 3
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
