<x-layouts.app>
    <x-slot:title>Monitoring Capaian JPL</x-slot>

    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="px-8 py-6">

        {{-- TITLE --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Monitoring Capaian JPL</x-page-title>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('monitoring.jpl.index') }}"
            class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-200">

                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>Tampilkan</span>
                    <select name="entries" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span>data</span>
                </div>

                <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                <select name="year" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition appearance-none pr-8 bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.23%207.21a.75.75%200%20011.06.02L10%2011.168l3.71-3.938a.75.75%200%20111.08%201.04l-4.25%204.5a.75.75%200%2001-1.08%200l-4.25-4.5a.75.75%200%2001.02-1.06z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                    @php
                        $currentYear = date('Y');
                    @endphp
                    @for ($y = $currentYear + 1; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="nip" value="{{ request('nip') }}" placeholder="Cari NIP..."
                        class="bg-gray-50 border border-gray-300 rounded-lg pl-9 pr-4 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition w-40">
                </div>

                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari Nama..."
                        class="bg-gray-50 border border-gray-300 rounded-lg pl-9 pr-4 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition w-40">
                </div>

                <button type="submit"
                    class="bg-[#007a7a] text-white px-4 py-1.5 rounded-lg hover:bg-[#006666] active:bg-[#005555] transition text-sm font-medium">
                    Cari
                </button>

                <a href="{{ route('monitoring.jpl.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-medium transition no-underline">
                    Reset
                </a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Unit Kerja</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Kategori</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">NIP</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama Pegawai</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm w-16">Target</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm w-16">Capaian</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm w-24">Status</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm w-28">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paginatedUsers as $index => $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $paginatedUsers->firstItem() + $index }}</td>
                                <td class="py-3 px-4">{{ $user->workUnit->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $user->category_name }}</td>
                                <td class="font-mono text-sm py-3 px-4">{{ $user->employee_id ?? '-' }}</td>
                                <td class="font-medium text-[#007a7a] py-3 px-4">{{ $user->name }}</td>
                                <td class="text-center font-bold text-gray-600 py-3 px-4">{{ $user->target_jpl }}</td>
                                <td class="text-center font-bold text-[#007a7a] py-3 px-4">{{ number_format($user->capaian_jpl, 1) }}</td>
                                <td class="text-center py-3 px-4">
                                    @if($user->capaian_jpl >= $user->target_jpl)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-green-300">
                                            Tercapai
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-300">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-3 px-4 text-xs text-gray-500">
                                    {{ $user->unique_activities_count }} kegiatan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-6 px-4">
                                    Tidak ada data pegawai yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $paginatedUsers->links('components.pagination') }}
            </div>
        </div>

        {{-- CHART: Capaian JPL per Kategori --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">Capaian JPL per Kategori Profesi Tahun {{ $year }}</h3>
            </div>
            <div class="p-6">
                <canvas id="jplChart"></canvas>
            </div>
        </div>

        {{-- INDIKATOR KINERJA --}}
        <div class="mb-6">
            <h2 class="text-lg font-bold text-white mb-4">Indikator Kinerja Tahun {{ $year }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-[#007a7a]">
                    <div class="p-6">
                        <h3 class="text-base font-bold text-gray-700 mb-2">Training Effectiveness Index (40 JPL)</h3>
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-3xl font-extrabold text-[#007a7a]">{{ $teiPercentage }}%</p>
                                <p class="text-sm text-gray-500 mt-1">Mencapai Target: {{ $numerator1 }} dari {{ $denominator1 }} Pegawai</p>
                            </div>
                            <div class="text-[#007a7a]/20">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-blue-500">
                    <div class="p-6">
                        <h3 class="text-base font-bold text-gray-700 mb-2">Clinical & Governance (24 JPL)</h3>
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-3xl font-extrabold text-blue-600">{{ $cgPercentage }}%</p>
                                <p class="text-sm text-gray-500 mt-1">Mencapai Target: {{ $numerator2 }} dari {{ $denominator2 }} Pegawai</p>
                            </div>
                            <div class="text-blue-200">
                                <i class="fas fa-stethoscope fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI CHART --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">Capaian Indikator Kinerja Pegawai (Tahun {{ $year }})</h3>
            </div>
            <div class="p-6" style="height: 400px;">
                <canvas id="kpiChart"></canvas>
            </div>
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
                                'rgba(0, 122, 122, 0.7)',
                                'rgba(59, 130, 246, 0.7)'
                            ],
                            borderColor: [
                                'rgba(0, 122, 122, 1)',
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
            const jplCtx = document.getElementById('jplChart');
            if (jplCtx) {
                new Chart(jplCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Capaian JPL',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: 'rgba(0, 122, 122, 0.7)',
                            borderColor: 'rgba(0, 122, 122, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'JPL'
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-layouts.app>