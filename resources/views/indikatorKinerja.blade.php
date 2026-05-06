<x-layouts.app>
    @section('title', 'Indikator Kinerja')

    <x-page-title>INDIKATOR KINERJA</x-page-title>

    {{-- FILTER --}}
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <form method="GET" action="{{ route('indikator-kinerja.index') }}"
            class="flex flex-wrap items-center justify-between gap-4 p-6 text-white"
            style="background-color:#205252;">
            <div class="flex items-center gap-2">
                <label for="year" class="text-sm font-semibold">Filter Tahun:</label>
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
            <button type="submit"
                class="px-4 py-1.5 text-sm font-bold text-black transition rounded-full bg-[#D6DE20] hover:opacity-90">
                Tampilkan
            </button>
        </form>
    </div>

    {{-- KPI SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Summary Value 1 --}}
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-teal-500">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Training Effectiveness Index (40 JPL)</h3>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-extrabold text-teal-600">{{ $teiPercentage }}%</p>
                    <p class="text-sm text-gray-500 mt-1">Mencapai Target : {{ $numerator1 }} dari {{ $denominator1 }} Pegawai</p>
                </div>
                <div class="text-teal-200">
                    <i class="fas fa-stethoscope fa-3x"></i>
                </div>
            </div>
        </div>

        {{-- Summary Value 2 --}}
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Clinical & Governance (24 JPL)</h3>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-extrabold text-blue-600">{{ $cgPercentage }}%</p>
                    <p class="text-sm text-gray-500 mt-1">Mencapai Target : {{ $numerator2 }} dari {{ $denominator2 }} Pegawai</p>
                </div>
                <div class="text-blue-200">
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="bg-white rounded-lg shadow p-6" style="height: 400px; position: relative;">
        <canvas id="kpiChart"></canvas>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
