<x-layouts.app>
    <x-slot:title>Dashboard Evaluasi Peserta</x-slot>

    <div class="px-8 py-6 space-y-6 bg-gray-50/50 min-h-screen">

        {{-- ── TITLE & HEADER ─────────────────────────────────────────────────── --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <x-page-title>Dashboard Evaluasi Pelatihan (Peserta)</x-page-title>
                <p class="text-sm text-gray-500 mt-1">RSUPN Dr. Cipto Mangunkusumo - Monitoring detail evaluasi peserta tingkat 1, 2, dan 3</p>
            </div>
            
            {{-- Shortcut back to overview --}}
            <div>
                <a href="{{ route('evaluations.dashboard') }}"
                    class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 active:bg-gray-100 transition shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    Overview Dashboard
                </a>
            </div>
        </div>

        {{-- ── FILTER PANEL ───────────────────────────────────────────────────── --}}
        <form method="GET" action="{{ route('evaluations.participant-dashboard') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                <svg class="w-4.5 h-4.5 text-[#007a7a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h3 class="text-sm font-bold text-gray-700">Filter Pencarian</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Month Filter --}}
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-600">Bulan Kegiatan</label>
                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#007a7a]/20 focus:border-[#007a7a] bg-white text-gray-700 outline-none transition">
                        <option value="">Semua Bulan</option>
                        @foreach ($monthsList as $num => $name)
                            <option value="{{ $num }}" @selected($num == $selectedMonth)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Scope Filter --}}
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-600">Cakupan Kegiatan</label>
                    <select name="activity_scope_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#007a7a]/20 focus:border-[#007a7a] bg-white text-gray-700 outline-none transition">
                        <option value="">Semua Cakupan</option>
                        @foreach ($scopesList as $scope)
                            <option value="{{ $scope->id }}" @selected($scope->id == $selectedScope)>{{ $scope->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Work Unit Filter --}}
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-600">Unit Kerja Peserta</label>
                    <select name="work_unit_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#007a7a]/20 focus:border-[#007a7a] bg-white text-gray-700 outline-none transition">
                        <option value="">Semua Unit Kerja</option>
                        @foreach ($workUnitsList as $unit)
                            <option value="{{ $unit->id }}" @selected($unit->id == $selectedWorkUnit)>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Activity Title Filter --}}
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-600">Judul Pelatihan</label>
                    <select name="activity_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#007a7a]/20 focus:border-[#007a7a] bg-white text-gray-700 outline-none transition">
                        <option value="">Semua Pelatihan</option>
                        @foreach ($activitiesList as $act)
                            <option value="{{ $act->id }}" @selected($act->id == $selectedActivity)>{{ $act->activityName->name ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Employee Name Search --}}
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-600">Nama Pegawai / NIP</label>
                    <input type="text" name="employee_name" value="{{ $searchEmployee }}" placeholder="Cari nama pegawai..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#007a7a]/20 focus:border-[#007a7a] bg-white text-gray-700 outline-none transition" />
                </div>
            </div>

            <div class="flex justify-end items-center gap-2 pt-2">
                <a href="{{ route('evaluations.participant-dashboard') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 active:bg-gray-100 transition">
                    Reset
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-[#007a7a] text-white rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>

        {{-- ── METRIC CARDS ───────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Total Kegiatan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition duration-300 group">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kegiatan</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ number_format($totalKegiatan) }}</p>
                    <p class="text-xs text-gray-500">Pelatihan yang dinilai</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-[#007a7a] group-hover:scale-110 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>

            {{-- Total Peserta --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition duration-300 group">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Peserta</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ number_format($totalPeserta) }}</p>
                    <p class="text-xs text-gray-500">Peserta pelatihan terdaftar</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            {{-- Total Unit Kerja --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition duration-300 group">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unit Kerja</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ number_format($totalUnitKerja) }}</p>
                    <p class="text-xs text-gray-500">Unit kerja aktif terlibat</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 group-hover:scale-110 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>

            {{-- Average Response Rate --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition duration-300 group">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Response Rate</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $responseRate }}%</p>
                    <p class="text-xs text-gray-500">Form evaluasi terkumpul</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── MAIN CONTENT (TABS) ──────────────────────────────────────────────── --}}
        <div x-data="{ activeTab: '{{ request('tab', 'kegiatan') }}', switchTab(tab) { this.activeTab = tab; const url = new URL(window.location); url.searchParams.set('tab', tab); window.history.pushState({}, '', url); } }" 
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            
            {{-- Tabs navigation --}}
            <div class="flex flex-wrap border-b border-gray-200 bg-gray-50/50 px-4 pt-3">
                {{-- Tab 1: Data Pelatihan --}}
                <button @click="switchTab('kegiatan')"
                    :class="activeTab === 'kegiatan' ? 'border-[#007a7a] text-[#007a7a] font-bold bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-transparent'"
                    class="flex items-center gap-2 px-5 py-3 border-b-2 text-sm font-semibold rounded-t-xl transition duration-150 outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                    Data Pelatihan
                </button>

                {{-- Tab 2: Level 1 --}}
                <button @click="switchTab('level1')"
                    :class="activeTab === 'level1' ? 'border-[#007a7a] text-[#007a7a] font-bold bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-transparent'"
                    class="flex items-center gap-2 px-5 py-3 border-b-2 text-sm font-semibold rounded-t-xl transition duration-150 outline-none">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-[#007a7a]/10 text-[#007a7a]">1</span>
                    Evaluasi Level 1
                </button>

                {{-- Tab 3: Level 2 --}}
                <button @click="switchTab('level2')"
                    :class="activeTab === 'level2' ? 'border-[#007a7a] text-[#007a7a] font-bold bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-transparent'"
                    class="flex items-center gap-2 px-5 py-3 border-b-2 text-sm font-semibold rounded-t-xl transition duration-150 outline-none">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600">2</span>
                    Evaluasi Level 2
                </button>

                {{-- Tab 4: Level 3 --}}
                <button @click="switchTab('level3')"
                    :class="activeTab === 'level3' ? 'border-[#007a7a] text-[#007a7a] font-bold bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 bg-transparent'"
                    class="flex items-center gap-2 px-5 py-3 border-b-2 text-sm font-semibold rounded-t-xl transition duration-150 outline-none">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-violet-500/10 text-violet-600">3</span>
                    Evaluasi Level 3
                </button>
            </div>

            {{-- 📑 TAB 1: DATA PELATIHAN --}}
            <div x-show="activeTab === 'kegiatan'" class="overflow-x-auto">
                <table class="w-full text-sm border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="w-16 py-3 px-5 text-center">No.</th>
                            <th class="py-3 px-5">Nama Pegawai</th>
                            <th class="py-3 px-5">Unit Kerja</th>
                            <th class="py-3 px-5">Judul Kegiatan Pelatihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mappedParticipants as $index => $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-5 text-center text-gray-400 font-medium">{{ $participants->firstItem() + $index }}</td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800">{{ $row->name }}</td>
                                <td class="py-3.5 px-5 text-gray-600">{{ $row->workUnit }}</td>
                                <td class="py-3.5 px-5 text-gray-700 font-medium">{{ $row->activityTitle }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-400 bg-gray-50/20">Belum ada data pelatihan peserta.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 📑 TAB 2: EVALUASI LEVEL 1 --}}
            <div x-show="activeTab === 'level1'" class="overflow-x-auto" style="display: none;">
                <table class="w-full text-sm border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="w-16 py-3 px-5 text-center">No.</th>
                            <th class="py-3 px-5 min-w-[150px]">Nama Pegawai</th>
                            <th class="py-3 px-5 min-w-[200px]">Judul Pelatihan</th>
                            <th class="py-3 px-5 text-center">Pelayanan Administrasi</th>
                            <th class="py-3 px-5 text-center">Sarana & Fasilitas</th>
                            <th class="py-3 px-5 text-center">Metode Pembelajaran</th>
                            <th class="py-3 px-5 text-center">Kepuasan Program</th>
                            <th class="py-3 px-5 text-center">Narasumber / Pelatih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mappedParticipants as $index => $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-5 text-center text-gray-400 font-medium">{{ $participants->firstItem() + $index }}</td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800">{{ $row->name }}</td>
                                <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->activityTitle }}</td>
                                
                                {{-- Kategori 1: Pelayanan Administrasi --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level1->cat1 === 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">Belum</span>
                                    @endif
                                </td>

                                {{-- Kategori 2: Sarana dan Fasilitas --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level1->cat2 === 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">Belum</span>
                                    @endif
                                </td>

                                {{-- Kategori 3: Metode & Pembelajaran --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level1->cat3 === 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">Belum</span>
                                    @endif
                                </td>

                                {{-- Kategori 4: Kepuasan Program --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level1->cat4 === 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">Belum</span>
                                    @endif
                                </td>

                                {{-- Narasumber --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level1->narasumber === 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Sudah</span>
                                    @elseif (str_starts_with($row->level1->narasumber, 'Belum'))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200" title="Ada narasumber yang belum dinilai">{{ $row->level1->narasumber }}</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-100">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-400 bg-gray-50/20">Belum ada data evaluasi level 1.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 📑 TAB 3: EVALUASI LEVEL 2 --}}
            <div x-show="activeTab === 'level2'" class="overflow-x-auto" style="display: none;">
                <table class="w-full text-sm border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="w-16 py-3 px-5 text-center">No.</th>
                            <th class="py-3 px-5">Nama Pegawai</th>
                            <th class="py-3 px-5">Judul Pelatihan</th>
                            <th class="py-3 px-5 text-center">Nilai Akhir</th>
                            <th class="py-3 px-5 text-center">Hasil Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mappedParticipants as $index => $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-5 text-center text-gray-400 font-medium">{{ $participants->firstItem() + $index }}</td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800">{{ $row->name }}</td>
                                <td class="py-3.5 px-5 text-gray-600 font-medium">{{ $row->activityTitle }}</td>
                                
                                {{-- Nilai Akhir --}}
                                <td class="py-3.5 px-5 text-center font-bold text-gray-700">
                                    {{ $row->level2->score !== null ? number_format($row->level2->score, 2) : '-' }}
                                </td>

                                {{-- Hasil Akhir badge --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level2->status === 'Lulus')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">LULUS</span>
                                    @elseif ($row->level2->status === 'Tidak Lulus')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">TIDAK LULUS</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">BELUM DINILAI</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-400 bg-gray-50/20">Belum ada data evaluasi level 2.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 📑 TAB 4: EVALUASI LEVEL 3 --}}
            <div x-show="activeTab === 'level3'" class="overflow-x-auto" style="display: none;">
                <table class="w-full text-sm border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="w-16 py-3 px-5 text-center">No.</th>
                            <th class="py-3 px-5 min-w-[150px]">Nama Pegawai</th>
                            <th class="py-3 px-5 min-w-[200px]">Judul Pelatihan</th>
                            <th class="py-3 px-5 text-center">Target (L3-C5)</th>
                            <th class="py-3 px-5 text-center">Kompetensi (L3-C6)</th>
                            <th class="py-3 px-5 text-center">Patient Safety (L3-C7)</th>
                            <th class="py-3 px-5 text-center">Transfer (L3-C8)</th>
                            <th class="py-3 px-5 text-center">Efektivitas (L3-C9)</th>
                            <th class="py-3 px-5 text-center">Data Dukung (L3-C10)</th>
                            <th class="py-3 px-5 text-center">Ketercapaian</th>
                            <th class="py-3 px-5 text-center">Response Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mappedParticipants as $index => $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-5 text-center text-gray-400 font-medium">{{ $participants->firstItem() + $index }}</td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800">{{ $row->name }}</td>
                                <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->activityTitle }}</td>

                                {{-- Target (Cat 5) --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level3->cat5 === 'Sudah')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-100">Belum</span>
                                    @endif
                                </td>

                                {{-- Kompetensi (Cat 6) --}}
                                <td class="py-3.5 px-5 text-center font-semibold">
                                    @if ($row->level3->cat6 !== '-')
                                        <span class="px-2 py-0.5 rounded text-xs {{ $row->level3->cat6 >= 3 ? 'bg-emerald-50 text-emerald-700' : ($row->level3->cat6 >= 2 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }} border">
                                            {{ $row->level3->cat6 }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Patient Safety (Cat 7) --}}
                                <td class="py-3.5 px-5 text-center font-semibold">
                                    @if ($row->level3->cat7 !== '-')
                                        <span class="px-2 py-0.5 rounded text-xs {{ $row->level3->cat7 >= 3 ? 'bg-emerald-50 text-emerald-700' : ($row->level3->cat7 >= 2 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }} border">
                                            {{ $row->level3->cat7 }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Transfer (Cat 8) --}}
                                <td class="py-3.5 px-5 text-center font-semibold">
                                    @if ($row->level3->cat8 !== '-')
                                        <span class="px-2 py-0.5 rounded text-xs {{ $row->level3->cat8 >= 3 ? 'bg-emerald-50 text-emerald-700' : ($row->level3->cat8 >= 2 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }} border">
                                            {{ $row->level3->cat8 }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Efektivitas (Cat 9) --}}
                                <td class="py-3.5 px-5 text-center font-semibold">
                                    @if ($row->level3->cat9 !== '-')
                                        <span class="px-2 py-0.5 rounded text-xs {{ $row->level3->cat9 >= 3 ? 'bg-emerald-50 text-emerald-700' : ($row->level3->cat9 >= 2 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }} border">
                                            {{ $row->level3->cat9 }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Data Dukung (Cat 10) --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level3->cat10 === 'Sudah')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-100">Belum</span>
                                    @endif
                                </td>

                                {{-- Ketercapaian --}}
                                <td class="py-3.5 px-5 text-center font-extrabold text-gray-800">
                                    @if ($row->level3->ketercapaian !== '-')
                                        <span class="inline-flex items-center gap-0.5 px-2.5 py-1 rounded-lg text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <svg class="w-3 h-3 text-indigo-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            {{ $row->level3->ketercapaian }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Response Rate --}}
                                <td class="py-3.5 px-5 text-center">
                                    @if ($row->level3->submitted === 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-10 text-gray-400 bg-gray-50/20">Belum ada data evaluasi level 3.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── PAGINATION ──────────────────────────────────────────────────────── --}}
            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50/20">
                {{ $participants->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-layouts.app>
