<x-layouts.app>
    <x-slot:title>Indikator Kinerja</x-slot>

    @push('styles')
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
    @endpush

    <div class="p-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold uppercase"
                style="color: white; font-size: 1.5rem; padding: 1rem 2rem; border-radius: 8px; display: inline-block;">
                INDIKATOR KINERJA</h1>
        </div>

        <div class="custom-filter shadow-sm">
            <form action="{{ route('indikator-kinerja.index') }}" method="GET" class="flex items-center gap-4 w-full">
                <div class="flex items-center gap-2">
                    <label for="year" class="font-semibold text-gray-700">Filter Tahun:</label>
                    <select name="year" id="year"
                        class="border-gray-300 rounded-md shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50 px-3 py-2 border">
                        @php
                            $currentYear = date('Y');
                            $startYear = 2020;
                        @endphp
                        @for ($y = $currentYear + 1; $y >= $startYear; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded transition-colors shadow">
                    Tampilkan
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Summary Value 1 -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-teal-500">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Training Effectiveness Index (40 JPL)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-teal-600">{{ $teiPercentage }}%</p>
                        <p class="text-sm text-gray-500 mt-1">Mencapai Target : {{ $numerator1 }} dari {{ $denominator1 }}
                            Pegawai</p>
                    </div>
                    <div class="text-teal-200">
                        <i class="fas fa-stethoscope fa-3x"></i>
                    </div>
                </div>
            </div>

            <!-- Summary Value 2 -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Clinical & Governance (24 JPL)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-extrabold text-blue-600">{{ $cgPercentage }}%</p>
                        <p class="text-sm text-gray-500 mt-1">Mencapai Target : {{ $numerator2 }} dari {{ $denominator2 }}
                            Pegawai</p>
                    </div>
                    <div class="text-blue-200">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-container">
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
    @endpush
</x-layouts.app>
