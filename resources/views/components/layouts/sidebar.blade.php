<aside
    class="w-[240px] h-[calc(100vh-60px)] lg:h-screen bg-[#1A5555] text-white fixed top-[60px] lg:top-0 transition-all duration-300 overflow-y-auto z-[1000]"
    :style="sidebarOpen ? 'left: 0' : 'left: -240px'">
    <div class="p-4 text-center bg-[#113a3a]">
        <img src="{{ $appSettings->get('app_logo') ? asset('storage/' . $appSettings->get('app_logo')) : asset('assets/images/logo-sipelatih.png') }}"
            class="w-44 mx-auto mb-2">
        <small class="text-xs block text-gray-300 mt-1">RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <div class="flex flex-col mt-4 space-y-1">

        <!-- MENU UTAMA -->
        @can('view dashboard')
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('/') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-house w-6 text-center mr-2"></i>
            <span>Dashboard</span>
        </a>
        @endcan

        @can('view usulan diklat')
        <a href="{{ route('usulan-diklat') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('usulan-diklat*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-folder w-6 text-center mr-2"></i>
            <span>Usulan Diklat</span>
        </a>
        @endcan

        <!-- MONITORING JPL -->
        @can('view monitoring jpl')
        <a href="{{ route('monitoring.jpl.index') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('monitoring-jpl*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i>
            <span>Monitoring JPL</span>
        </a>
        @endcan

        <!-- DROPDOWN: KEUANGAN -->
        @canany(['view budget categories', 'view pagu'])
            @php
                $isKeuanganOpen =
                    request()->is('budget-categories*') || request()->is('pagu*');
            @endphp
            <details class="group" {{ $isKeuanganOpen ? 'open' : '' }}>
                <summary
                    class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center">
                        <i class="fa-solid fa-wallet w-6 text-center mr-2"></i>
                        <span>Keuangan</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
                </summary>
                <div class="flex flex-col bg-[#113a3a] pb-1">
                    @can('view budget categories')
                        <a href="{{ route('budget-categories.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('budget-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-tag w-6 text-center mr-2 text-sm"></i>
                            <span>Kategori Pagu</span>
                        </a>
                    @endcan

                    @can('view pagu')
                        <a href="{{ route('pagu.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('pagu*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-money-bill w-6 text-center mr-2 text-sm"></i>
                            <span>Pagu</span>
                        </a>
                    @endcan
                </div>
            </details>
        @endcanany

        <!-- DROPDOWN: EVALUASI & LAPORAN -->
        @canany(['view kegiatan laporan', 'view evaluasi'])
            @php
                $isEvaluasiOpen =
                    request()->is('laporan-kegiatan*') || request()->is('evaluations*');
            @endphp
            <details class="group" {{ $isEvaluasiOpen ? 'open' : '' }}>
                <summary
                    class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center">
                        <i class="fa-solid fa-chart-column w-6 text-center mr-2"></i>
                        <span>Evaluasi & Laporan</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
                </summary>
                <div class="flex flex-col bg-[#113a3a] pb-1">
                    @can('view kegiatan laporan')
                        <a href="{{ route('kegiatan.laporan.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('laporan-kegiatan*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-file-invoice w-6 text-center mr-2 text-sm"></i>
                            <span>Laporan</span>
                        </a>
                    @endcan

                    @can('view evaluasi')
                        <a href="{{ route('evaluations.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluations*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                            <span>Evaluasi</span>
                        </a>
                    @endcan
                </div>
            </details>
        @endcanany

        <!-- MASTER DATA & CONFIG (GRANULAR ACCESS) -->
        @canany([
            'view users', 'view accounts', 'view professions', 'view profession categories', 
            'view roles', 'view permissions', 'view positions', 'view ranks', 'view work units', 
            'view activity types', 'view activity categories', 'view activity scopes', 
            'view material types', 'view activity formats', 'view activity methods', 
            'view employment types', 'view batches', 'view fund sources', 'view activity names',
            'view evaluation criteria'
        ])
            @php
                $isMasterDataOpen =
                    request()->is('users*') ||
                    request()->is('accounts*') ||
                    request()->is('professions*') ||
                    request()->is('profession-categories*') ||
                    request()->is('roles*') ||
                    request()->is('permissions*') ||
                    request()->is('positions*') ||
                    request()->is('ranks*') ||
                    request()->is('work-units*') ||
                    request()->is('dictionaries/activity-types*') ||
                    request()->is('dictionaries/activity-categories*') ||
                    request()->is('dictionaries/activity-scopes*') ||
                    request()->is('dictionaries/material-types*') ||
                    request()->is('dictionaries/activity-formats*') ||
                    request()->is('dictionaries/activity-methods*') ||
                    request()->is('employment-types*') ||
                    request()->is('dictionaries/batches*') ||
                    request()->is('fund-sources*') ||
                    request()->is('dictionaries/activity-names*') ||
                    request()->is('evaluation-criteria*');
            @endphp
            <details class="group" {{ $isMasterDataOpen ? 'open' : '' }}>
                <summary
                    class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center">
                        <i class="fa-solid fa-database w-6 text-center mr-2"></i>
                        <span>Master Data</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
                </summary>
                <div class="flex flex-col bg-[#113a3a] pb-1">
                    @can('view users')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('users*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-users w-6 text-center mr-2 text-sm"></i>
                            <span>Data Pegawai</span>
                        </a>
                    @endcan

                    @can('view accounts')
                        <a href="{{ route('accounts.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('accounts*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-user-shield w-6 text-center mr-2 text-sm"></i>
                            <span>Data Akun</span>
                        </a>
                    @endcan

                    @can('view professions')
                        <a href="{{ route('professions.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('professions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-briefcase w-6 text-center mr-2 text-sm"></i>
                            <span>Data Profesi</span>
                        </a>
                    @endcan

                    @can('view profession categories')
                        <a href="{{ route('profession-categories.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('profession-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-list w-6 text-center mr-2 text-sm"></i>
                            <span>Kategori Profesi</span>
                        </a>
                    @endcan

                    @can('view roles')
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('roles*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-user-shield w-6 text-center mr-2 text-sm"></i>
                            <span>Data Role</span>
                        </a>
                    @endcan

                    @can('view permissions')
                        <a href="{{ route('permissions.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('permissions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-key w-6 text-center mr-2 text-sm"></i>
                            <span>Data Permission</span>
                        </a>
                    @endcan

                    @can('view positions')
                        <a href="{{ route('positions.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('positions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-user-tie w-6 text-center mr-2 text-sm"></i>
                            <span>Data Jabatan</span>
                        </a>
                    @endcan

                    @can('view ranks')
                        <a href="{{ route('ranks.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('ranks*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-star w-6 text-center mr-2 text-sm"></i>
                            <span>Data Pangkat</span>
                        </a>
                    @endcan

                    @can('view work units')
                        <a href="{{ route('work-units.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('work-units*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-building w-6 text-center mr-2 text-sm"></i>
                            <span>Unit Kerja</span>
                        </a>
                    @endcan

                    @can('view activity types')
                        <a href="{{ route('activity-types.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-tags w-6 text-center mr-2 text-sm"></i>
                            <span>Jenis Kegiatan</span>
                        </a>
                    @endcan

                    @can('view activity categories')
                        <a href="{{ route('activity-categories.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-list-alt w-6 text-center mr-2 text-sm"></i>
                            <span>Kategori Kegiatan</span>
                        </a>
                    @endcan

                    @can('view activity scopes')
                        <a href="{{ route('activity-scopes.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-scopes*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-globe w-6 text-center mr-2 text-sm"></i>
                            <span>Ruang Lingkup</span>
                        </a>
                    @endcan

                    @can('view material types')
                        <a href="{{ route('material-types.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/material-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-book-open w-6 text-center mr-2 text-sm"></i>
                            <span>Jenis Materi</span>
                        </a>
                    @endcan

                    @can('view activity formats')
                        <a href="{{ route('activity-formats.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-formats*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-shapes w-6 text-center mr-2 text-sm"></i>
                            <span>Bentuk Kegiatan</span>
                        </a>
                    @endcan

                    @can('view activity methods')
                        <a href="{{ route('activity-methods.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-methods*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-layer-group w-6 text-center mr-2 text-sm"></i>
                            <span>Metode Kegiatan</span>
                        </a>
                    @endcan

                    @can('view employment types')
                        <a href="{{ route('employment-types.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('employment-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-address-book w-6 text-center mr-2 text-sm"></i>
                            <span>Jenis Kepegawaian</span>
                        </a>
                    @endcan

                    @can('view batches')
                        <a href="{{ route('batches.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/batches*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-layer-group w-6 text-center mr-2 text-sm"></i>
                            <span>Batch</span>
                        </a>
                    @endcan

                    @can('view fund sources')
                        <a href="{{ route('fund-sources.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('fund-sources*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-coins w-6 text-center mr-2 text-sm"></i>
                            <span>Sumber Dana</span>
                        </a>
                    @endcan

                    @can('view activity names')
                        <a href="{{ route('activity-names.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-names*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-signature w-6 text-center mr-2 text-sm"></i>
                            <span>Nama Kegiatan</span>
                        </a>
                    @endcan

                    @can('view evaluation criteria')
                        <a href="{{ route('evaluation-criteria.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluation-criteria*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-clipboard-list w-6 text-center mr-2 text-sm"></i>
                            <span>Kriteria Evaluasi</span>
                        </a>
                    @endcan
                </div>
            </details>
        @endcanany

        <!-- PENGATURAN -->
        @can('view settings')
            <a href="{{ route('settings.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('settings*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-gear w-6 text-center mr-2"></i>
                <span>Pengaturan</span>
            </a>
        @endcan

    </div>
</aside>
