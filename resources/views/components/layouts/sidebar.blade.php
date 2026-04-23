<aside 
    class="w-[240px] h-screen bg-[#1A5555] text-white fixed top-0 transition-all duration-300 overflow-y-auto z-[1000]"
    :class="sidebarOpen ? 'left-0' : '-left-[240px]'"
>
    <div class="p-4 text-center bg-[#113a3a]">
        <img src="{{ asset('assets/images/logo-sipelatih.png') }}" class="w-44 mx-auto mb-2">
        <small class="text-xs block text-gray-300 mt-1">RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <div class="flex flex-col mt-4 space-y-1">
        <!-- COMMON MENU (ALL USERS) -->
        <a href="{{ url('/') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('/') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-house w-6 text-center mr-2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ url('/usulan-diklat') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('usulan-diklat*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-folder w-6 text-center mr-2"></i>
            <span>Usulan Diklat</span>
        </a>

        <a href="{{ url('/monitoring-jpl') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('monitoring-jpl*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i>
            <span>Monitoring JPL</span>
        </a>

        <a href="{{ url('/indikator-kinerja') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('indikator-kinerja*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-chart-bar w-6 text-center mr-2"></i>
            <span>Indikator Kinerja</span>
        </a>

        <!-- MENU PENGUSUL -->
        @hasrole('Pengusul')
            <div class="px-5 pt-4 pb-2 text-xs font-bold text-teal-300 uppercase tracking-wider">
                Manajemen
            </div>

            <a href="{{ route('users.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('users*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-id-card w-6 text-center mr-2"></i>
                <span>Management Pegawai</span>
            </a>

            <a href="{{ url('/manajemen-sasaran-profesi') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('manajemen-sasaran-profesi*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-briefcase w-6 text-center mr-2"></i>
                <span>Management Sasaran profesi</span>
            </a>
        @endhasrole

        <!-- MENU SUPERADMIN -->
        @hasrole('SuperAdmin')
            <div class="px-5 pt-4 pb-2 text-xs font-bold text-teal-300 uppercase tracking-wider">
                Evaluasi & Laporan
            </div>

            <a href="{{ route('pagu.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('pagu*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-money-bill w-6 text-center mr-2"></i>
                <span>Pagu</span>
            </a>

            <a href="{{ route('kegiatan.laporan.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('laporan-kegiatan*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-file-invoice w-6 text-center mr-2"></i>
                <span>Laporan Kegiatan</span>
            </a>

            <a href="{{ url('/evaluasi1') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluasi1*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-clipboard-check w-6 text-center mr-2"></i>
                <span>Evaluasi I</span>
            </a>

            <a href="{{ url('/evaluasi2') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluasi2*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-clipboard-check w-6 text-center mr-2"></i>
                <span>Evaluasi II</span>
            </a>

            <a href="{{ url('/evaluasi3') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluasi3*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-clipboard-check w-6 text-center mr-2"></i>
                <span>Evaluasi III</span>
            </a>

            <div class="px-5 pt-4 pb-2 text-xs font-bold text-teal-300 uppercase tracking-wider">
                Master Data
            </div>

            <a href="{{ route('users.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('users*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-id-card w-6 text-center mr-2"></i>
                <span>Data Pengguna</span>
            </a>

            <a href="{{ route('professions.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('professions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-briefcase w-6 text-center mr-2"></i>
                <span>Data Profesi</span>
            </a>

            <a href="{{ route('profession-categories.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('profession-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-list w-6 text-center mr-2"></i>
                <span>Kategori Profesi</span>
            </a>

            <a href="{{ route('roles.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('roles*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-user-shield w-6 text-center mr-2"></i>
                <span>Data Role</span>
            </a>

            <a href="{{ route('positions.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('positions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-user-tie w-6 text-center mr-2"></i>
                <span>Data Jabatan</span>
            </a>

            <!-- Other Master Data placeholders from the unused sidebar -->
            <a href="{{ route('work-units.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('work-units*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-building w-6 text-center mr-2"></i>
                <span>Unit Kerja</span>
            </a>

            <a href="{{ route('activity-types.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-tags w-6 text-center mr-2"></i>
                <span>Jenis Kegiatan</span>
            </a>

            <a href="{{ route('activity-categories.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-list-alt w-6 text-center mr-2"></i>
                <span>Kategori Kegiatan</span>
            </a>

            <a href="{{ route('activity-scopes.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-scopes*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-globe w-6 text-center mr-2"></i>
                <span>Ruang Lingkup</span>
            </a>

            <a href="{{ route('material-types.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/material-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-book-open w-6 text-center mr-2"></i>
                <span>Jenis Materi</span>
            </a>

            <a href="{{ route('activity-formats.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-formats*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-shapes w-6 text-center mr-2"></i>
                <span>Bentuk Kegiatan</span>
            </a>

            <a href="{{ route('activity-methods.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-methods*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-layer-group w-6 text-center mr-2"></i>
                <span>Metode Kegiatan</span>
            </a>

            <a href="{{ route('employment-types.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('employment-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-address-book w-6 text-center mr-2"></i>
                <span>Jenis Kepegawaian</span>
            </a>

            <a href="{{ route('batches.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/batches*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-layer-group w-6 text-center mr-2"></i>
                <span>Batch</span>
            </a>

            <a href="{{ route('fund-sources.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('fund-sources*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-coins w-6 text-center mr-2"></i>
                <span>Sumber Dana</span>
            </a>

            <a href="{{ route('activity-names.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-names*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-signature w-6 text-center mr-2"></i>
                <span>Nama Kegiatan</span>
            </a>
        @endhasrole
    </div>
</aside>
