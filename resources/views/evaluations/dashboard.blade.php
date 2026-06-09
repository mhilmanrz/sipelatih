<x-layouts.app>
    <x-slot:title>Dashboard Evaluasi</x-slot>

    {{-- Chart.js CDN --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    @endpush

    <div class="px-6 py-6 space-y-6">

        {{-- ── HEADER & FILTER ─────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Evaluasi Pelatihan</h1>
                <p class="text-sm text-gray-500 mt-0.5">RSUPN Dr. Cipto Mangunkusumo</p>
            </div>
            <form method="GET" action="{{ route('evaluations.dashboard') }}" class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Tahun:</label>
                <select name="year" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white">
                    @foreach ($availableYears as $yr)
                        <option value="{{ $yr }}" @selected($yr == $selectedYear)>{{ $yr }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- ── OVERVIEW ─────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

            {{-- Stat Cards --}}
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Total Kegiatan</p>
                    <p class="text-4xl font-bold mt-1">{{ number_format($totalKegiatan) }}</p>
                    <div class="mt-3 flex items-center gap-2 opacity-70">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        <span class="text-xs">Kegiatan disetujui</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Total Peserta</p>
                    <p class="text-4xl font-bold mt-1">{{ number_format($totalPeserta) }}</p>
                    <div class="mt-3 flex items-center gap-2 opacity-70">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        <span class="text-xs">Total peserta kegiatan</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-5 text-white shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Unit Kerja</p>
                    <p class="text-4xl font-bold mt-1">{{ number_format($totalUnitKerja) }}</p>
                    <div class="mt-3 flex items-center gap-2 opacity-70">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                        <span class="text-xs">Unit kerja aktif</span>
                    </div>
                </div>
            </div>

            {{-- Donut: Distribusi Evaluasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-bold text-gray-700 text-sm mb-4">Distribusi Evaluasi</h2>
                <div class="relative h-44">
                    <canvas id="chartEvalDist"></canvas>
                </div>
                <div class="mt-4 space-y-1.5">
                    @php
                        $total = array_sum($evaluationDistribution);
                        $pct = fn ($v) => $total > 0 ? round($v / $total * 100, 1) : 0;
                    @endphp
                    @foreach ([['Level 1', $evaluationDistribution['level1'], '#0d9488'], ['Level 2', $evaluationDistribution['level2'], '#f59e0b'], ['Level 3', $evaluationDistribution['level3'], '#8b5cf6']] as [$label, $val, $color])
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $color }}"></div>
                                <span class="text-gray-600">{{ $label }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $val }} <span class="text-gray-400">({{ $pct($val) }}%)</span></span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bar: Peserta per Bulan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 lg:col-span-2">
                <h2 class="font-bold text-gray-700 text-sm mb-4">Jumlah Peserta per Bulan ({{ $selectedYear }})</h2>
                <div class="relative h-52">
                    <canvas id="chartPesertaBulan"></canvas>
                </div>
            </div>
        </div>

        {{-- ── LEVEL SECTIONS ──────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ── LEVEL 1 ─────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 bg-gradient-to-r from-teal-500 to-teal-600">
                    <h2 class="font-bold text-white text-base">Evaluasi Level 1</h2>
                    <p class="text-teal-100 text-xs">Penyelenggaraan Pelatihan</p>
                </div>
                <div class="p-5 space-y-4">
                    {{-- Mini stats --}}
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ([['Kegiatan', $level1Kegiatan, 'teal'], ['Peserta', $level1Peserta, 'teal'], ['Unit Kerja', $level1UnitKerja, 'teal']] as [$label, $val, $color])
                            <div class="bg-teal-50 rounded-xl p-3 text-center border border-teal-100">
                                <p class="text-xl font-bold text-teal-700">{{ number_format($val) }}</p>
                                <p class="text-xs text-teal-600 mt-0.5">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Response Rate --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-600">Response Rate</span>
                            <span class="text-sm font-bold text-teal-600">
                                {{ $level1Total > 0 ? round($level1Submitted / $level1Total * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-500 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $level1Total > 0 ? round($level1Submitted / $level1Total * 100) : 0 }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1.5 text-xs text-gray-500">
                            <span>{{ number_format($level1Submitted) }} diisi</span>
                            <span>{{ number_format($level1Total) }} total form</span>
                        </div>
                    </div>

                    {{-- Rating per Category --}}
                    @if ($level1CategoryRatings->isNotEmpty())
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-3">Rata-rata Rating per Kategori</p>
                            <div class="relative h-56">
                                <canvas id="chartLevel1Categories"></canvas>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400 text-sm">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Belum ada data evaluasi
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── LEVEL 2 ─────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 bg-gradient-to-r from-amber-500 to-amber-600">
                    <h2 class="font-bold text-white text-base">Evaluasi Level 2</h2>
                    <p class="text-amber-100 text-xs">Hasil Belajar Peserta</p>
                </div>
                <div class="p-5 space-y-4">
                    {{-- Mini stats --}}
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ([['Kegiatan', $level2Kegiatan, 'amber'], ['Peserta', $level2Peserta, 'amber'], ['Unit Kerja', $level2UnitKerja, 'amber']] as [$label, $val, $color])
                            <div class="bg-amber-50 rounded-xl p-3 text-center border border-amber-100">
                                <p class="text-xl font-bold text-amber-700">{{ number_format($val) }}</p>
                                <p class="text-xs text-amber-600 mt-0.5">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Donut: Pass/Fail --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-600 mb-3">Hasil Akhir Peserta</p>
                        <div class="relative h-44">
                            <canvas id="chartLevel2Pass"></canvas>
                        </div>
                        @php
                            $l2Total = array_sum($level2PassDistribution);
                            $l2Pct = fn ($v) => $l2Total > 0 ? round($v / $l2Total * 100, 1) : 0;
                        @endphp
                        <div class="mt-3 grid grid-cols-3 gap-2 text-center">
                            @foreach ([['Lulus', $level2PassDistribution['lulus'], '#10b981'], ['Belum', $level2PassDistribution['belum'], '#94a3b8'], ['Tdk Lulus', $level2PassDistribution['tidak_lulus'], '#ef4444']] as [$label, $val, $color])
                                <div>
                                    <div class="w-2.5 h-2.5 rounded-full mx-auto mb-1" style="background:{{ $color }}"></div>
                                    <p class="text-sm font-bold text-gray-800">{{ number_format($val) }}</p>
                                    <p class="text-xs text-gray-500">{{ $label }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Summary bar --}}
                    @if ($level2Peserta > 0)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-600">Tingkat Kelulusan</span>
                                <span class="text-sm font-bold text-emerald-600">
                                    {{ $level2Peserta > 0 ? round($level2PassDistribution['lulus'] / $level2Peserta * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full"
                                    style="width: {{ $level2Peserta > 0 ? round($level2PassDistribution['lulus'] / $level2Peserta * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-400 text-sm">Belum ada data Level 2</div>
                    @endif
                </div>
            </div>

            {{-- ── LEVEL 3 ─────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 bg-gradient-to-r from-violet-500 to-violet-600">
                    <h2 class="font-bold text-white text-base">Evaluasi Level 3</h2>
                    <p class="text-violet-100 text-xs">Implementasi di Tempat Kerja</p>
                </div>
                <div class="p-5 space-y-4">
                    {{-- Mini stats --}}
                    <div class="grid grid-cols-3 gap-3">
                        @foreach ([['Kegiatan', $level3Kegiatan, 'violet'], ['Peserta', $level3Peserta, 'violet'], ['Unit Kerja', $level3UnitKerja, 'violet']] as [$label, $val, $color])
                            <div class="bg-violet-50 rounded-xl p-3 text-center border border-violet-100">
                                <p class="text-xl font-bold text-violet-700">{{ number_format($val) }}</p>
                                <p class="text-xs text-violet-600 mt-0.5">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Response Rate Level 3 --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-600">Response Rate</span>
                            <span class="text-sm font-bold text-violet-600">
                                {{ $level3Total > 0 ? round($level3Submitted / $level3Total * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-violet-500 h-2 rounded-full"
                                style="width: {{ $level3Total > 0 ? round($level3Submitted / $level3Total * 100) : 0 }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1.5 text-xs text-gray-500">
                            <span>{{ number_format($level3Submitted) }} diisi</span>
                            <span>{{ number_format($level3Total) }} total form</span>
                        </div>
                    </div>

                    {{-- Radar/Bar: Rating per Category --}}
                    @if ($level3CategoryRatings->isNotEmpty())
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-3">Rata-rata Rating per Kategori</p>
                            <div class="relative h-56">
                                <canvas id="chartLevel3Categories"></canvas>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400 text-sm">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Belum ada data evaluasi
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── SHORTCUT LINK ───────────────────────────────────────────────── --}}
        @can('view evaluasi')
        <div class="flex justify-end">
            <a href="{{ route('evaluations.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                Kelola Evaluasi Kegiatan
            </a>
        </div>
        @endcan
    </div>

    @push('scripts')
    <script>
    (function () {
        const COLORS = {
            teal:   '#0d9488',
            amber:  '#f59e0b',
            violet: '#8b5cf6',
            blue:   '#3b82f6',
            rose:   '#f43f5e',
            emerald:'#10b981',
            slate:  '#94a3b8',
        };

        // ── 1. Distribusi Evaluasi ─────────────────────────────────────────
        new Chart(document.getElementById('chartEvalDist'), {
            type: 'doughnut',
            data: {
                labels: ['Level 1', 'Level 2', 'Level 3'],
                datasets: [{
                    data: [
                        {{ $evaluationDistribution['level1'] }},
                        {{ $evaluationDistribution['level2'] }},
                        {{ $evaluationDistribution['level3'] }},
                    ],
                    backgroundColor: [COLORS.teal, COLORS.amber, COLORS.violet],
                    borderWidth: 0,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: { legend: { display: false }, tooltip: { callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.parsed} kegiatan`
                }}},
            },
        });

        // ── 2. Peserta per Bulan ───────────────────────────────────────────
        new Chart(document.getElementById('chartPesertaBulan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($pesertaPerBulanChart->keys()) !!},
                datasets: [{
                    label: 'Jumlah Peserta',
                    data: {!! json_encode($pesertaPerBulanChart->values()) !!},
                    backgroundColor: 'rgba(13, 148, 136, 0.8)',
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y.toLocaleString()} peserta` } },
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: { grid: { color: '#f0f9ff' }, ticks: { font: { size: 11 } } },
                },
            },
        });

        // ── 3. Level 1 – Category Ratings ─────────────────────────────────
        @if ($level1CategoryRatings->isNotEmpty())
        (function () {
            const labels = {!! json_encode($level1CategoryRatings->pluck('name')) !!};
            const data   = {!! json_encode($level1CategoryRatings->pluck('avg')) !!};
            const palette = ['#0d9488','#0891b2','#6366f1','#8b5cf6','#a855f7','#ec4899','#f43f5e'];
            new Chart(document.getElementById('chartLevel1Categories'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Rata-rata Rating',
                        data,
                        backgroundColor: labels.map((_, i) => palette[i % palette.length] + 'cc'),
                        borderRadius: 5,
                        borderSkipped: false,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.x.toFixed(2)} / 4.00` } },
                    },
                    scales: {
                        x: {
                            min: 0, max: 4,
                            grid: { color: '#f0f9ff' },
                            ticks: { font: { size: 10 } },
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9 },
                                callback: function(val) {
                                    const label = this.getLabelForValue(val);
                                    return label.length > 20 ? label.substring(0, 20) + '…' : label;
                                }
                            },
                        },
                    },
                },
            });
        })();
        @endif

        // ── 4. Level 2 – Pass/Fail Donut ──────────────────────────────────
        new Chart(document.getElementById('chartLevel2Pass'), {
            type: 'doughnut',
            data: {
                labels: ['Lulus', 'Belum Dinilai', 'Tidak Lulus'],
                datasets: [{
                    data: [
                        {{ $level2PassDistribution['lulus'] }},
                        {{ $level2PassDistribution['belum'] }},
                        {{ $level2PassDistribution['tidak_lulus'] }},
                    ],
                    backgroundColor: [COLORS.emerald, COLORS.slate, COLORS.rose],
                    borderWidth: 0,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} peserta` } },
                },
            },
        });

        // ── 5. Level 3 – Category Ratings ─────────────────────────────────
        @if ($level3CategoryRatings->isNotEmpty())
        (function () {
            const labels = {!! json_encode($level3CategoryRatings->pluck('name')) !!};
            const data   = {!! json_encode($level3CategoryRatings->pluck('avg')) !!};
            const palette = ['#7c3aed','#2563eb','#0891b2','#059669','#d97706','#dc2626'];
            new Chart(document.getElementById('chartLevel3Categories'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Rata-rata Rating',
                        data,
                        backgroundColor: labels.map((_, i) => palette[i % palette.length] + 'cc'),
                        borderRadius: 5,
                        borderSkipped: false,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.x.toFixed(2)} / 4.00` } },
                    },
                    scales: {
                        x: {
                            min: 0, max: 4,
                            grid: { color: '#f5f3ff' },
                            ticks: { font: { size: 10 } },
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9 },
                                callback: function(val) {
                                    const label = this.getLabelForValue(val);
                                    return label.length > 20 ? label.substring(0, 20) + '…' : label;
                                }
                            },
                        },
                    },
                },
            });
        })();
        @endif
    })();
    </script>
    @endpush
</x-layouts.app>
