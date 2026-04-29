<aside
    id="sidebar"
    class="sidebar w-[240px] h-screen bg-[#1A5555] text-white fixed top-0 left-0 transition-all duration-300 z-[1000] border-r border-[#1fd1d1]/20 flex flex-col shadow-xl"
    :class="sidebarOpen ? 'left-0' : '-left-[240px]'">
    <!-- Header/Logo Area -->
    <div class="sticky top-0 z-10 flex flex-col items-center justify-center p-4 bg-[#113a3a] border-b border-black/20 shrink-0">
        <img src="{{ asset('assets/images/logo-sipelatih.png') }}" class="w-40 transition-transform hover:scale-105 duration-300" alt="SIPELATIH">
        <small class="text-[10px] text-gray-300 mt-2 tracking-widest uppercase font-semibold text-center border-t border-white/10 pt-1 w-full">RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <!-- Navigation Area w/ Custom Scrollbar -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-white/10 hover:[&::-webkit-scrollbar-thumb]:bg-white/25 [&::-webkit-scrollbar-thumb]:rounded-full">

        <!-- MENU UTAMA -->
        <a href="{{ url('/') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('/') ? 'bg-white/10 text-white font-medium shadow-sm ring-1 ring-white/5' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <i class="fa-solid fa-house w-6 text-center mr-2 transition-colors {{ request()->is('/') ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="{{ url('/usulan-diklat') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('usulan-diklat*') ? 'bg-white/10 text-white font-medium shadow-sm ring-1 ring-white/5' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <i class="fa-solid fa-folder w-6 text-center mr-2 transition-colors {{ request()->is('usulan-diklat*') ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
            <span class="text-sm">Usulan Diklat</span>
        </a>

        <!-- DROPDOWN: MONITORING -->
        @php $isMonitoringOpen = request()->is('monitoring-jpl*') || request()->is('indikator-kinerja*'); @endphp
        <details class="group mt-2" {{ $isMonitoringOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none {{ $isMonitoringOpen ? 'bg-white/5 text-white font-medium' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-chart-line w-6 text-center mr-2 transition-colors {{ $isMonitoringOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
                    <span class="text-sm">Monitoring</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 {{ $isMonitoringOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
            </summary>
            <div class="flex flex-col mt-1 mb-2 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
                <a href="{{ url('/monitoring-jpl') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('monitoring-jpl*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center mr-2 opacity-80"></i> Monitoring JPL
                </a>
                <a href="{{ url('/indikator-kinerja') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('indikator-kinerja*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-bar w-5 text-center mr-2 opacity-80"></i> Indikator Kinerja
                </a>
            </div>
        </details>

        <!-- DROPDOWN: MANAJEMEN (PENGUSUL) -->
        @hasrole('Pengusul')
        @php $isManajemenOpen = request()->is('users*') || request()->is('manajemen-sasaran-profesi*'); @endphp
        <details class="group mt-2" {{ $isManajemenOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none {{ $isManajemenOpen ? 'bg-white/5 text-white font-medium' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-users-gear w-6 text-center mr-2 transition-colors {{ $isManajemenOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
                    <span class="text-sm">Manajemen</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 {{ $isManajemenOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
            </summary>
            <div class="flex flex-col mt-1 mb-2 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
                <a href="{{ route('users.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('users*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-id-card w-5 text-center mr-2 opacity-80"></i> Management Pegawai
                </a>
                <a href="{{ url('/manajemen-sasaran-profesi') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('manajemen-sasaran-profesi*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-briefcase w-5 text-center mr-2 opacity-80"></i> Sasaran Profesi
                </a>
            </div>
        </details>
        @endhasrole

        <!-- SUPERADMIN MENUS -->
        @hasrole('SuperAdmin')

        <div class="px-3 pb-1 pt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Administrator</div>

        <!-- DROPDOWN: EVALUASI & LAPORAN -->
        @php
        $isEvaluasiOpen = request()->is('pagu*') || request()->is('laporan-kegiatan*')
        || request()->is('evaluasi1*') || request()->is('evaluasi2*') || request()->is('evaluasi3*');
        @endphp
        <details class="group mt-2" {{ $isEvaluasiOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none {{ $isEvaluasiOpen ? 'bg-white/5 text-white font-medium' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-file-chart-column w-6 text-center mr-2 transition-colors {{ $isEvaluasiOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
                    <span class="text-sm">Evaluasi & Laporan</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 {{ $isEvaluasiOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
            </summary>
            <div class="flex flex-col mt-1 mb-2 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
                <a href="{{ route('pagu.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('pagu*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-money-bill w-5 text-center mr-2 opacity-80"></i> Pagu
                </a>
                <a href="{{ route('kegiatan.laporan.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('laporan-kegiatan*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-file-invoice w-5 text-center mr-2 opacity-80"></i> Laporan Kegiatan
                </a>
                <a href="{{ url('/evaluasi1') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('evaluasi1*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center mr-2 opacity-80"></i> Evaluasi I
                </a>
                <a href="{{ url('/evaluasi2') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('evaluasi2*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center mr-2 opacity-80"></i> Evaluasi II
                </a>
                <a href="{{ url('/evaluasi3') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('evaluasi3*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center mr-2 opacity-80"></i> Evaluasi III
                </a>
            </div>
        </details>

        <!-- DROPDOWN: MASTER DATA -->
        @php
        $isMasterDataOpen = request()->is('users*') || request()->is('professions*') || request()->is('profession-categories*') ||
        request()->is('roles*') || request()->is('positions*') || request()->is('work-units*') ||
        request()->is('dictionaries/activity-types*') || request()->is('dictionaries/activity-categories*') ||
        request()->is('dictionaries/activity-scopes*') || request()->is('dictionaries/material-types*') ||
        request()->is('dictionaries/activity-formats*') || request()->is('dictionaries/activity-methods*') ||
        request()->is('employment-types*') || request()->is('dictionaries/batches*') ||
        request()->is('fund-sources*') || request()->is('dictionaries/activity-names*');
        @endphp
        <details class="group mt-2" {{ $isMasterDataOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none {{ $isMasterDataOpen ? 'bg-white/5 text-white font-medium' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-database w-6 text-center mr-2 transition-colors {{ $isMasterDataOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
                    <span class="text-sm">Master Data</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 {{ $isMasterDataOpen ? 'text-[#1fd1d1]' : 'text-gray-400 group-hover:text-gray-200' }}"></i>
            </summary>
            <!-- Taller Submenu Area -->
            <div class="flex flex-col mt-1 mb-6 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
                <a href="{{ route('users.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('users*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-id-card w-5 text-center mr-2 opacity-80"></i> Data Pengguna
                </a>
                <a href="{{ route('professions.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('professions*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-briefcase w-5 text-center mr-2 opacity-80"></i> Data Profesi
                </a>
                <a href="{{ route('profession-categories.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('profession-categories*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-list w-5 text-center mr-2 opacity-80"></i> Kategori Profesi
                </a>
                <a href="{{ route('roles.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('roles*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-user-shield w-5 text-center mr-2 opacity-80"></i> Data Role
                </a>
                <a href="{{ route('positions.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('positions*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-user-tie w-5 text-center mr-2 opacity-80"></i> Data Jabatan
                </a>
                <a href="{{ route('work-units.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('work-units*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-building w-5 text-center mr-2 opacity-80"></i> Unit Kerja
                </a>
                <a href="{{ route('activity-types.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/activity-types*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-tags w-5 text-center mr-2 opacity-80"></i> Jenis Kegiatan
                </a>
                <a href="{{ route('activity-categories.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/activity-categories*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-list-alt w-5 text-center mr-2 opacity-80"></i> Kategori Kegiatan
                </a>
                <a href="{{ route('activity-scopes.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/activity-scopes*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-globe w-5 text-center mr-2 opacity-80"></i> Ruang Lingkup
                </a>
                <a href="{{ route('material-types.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/material-types*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-book-open w-5 text-center mr-2 opacity-80"></i> Jenis Materi
                </a>
                <a href="{{ route('activity-formats.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/activity-formats*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-shapes w-5 text-center mr-2 opacity-80"></i> Bentuk Kegiatan
                </a>
                <a href="{{ route('activity-methods.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/activity-methods*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center mr-2 opacity-80"></i> Metode Kegiatan
                </a>
                <a href="{{ route('employment-types.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('employment-types*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-address-book w-5 text-center mr-2 opacity-80"></i> Jenis Kepegawaian
                </a>
                <a href="{{ route('batches.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/batches*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center mr-2 opacity-80"></i> Batch
                </a>
                <a href="{{ route('fund-sources.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('fund-sources*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-coins w-5 text-center mr-2 opacity-80"></i> Sumber Dana
                </a>
                <a href="{{ route('activity-names.index') }}"
                    class="flex items-center pl-8 pr-3 py-2 rounded-lg transition-all duration-200 text-sm {{ request()->is('dictionaries/activity-names*') ? 'bg-white/5 text-[#1fd1d1] font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-signature w-5 text-center mr-2 opacity-80"></i> Nama Kegiatan
                </a>
            </div>
        </details>
        @endhasrole
    </nav>
</aside>