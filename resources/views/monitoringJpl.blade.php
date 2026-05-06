<x-layouts.app>
    <x-slot:title>Monitoring Capaian JPL</x-slot>

    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <!-- CONTENT -->
    <div class="p-6 space-y-6">

        <!-- TITLE -->
        <x-page-title>MONITORING CAPAIAN JPL</x-page-title>

        <!-- TABLE 2 -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <x-table-toolbar actionUrl="{{ route('monitoring.jpl.index') }}" searchPlaceholder="Cari NIP/Nama Pegawai...">
                <div class="flex items-center gap-2">
                    <label for="year" class="text-sm text-white whitespace-nowrap">Tahun:</label>
                    <select name="year" id="year" onchange="this.form.submit()"
                        class="bg-transparent border border-white text-white rounded-full px-3 py-1.5 outline-none text-sm">
                        @php
                            $currentYear = date('Y');
                            $startYear = 2020;
                        @endphp
                        @for ($y = $currentYear + 1; $y >= $startYear; $y--)
                            <option value="{{ $y }}" class="text-black" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <a href="{{ route('monitoring.jpl.index') }}"
                    class="px-4 py-1.5 text-sm font-bold text-white border border-white rounded-full transition hover:bg-white hover:text-[#205252]"
                    style="text-decoration: none;">
                    Reset
                </a>
            </x-table-toolbar>

            <x-table>
                <x-slot name="header">
                    <tr>
                        <th class="px-4 py-3 text-center">No.</th>
                        <th class="px-4 py-3">Nama Pegawai</th>
                        <th class="px-4 py-3">NIP</th>
                        <th class="px-4 py-3">Unit Kerja</th>
                        <th class="px-4 py-3">Nama Kegiatan</th>
                        <th class="px-4 py-3">Waktu Kegiatan</th>
                        <th class="px-4 py-3">Cakupan Kegiatan</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Tenaga</th>
                        <th class="px-4 py-3">4 Besar</th>
                        <th class="px-4 py-3 text-center">Target</th>
                        <th class="px-4 py-3 text-center">Capaian</th>
                        <th class="px-4 py-3 text-center">Keterangan</th>
                    </tr>
                </x-slot>

                @forelse($detailedActivitiesPaginated as $index => $participant)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="text-center px-4 py-3">{{ $detailedActivitiesPaginated->firstItem() + $index }}</td>
                        <td class="text-teal-700 font-medium whitespace-nowrap px-4 py-3">
                            {{ $participant->user->name }}</td>
                        <td class="font-mono text-sm px-4 py-3">{{ $participant->user->employee_id ?? '-' }}</td>
                        <td class="min-w-[12rem] px-4 py-3">{{ $participant->user->workUnit->name ?? '-' }}</td>
                        <td class="font-medium min-w-[12rem] px-4 py-3">
                            {{ $participant->activity->activityName->name ?? ($participant->activity->name ?? 'N/A') }}
                        </td>
                        <td class="text-xs min-w-[10rem] px-4 py-3">
                            {{ \Carbon\Carbon::parse($participant->activity->start_date)->format('d M') }} -
                            {{ \Carbon\Carbon::parse($participant->activity->end_date)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">{{ $participant->activity->activityScope->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $participant->user->position->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $participant->user->profession->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $participant->user->employmentType->name ?? '-' }}</td>
                        <td class="text-center font-bold text-gray-600 px-4 py-3">{{ $participant->target_jpl }}</td>
                        <td class="text-center font-bold text-teal-600 px-4 py-3">
                            {{ number_format($participant->capaian_jpl, 1) }}</td>
                        <td class="text-center px-4 py-3">
                            @if ($participant->capaian_jpl >= $participant->target_jpl)
                                <span class="bg-green-500 text-white px-4 py-1 rounded-full text-xs">
                                    Tercapai
                                </span>
                            @else
                                <span class="bg-red-500 text-white px-4 py-1 rounded-full text-xs">
                                    Belum Tercapai
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center text-gray-500 px-4 py-8">
                            Tidak ada data histori detail partisipan yang telah lulus.
                        </td>
                    </tr>
                @endforelse
            </x-table>

            <x-table-footer :paginator="$detailedActivitiesPaginated->appends(request()->query())" />
        </div>

        <!-- CHART -->
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Capaian JPL per Kategori Profesi Tahun {{ $year }}
            </h3>
            <canvas id="jplChart"></canvas>
        </div>

        <!-- INDIKATOR KINERJA -->
        <h2 class="text-2xl font-bold text-[#007A7F] text-left">
            INDIKATOR KINERJA TAHUN {{ $year }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Summary Value 1 -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-teal-500">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Training Effectiveness Index (40 JPL)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-teal-600">{{ $teiPercentage }}%</p>
                        <p class="text-sm text-gray-500 mt-1">Mencapai Target : {{ $numerator1 }} dari
                            {{ $denominator1 }} Pegawai</p>
                    </div>
                    <div class="text-teal-200">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>

            <!-- Summary Value 2 -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Clinical & Governance (24 JPL)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-blue-600">{{ $cgPercentage }}%</p>
                        <p class="text-sm text-gray-500 mt-1">Mencapai Target : {{ $numerator2 }} dari
                            {{ $denominator2 }} Pegawai</p>
                    </div>
                    <div class="text-blue-200">
                        <i class="fas fa-stethoscope fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative bg-white rounded-lg shadow p-4" style="height: 400px;">
            <canvas id="kpiChart"></canvas>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('kpiChart').getContext('2d');

                const teiValue = {{ $teiPercentage }};
                const cgValue = {{ $cgPercentage }};

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Training Effectiveness Index', 'Clinical & Governance'],
                        datasets: [{
                            label: 'Persentase Capaian (%)',
                            data: [teiValue, cgValue],
                            backgroundColor: [
                                'rgba(20, 184, 166, 0.7)', // Teal
                                'rgba(59, 130, 246, 0.7)' // Blue
                            ],
                            borderColor: [
                                'rgba(20, 184, 166, 1)',
                                'rgba(59, 130, 246, 1)'
                            ],
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Persentase (%)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Capaian Indikator Kinerja Pegawai (Tahun {{ $year }})',
                                font: {
                                    size: 16
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + '% Capaian Target';
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
        <script>
            const ctx = document.getElementById('jplChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Capaian JPL',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: '#11b9c6'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-layouts.app>
