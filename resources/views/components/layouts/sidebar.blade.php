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
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('/') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-house w-6 text-center mr-2"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('usulan-diklat') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('usulan-diklat*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-folder w-6 text-center mr-2"></i>
            <span>Usulan Diklat</span>
        </a>

        <!-- MONITORING JPL -->
        <a href="{{ route('monitoring.jpl.index') }}"
            class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('monitoring-jpl*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
            <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i>
            <span>Monitoring JPL</span>
        </a>

        <!-- DROPDOWN: EVALUASI & LAPORAN -->
        @hasanyrole('perencanaan|penyelenggara|evaluasi|superadmin')
            @php
                $isEvaluasiOpen =
                    request()->is('pagu*') ||
                    request()->is('laporan-kegiatan*') ||
                    request()->is('evaluasi1*') ||
                    request()->is('evaluasi2*') ||
                    request()->is('evaluasi3*');
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
                    @hasanyrole('perencanaan|superadmin')
                        <a href="{{ route('budget-categories.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('budget-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-tag w-6 text-center mr-2 text-sm"></i>
                            <span>Kategori Pagu</span>
                        </a>
                        <a href="{{ route('pagu.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('pagu*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-money-bill w-6 text-center mr-2 text-sm"></i>
                            <span>Pagu</span>
                        </a>
                    @endhasanyrole

                    @hasanyrole('perencanaan|penyelenggara|evaluasi|superadmin')
                        <a href="{{ route('kegiatan.laporan.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('laporan-kegiatan*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-file-invoice w-6 text-center mr-2 text-sm"></i>
                            <span>Laporan Kegiatan</span>
                        </a>
                    @endhasanyrole

                    @hasanyrole('evaluasi|superadmin')
                        <a href="{{ route('evaluasi1') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluasi1*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                            <span>Evaluasi I</span>
                        </a>
                        <a href="{{ route('evaluasi2') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluasi2*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                            <span>Evaluasi II</span>
                        </a>
                        <a href="{{ route('evaluasi3') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('evaluasi3*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-clipboard-check w-6 text-center mr-2 text-sm"></i>
                            <span>Evaluasi III</span>
                        </a>
                    @endhasanyrole
                </div>
            </details>
        @endhasanyrole

        <!-- SUPERADMIN MENUS -->
        @hasrole('superadmin')
            <!-- DROPDOWN: MASTER DATA -->
            @php
                $isMasterDataOpen =
                    request()->is('users*') ||
                    request()->is('accounts*') ||
                    request()->is('professions*') ||
                    request()->is('profession-categories*') ||
                    request()->is('roles*') ||
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
                    request()->is('dictionaries/activity-names*');
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
                    <a href="{{ route('users.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('users*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-users w-6 text-center mr-2 text-sm"></i>
                        <span>Data Pegawai</span>
                    </a>
                    <a href="{{ route('accounts.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('accounts*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-user-shield w-6 text-center mr-2 text-sm"></i>
                        <span>Data Akun</span>
                    </a>
                    <a href="{{ route('professions.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('professions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-briefcase w-6 text-center mr-2 text-sm"></i>
                        <span>Data Profesi</span>
                    </a>
                    <a href="{{ route('profession-categories.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('profession-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-list w-6 text-center mr-2 text-sm"></i>
                        <span>Kategori Profesi</span>
                    </a>
                    <a href="{{ route('roles.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('roles*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-user-shield w-6 text-center mr-2 text-sm"></i>
                        <span>Data Role</span>
                    </a>
                    <a href="{{ route('positions.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('positions*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-user-tie w-6 text-center mr-2 text-sm"></i>
                        <span>Data Jabatan</span>
                    </a>
                    <a href="{{ route('ranks.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('ranks*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-star w-6 text-center mr-2 text-sm"></i>
                        <span>Data Pangkat</span>
                    </a>
                    <a href="{{ route('work-units.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('work-units*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-building w-6 text-center mr-2 text-sm"></i>
                        <span>Unit Kerja</span>
                    </a>
                    <a href="{{ route('activity-types.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-tags w-6 text-center mr-2 text-sm"></i>
                        <span>Jenis Kegiatan</span>
                    </a>
                    <a href="{{ route('activity-categories.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-categories*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-list-alt w-6 text-center mr-2 text-sm"></i>
                        <span>Kategori Kegiatan</span>
                    </a>
                    <a href="{{ route('activity-scopes.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-scopes*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-globe w-6 text-center mr-2 text-sm"></i>
                        <span>Ruang Lingkup</span>
                    </a>
                    <a href="{{ route('material-types.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/material-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-book-open w-6 text-center mr-2 text-sm"></i>
                        <span>Jenis Materi</span>
                    </a>
                    <a href="{{ route('activity-formats.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-formats*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-shapes w-6 text-center mr-2 text-sm"></i>
                        <span>Bentuk Kegiatan</span>
                    </a>
                    <a href="{{ route('activity-methods.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-methods*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-layer-group w-6 text-center mr-2 text-sm"></i>
                        <span>Metode Kegiatan</span>
                    </a>
                    <a href="{{ route('employment-types.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('employment-types*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-address-book w-6 text-center mr-2 text-sm"></i>
                        <span>Jenis Kepegawaian</span>
                    </a>
                    <a href="{{ route('batches.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/batches*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-layer-group w-6 text-center mr-2 text-sm"></i>
                        <span>Batch</span>
                    </a>
                    <a href="{{ route('fund-sources.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('fund-sources*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-coins w-6 text-center mr-2 text-sm"></i>
                        <span>Sumber Dana</span>
                    </a>
                    <a href="{{ route('activity-names.index') }}"
                        class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('dictionaries/activity-names*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                        <i class="fa-solid fa-signature w-6 text-center mr-2 text-sm"></i>
                        <span>Nama Kegiatan</span>
                    </a>
                </div>
            </details>

            <!-- PENGATURAN (SUPERADMIN) -->
            <a href="{{ route('settings.index') }}"
                class="flex items-center px-4 py-3 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('settings*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                <i class="fa-solid fa-gear w-6 text-center mr-2"></i>
                <span>Pengaturan</span>
            </a>
        @endhasrole

    </div>
</aside>
