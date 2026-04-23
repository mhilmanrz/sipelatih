@extends('layout.LayoutSuperAdmin')

@section('title', 'Monitoring Capaian JPL')

@push('styles')
    <!-- Tailwind via CDN for this specific page as requested by user's raw HTML -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0DBBCB'
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('CSS/LayoutPengusul.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/monitoringJpl.css') }}">
@endpush

@section('content')
    <!-- CONTENT -->
    <div class="p-6 space-y-6">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-[#007A7F] text-left">
            MONITORING CAPAIAN JPL
        </h2>

        <!-- FILTER -->
        <form action="{{ route('monitoring.jpl.index') }}" method="GET"
            class="bg-white p-4 rounded shadow flex flex-col md:flex-row gap-4 md:items-center flex-wrap">
            <div class="flex items-center gap-2">
                <label for="year" class="font-semibold text-gray-700 whitespace-nowrap">Tahun:</label>
                <select name="year" id="year"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-primary/40">
                    @php
                        $currentYear = date('Y');
                        $startYear = 2020;
                    @endphp
                    @for ($y = $currentYear + 1; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <input type="text" name="nip" value="{{ request('nip') }}" placeholder="Cari NIP Pegawai"
                class="border rounded px-4 py-2 w-full md:w-64 focus:outline-none focus:ring focus:ring-primary/40">
            <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari Nama Pegawai"
                class="border rounded px-4 py-2 w-full md:w-64 focus:outline-none focus:ring focus:ring-primary/40">
            <button type="submit" class="bg-[#007A7F] hover:bg-teal-700 text-white px-6 py-2 rounded w-full md:w-auto">
                Cari
            </button>
            <a href="{{ route('monitoring.jpl.index') }}"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded w-full md:w-auto text-center"
                style="text-decoration: none;">
                Reset
            </a>
        </form>

        <!-- TABLE 1 -->
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#007A7F] text-white">
                    <tr>
                        <th class="px-3 py-2">No</th>
                        <th class="px-3 py-2">Nama Pegawai</th>
                        <th class="px-3 py-2">NIP</th>
                        <th class="px-3 py-2">Unit Kerja</th>
                        <th class="px-3 py-2">Jumlah Kegiatan</th>
                        <th class="px-3 py-2">Target</th>
                        <th class="px-3 py-2">Capaian</th>
                        <th class="px-3 py-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 text-teal-700 font-medium">{{ $user->name }}</td>
                            <td class="px-3 py-2 font-mono text-sm">{{ $user->employee_id ?? '-' }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ $user->workUnit->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-center font-bold text-gray-700">
                                {{ $user->unique_activities_count }}</td>
                            <td class="px-3 py-2 text-center font-bold text-gray-700">{{ $user->target_jpl }} JPL</td>
                            <td
                                class="px-3 py-2 text-center font-bold {{ $user->capaian_jpl >= $user->target_jpl ? 'text-green-600' : 'text-orange-500' }}">
                                {{ $user->capaian_jpl }} JPL
                            </td>
                            <td class="px-3 py-2 text-center">
                                @if ($user->capaian_jpl >= $user->target_jpl)
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                                        Tercapai
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">
                                        Belum Tercapai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-6 text-center text-gray-500">
                                Tidak ada data pegawai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABLE 2 -->
        <div class="bg-white rounded-2xl shadow overflow-x-auto">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-teal-700 text-white">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Nama Pegawai</th>
                        <th class="px-4 py-3">NIP</th>
                        <th class="px-4 py-3">Unit Kerja</th>
                        <th class="px-4 py-3">Nama Kegiatan</th>
                        <th class="px-4 py-3">Waktu Kegiatan</th>
                        <th class="px-4 py-3">Cakupan Kegiatan</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Tenaga</th>
                        <th class="px-4 py-3">4 Besar</th>
                        <th class="px-4 py-3">Target</th>
                        <th class="px-4 py-3">Capaian</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-50">
                    @forelse($detailedActivities as $index => $participant)
                        <tr class="border-b">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-teal-700 font-medium whitespace-nowrap">
                                {{ $participant->user->name }}</td>
                            <td class="px-4 py-3 font-mono text-sm">{{ $participant->user->employee_id ?? '-' }}</td>
                            <td class="px-4 py-3 min-w-[12rem]">{{ $participant->user->workUnit->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium min-w-[12rem]">
                                {{ $participant->activity->activityName->name ?? ($participant->activity->name ?? 'N/A') }}
                            </td>
                            <td class="px-4 py-3 text-xs min-w-[10rem]">
                                {{ \Carbon\Carbon::parse($participant->activity->start_date)->format('d M') }} -
                                {{ \Carbon\Carbon::parse($participant->activity->end_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">{{ $participant->activity->activityScope->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $participant->user->position->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $participant->user->profession->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $participant->user->employmentType->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center font-bold text-gray-600">{{ $participant->target_jpl }}</td>
                            <td class="px-4 py-3 text-center font-bold text-teal-600">
                                {{ number_format($participant->capaian_jpl, 1) }}</td>
                            <td class="px-4 py-3 text-center">
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
                            <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data histori detail partisipan yang telah lulus.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- CHART -->
        <div class="bg-white rounded shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Grafik Capaian JPL Tahun {{ $year }}
            </h3>
            <canvas id="jplChart"></canvas>
        </div>

        <!-- INDIKATOR KINERJA -->
        <h2 class="text-2xl font-bold text-[#007A7F] text-left mt-4">
            INDIKATOR KINERJA TAHUN {{ $year }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- TEI Card -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-teal-500">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Training Effectiveness Index (40 JPL)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-teal-600">{{ $teiPercentage }}%</p>
                        <p class="text-sm text-gray-500 mt-1">Mencapai Target: {{ $numerator1 }} dari {{ $denominator1 }} Pegawai</p>
                    </div>
                    <div class="text-teal-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4 6v-2m0 0a4 4 0 10-8 0m8 0a4 4 0 108 0" />
                        </svg>
                    </div>
                </div>
                <!-- Progress bar -->
                <div class="mt-4 bg-gray-200 rounded-full h-3">
                    <div class="bg-teal-500 h-3 rounded-full transition-all" style="width: {{ min($teiPercentage, 100) }}%"></div>
                </div>
            </div>

            <!-- CG Card -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Clinical &amp; Governance (24 JPL)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-blue-600">{{ $cgPercentage }}%</p>
                        <p class="text-sm text-gray-500 mt-1">Mencapai Target: {{ $numerator2 }} dari {{ $denominator2 }} Pegawai</p>
                    </div>
                    <div class="text-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <!-- Progress bar -->
                <div class="mt-4 bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-500 h-3 rounded-full transition-all" style="width: {{ min($cgPercentage, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- CHART INDIKATOR KINERJA -->
        <div class="bg-white rounded shadow p-6">
            <div style="position: relative; height: 350px; width: 100%;">
                <canvas id="kpiChart"></canvas>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <script src="{{ asset('JS/LayoutPengusul.js') }}"></script>
    <script src="{{ asset('JS/monitoringJpl.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kpiCtx = document.getElementById('kpiChart').getContext('2d');
            new Chart(kpiCtx, {
                type: 'bar',
                data: {
                    labels: ['Training Effectiveness Index (40 JPL)', 'Clinical & Governance (24 JPL)'],
                    datasets: [{
                        label: 'Persentase Capaian (%)',
                        data: [{{ $teiPercentage }}, {{ $cgPercentage }}],
                        backgroundColor: [
                            'rgba(20, 184, 166, 0.7)',
                            'rgba(59, 130, 246, 0.7)'
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
                            title: { display: true, text: 'Persentase (%)' }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Capaian Indikator Kinerja Pegawai (Tahun {{ $year }})',
                            font: { size: 16 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) { return context.raw + '% Capaian Target'; }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
