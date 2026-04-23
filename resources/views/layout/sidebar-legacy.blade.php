<div class="sidebar" id="sidebar">
    <div class="logo font-sans flex flex-col items-center justify-center p-4">
        <img src="{{ asset('assets/images/logo-sipelatih.png') }}" class="w-44 mb-2">
        <small class="text-xs text-center text-gray-200">RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <div class="menu flex flex-col mt-4 font-sans space-y-1">

        <!-- ======================= -->
        <!-- MENU UTAMA (ALL USERS)  -->
        <!-- ======================= -->
        <a href="{{ url('/') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('/') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
            <i class="fa-solid fa-house w-6 text-center mr-2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ url('/usulan-diklat') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('usulan-diklat*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
            <i class="fa-solid fa-folder w-6 text-center mr-2"></i>
            <span>Usulan Diklat</span>
        </a>

        <!-- ======================= -->
        <!-- DROPDOWN: MONITORING    -->
        <!-- ======================= -->
        @php
            $isMonitoringOpen = request()->is('monitoring-jpl*') || request()->is('indikator-kinerja*');
        @endphp
        <details class="group" {{ $isMonitoringOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <span class="flex items-center">
                    <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i>
                    <span>Monitoring</span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col bg-teal-900 bg-opacity-40 pb-1">
                <a href="{{ url('/monitoring-jpl') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('monitoring-jpl*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-chart-line w-6 text-center mr-2 text-sm"></i>
                    <span>Monitoring JPL</span>
                </a>
                <a href="{{ url('/indikator-kinerja') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('indikator-kinerja*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-chart-bar w-6 text-center mr-2 text-sm"></i>
                    <span>Indikator Kinerja</span>
                </a>
            </div>
        </details>

        <!-- ======================= -->
        <!-- DROPDOWN: MANAJEMEN (PENGUSUL) -->
        <!-- ======================= -->
        @hasrole('Pengusul')
        @php
            $isManajemenOpen = request()->is('users*') || request()->is('manajemen-sasaran-profesi*');
        @endphp
        <details class="group" {{ $isManajemenOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <span class="flex items-center">
                    <i class="fa-solid fa-users-gear w-6 text-center mr-2"></i>
                    <span>Manajemen</span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col bg-teal-900 bg-opacity-40 pb-1">
                <a href="{{ route('users.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('users*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-id-card w-6 text-center mr-2 text-sm"></i>
                    <span>Management Pegawai</span>
                </a>
                <a href="{{ url('/manajemen-sasaran-profesi') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('manajemen-sasaran-profesi*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-briefcase w-6 text-center mr-2 text-sm"></i>
                    <span>Sasaran Profesi</span>
                </a>
            </div>
        </details>
        @endhasrole

        <!-- ======================= -->
        <!-- MENU SUPERADMIN         -->
        <!-- ======================= -->
        @hasrole('SuperAdmin')

        <!-- DROPDOWN: EVALUASI & LAPORAN -->
        @php
            $isEvaluasiOpen = request()->is('pagu*') || request()->is('laporan-kegiatan*')
                || request()->is('evaluasi1*') || request()->is('evaluasi2*') || request()->is('evaluasi3*');
        @endphp
        <details class="group" {{ $isEvaluasiOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <span class="flex items-center">
                    <i class="fa-solid fa-file-chart-column w-6 text-center mr-2"></i>
                    <span>Evaluasi & Laporan</span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col bg-teal-900 bg-opacity-40 pb-1">
                <a href="{{ route('pagu.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('pagu*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-money-bill w-6 text-center mr-2 text-sm"></i>
                    <span>Pagu</span>
                </a>
                <a href="{{ route('kegiatan.laporan.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('laporan-kegiatan*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-file-invoice w-6 text-center mr-2 text-sm"></i>
                    <span>Laporan Kegiatan</span>
                </a>
                <a href="{{ url('/evaluasi1') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('evaluasi1*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                    <span>Evaluasi I</span>
                </a>
                <a href="{{ url('/evaluasi2') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('evaluasi2*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                    <span>Evaluasi II</span>
                </a>
                <a href="{{ url('/evaluasi3') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('evaluasi3*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                    <span>Evaluasi III</span>
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
        <details class="group" {{ $isMasterDataOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-4 py-3 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <span class="flex items-center">
                    <i class="fa-solid fa-database w-6 text-center mr-2"></i>
                    <span>Master Data</span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transform group-open:rotate-180 transition-transform"></i>
            </summary>
            <div class="flex flex-col bg-teal-900 bg-opacity-40 pb-1">
                <a href="{{ route('users.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('users*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-id-card w-6 text-center mr-2 text-sm"></i>
                    <span>Data Pengguna</span>
                </a>
                <a href="{{ route('professions.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('professions*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-briefcase w-6 text-center mr-2 text-sm"></i>
                    <span>Data Profesi</span>
                </a>
                <a href="{{ route('profession-categories.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('profession-categories*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-list w-6 text-center mr-2 text-sm"></i>
                    <span>Kategori Profesi</span>
                </a>
                <a href="{{ route('roles.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('roles*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-user-shield w-6 text-center mr-2 text-sm"></i>
                    <span>Data Role</span>
                </a>
                <a href="{{ route('positions.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('positions*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-user-tie w-6 text-center mr-2 text-sm"></i>
                    <span>Data Jabatan</span>
                </a>
                <a href="{{ route('work-units.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('work-units*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-building w-6 text-center mr-2 text-sm"></i>
                    <span>Unit Kerja</span>
                </a>
                <a href="{{ route('activity-types.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/activity-types*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-tags w-6 text-center mr-2 text-sm"></i>
                    <span>Jenis Kegiatan</span>
                </a>
                <a href="{{ route('activity-categories.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/activity-categories*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-list-alt w-6 text-center mr-2 text-sm"></i>
                    <span>Kategori Kegiatan</span>
                </a>
                <a href="{{ route('activity-scopes.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/activity-scopes*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-globe w-6 text-center mr-2 text-sm"></i>
                    <span>Ruang Lingkup</span>
                </a>
                <a href="{{ route('material-types.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/material-types*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-book-open w-6 text-center mr-2 text-sm"></i>
                    <span>Jenis Materi</span>
                </a>
                <a href="{{ route('activity-formats.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/activity-formats*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-shapes w-6 text-center mr-2 text-sm"></i>
                    <span>Bentuk Kegiatan</span>
                </a>
                <a href="{{ route('activity-methods.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/activity-methods*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-layer-group w-6 text-center mr-2 text-sm"></i>
                    <span>Metode Kegiatan</span>
                </a>
                <a href="{{ route('employment-types.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('employment-types*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-address-book w-6 text-center mr-2 text-sm"></i>
                    <span>Jenis Kepegawaian</span>
                </a>
                <a href="{{ route('batches.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/batches*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-layer-group w-6 text-center mr-2 text-sm"></i>
                    <span>Batch</span>
                </a>
                <a href="{{ route('fund-sources.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('fund-sources*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-coins w-6 text-center mr-2 text-sm"></i>
                    <span>Sumber Dana</span>
                </a>
                <a href="{{ route('activity-names.index') }}"
                    class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-teal-700 hover:text-white transition-colors {{ request()->is('dictionaries/activity-names*') ? 'bg-teal-800 border-l-4 border-teal-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-signature w-6 text-center mr-2 text-sm"></i>
                    <span>Nama Kegiatan</span>
                </a>
            </div>
        </details>

        @endhasrole
    </div>
</div>
