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

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .custom-filter {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>

    <link rel="stylesheet" href="{{ asset('CSS/LayoutPengusul.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/monitoringJpl.css') }}">
@endpush

@section('content')
    <!-- CONTENT -->
    <div class="p-6 space-y-6">

        <!-- TITLE -->
        <x-page-title>MONITORING CAPAIAN JPL</x-page-title>

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

        <!-- TABLE 2 -->
        <div class="bg-white rounded-2xl shadow overflow-x-auto">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="border border-white py-3 px-4 font-semibold">No.</th>
                        <th class="border border-white py-3 px-4 font-semibold">Nama Pegawai</th>
                        <th class="border border-white py-3 px-4 font-semibold">NIP</th>
                        <th class="border border-white py-3 px-4 font-semibold">Unit Kerja</th>
                        <th class="border border-white py-3 px-4 font-semibold">Nama Kegiatan</th>
                        <th class="border border-white py-3 px-4 font-semibold">Waktu Kegiatan</th>
                        <th class="border border-white py-3 px-4 font-semibold">Cakupan Kegiatan</th>
                        <th class="border border-white py-3 px-4 font-semibold">Jabatan</th>
                        <th class="border border-white py-3 px-4 font-semibold">Tenaga</th>
                        <th class="border border-white py-3 px-4 font-semibold">4 Besar</th>
                        <th class="border border-white py-3 px-4 font-semibold">Target</th>
                        <th class="border border-white py-3 px-4 font-semibold">Capaian</th>
                        <th class="border border-white py-3 px-4 font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-50">
                    @forelse($detailedActivities as $index => $participant)
                        <tr class="border-b">
                            <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="text-teal-700 font-medium whitespace-nowrap border border-gray-200 py-3 px-4">
                                {{ $participant->user->name }}</td>
                            <td class="font-mono text-sm border border-gray-200 py-3 px-4">{{ $participant->user->employee_id ?? '-' }}</td>
                            <td class="min-w-[12rem] border border-gray-200 py-3 px-4">{{ $participant->user->workUnit->name ?? '-' }}</td>
                            <td class="font-medium min-w-[12rem] border border-gray-200 py-3 px-4">
                                {{ $participant->activity->activityName->name ?? ($participant->activity->name ?? 'N/A') }}
                            </td>
                            <td class="text-xs min-w-[10rem] border border-gray-200 py-3 px-4">
                                {{ \Carbon\Carbon::parse($participant->activity->start_date)->format('d M') }} -
                                {{ \Carbon\Carbon::parse($participant->activity->end_date)->format('d M Y') }}
                            </td>
                            <td class="border border-gray-200 py-3 px-4">{{ $participant->activity->activityScope->name ?? '-' }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $participant->user->position->name ?? '-' }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $participant->user->profession->name ?? '-' }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $participant->user->employmentType->name ?? '-' }}</td>
                            <td class="text-center font-bold text-gray-600 border border-gray-200 py-3 px-4">{{ $participant->target_jpl }}</td>
                            <td class="text-center font-bold text-teal-600 border border-gray-200 py-3 px-4">
                                {{ number_format($participant->capaian_jpl, 1) }}</td>
                            <td class="text-center border border-gray-200 py-3 px-4">
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
                            <td colspan="13" class="text-center text-gray-500 border border-gray-200 py-3 px-4">
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

        <div class="chart-container">
            <canvas id="kpiChart"></canvas>
        </div>
    </div>
@endsection

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
@endpush
