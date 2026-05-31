<div class="space-y-6">
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
