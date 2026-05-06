<aside
    class="w-[240px] h-[calc(100vh-60px)] lg:h-screen bg-brand-primary text-white fixed top-[60px] lg:top-0 transition-all duration-300 overflow-y-auto z-[1000]
           [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-white/20 [&::-webkit-scrollbar-thumb]:rounded-full"
    :class="sidebarOpen ? 'left-0' : '-left-[240px]'"
>
    {{-- LOGO HEADER --}}
    <div class="p-4 text-center bg-brand-dark">
        <img src="{{ $appSettings->get('app_logo') ? asset('storage/' . $appSettings->get('app_logo')) : asset('assets/images/logo-sipelatih.png') }}" class="w-44 mx-auto mb-2">
        <small class="text-xs block text-gray-300 mt-1">RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <div class="flex flex-col mt-4 px-3 space-y-1">

        {{-- MENU UTAMA --}}
        <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon="fa-house" :active="request()->is('/')">
            Dashboard
        </x-layouts.sidebar-link>

        <x-layouts.sidebar-link href="{{ route('usulan-diklat') }}" icon="fa-folder" :active="request()->is('usulan-diklat*')">
            Usulan Diklat
        </x-layouts.sidebar-link>

        {{-- MONITORING JPL --}}
        <x-layouts.sidebar-link href="{{ route('monitoring.jpl.index') }}" icon="fa-chart-line" :active="request()->is('monitoring-jpl*')">
            Monitoring JPL
        </x-layouts.sidebar-link>

        {{-- DROPDOWN: EVALUASI & LAPORAN --}}
        @hasanyrole('Perencanaan|Penyelenggara|Evaluasi|SuperAdmin')
        @php
            $isEvaluasiOpen = request()->is(['pagu*', 'laporan-kegiatan*', 'evaluasi1*', 'evaluasi2*', 'evaluasi3*']);
        @endphp
        <details class="group mt-2" {{ $isEvaluasiOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none text-gray-300 hover:bg-white/5 hover:text-white">
                <span class="flex items-center text-sm">    
                    <i class="fa-solid fa-chart-column w-6 text-center mr-2 text-gray-400 group-hover:text-gray-200"></i>
                    <span>Evaluasi & Laporan</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 text-gray-400"></i>
            </summary>
            <div class="flex flex-col mt-1 mb-2 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
                @hasanyrole('Perencanaan|SuperAdmin')
                <x-layouts.sidebar-link href="{{ route('pagu.index') }}" icon="fa-money-bill" :active="request()->is('pagu*')" :isSubmenu="true">
                    Pagu
                </x-layouts.sidebar-link>
                @endhasanyrole

                @hasanyrole('Perencanaan|Penyelenggara|Evaluasi|SuperAdmin')
                <x-layouts.sidebar-link href="{{ route('kegiatan.laporan.index') }}" icon="fa-file-invoice" :active="request()->is('laporan-kegiatan*')" :isSubmenu="true">
                    Laporan Kegiatan
                </x-layouts.sidebar-link>
                @endhasanyrole

                @hasanyrole('Evaluasi|SuperAdmin')
                <x-layouts.sidebar-link href="{{ route('evaluasi1') }}" icon="fa-clipboard-check" :active="request()->is('evaluasi1*')" :isSubmenu="true">
                    Evaluasi I
                </x-layouts.sidebar-link>
                <x-layouts.sidebar-link href="{{ route('evaluasi2') }}" icon="fa-clipboard-check" :active="request()->is('evaluasi2*')" :isSubmenu="true">
                    Evaluasi II
                </x-layouts.sidebar-link>
                <x-layouts.sidebar-link href="{{ route('evaluasi3') }}" icon="fa-clipboard-check" :active="request()->is('evaluasi3*')" :isSubmenu="true">
                    Evaluasi III
                </x-layouts.sidebar-link>
                @endhasanyrole
            </div>
        </details>
        @endhasanyrole

        {{-- SUPERADMIN MENUS --}}
        @hasrole('SuperAdmin')

        {{-- SECTION DIVIDER --}}
        <div class="px-3 pb-1 pt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Administrator</div>

        {{-- DROPDOWN: MASTER DATA --}}
        @php
            $masterItems = [
                ['route' => 'users.index', 'match' => 'users*', 'icon' => 'fa-users', 'label' => 'Data Pegawai'],
                ['route' => 'accounts.index', 'match' => 'accounts*', 'icon' => 'fa-user-shield', 'label' => 'Data Akun'],
                ['route' => 'professions.index', 'match' => 'professions*', 'icon' => 'fa-briefcase', 'label' => 'Data Profesi'],
                ['route' => 'profession-categories.index', 'match' => 'profession-categories*', 'icon' => 'fa-list', 'label' => 'Kategori Profesi'],
                ['route' => 'roles.index', 'match' => 'roles*', 'icon' => 'fa-user-shield', 'label' => 'Data Role'],
                ['route' => 'positions.index', 'match' => 'positions*', 'icon' => 'fa-user-tie', 'label' => 'Data Jabatan'],
                ['route' => 'work-units.index', 'match' => 'work-units*', 'icon' => 'fa-building', 'label' => 'Unit Kerja'],
                ['route' => 'activity-types.index', 'match' => 'dictionaries/activity-types*', 'icon' => 'fa-tags', 'label' => 'Jenis Kegiatan'],
                ['route' => 'activity-categories.index', 'match' => 'dictionaries/activity-categories*', 'icon' => 'fa-list-alt', 'label' => 'Kategori Kegiatan'],
                ['route' => 'activity-scopes.index', 'match' => 'dictionaries/activity-scopes*', 'icon' => 'fa-globe', 'label' => 'Ruang Lingkup'],
                ['route' => 'material-types.index', 'match' => 'dictionaries/material-types*', 'icon' => 'fa-book-open', 'label' => 'Jenis Materi'],
                ['route' => 'activity-formats.index', 'match' => 'dictionaries/activity-formats*', 'icon' => 'fa-shapes', 'label' => 'Bentuk Kegiatan'],
                ['route' => 'activity-methods.index', 'match' => 'dictionaries/activity-methods*', 'icon' => 'fa-layer-group', 'label' => 'Metode Kegiatan'],
                ['route' => 'employment-types.index', 'match' => 'employment-types*', 'icon' => 'fa-address-book', 'label' => 'Jenis Kepegawaian'],
                ['route' => 'batches.index', 'match' => 'dictionaries/batches*', 'icon' => 'fa-layer-group', 'label' => 'Batch'],
                ['route' => 'fund-sources.index', 'match' => 'fund-sources*', 'icon' => 'fa-coins', 'label' => 'Sumber Dana'],
                ['route' => 'activity-names.index', 'match' => 'dictionaries/activity-names*', 'icon' => 'fa-signature', 'label' => 'Nama Kegiatan'],
                ['route' => 'budget-categories.index', 'match' => 'dictionaries/budget-categories*', 'icon' => 'fa-tag', 'label' => 'Kategori Pagu'],
            ];
            $masterMatches = array_column($masterItems, 'match');
            $isMasterDataOpen = request()->is($masterMatches);
        @endphp
        <details class="group" {{ $isMasterDataOpen ? 'open' : '' }}>
            <summary class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden select-none text-gray-300 hover:bg-white/5 hover:text-white">
                <span class="flex items-center text-sm">
                    <i class="fa-solid fa-database w-6 text-center mr-2 text-gray-400 group-hover:text-gray-200"></i>
                    <span>Master Data</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] transform group-open:rotate-180 transition-transform duration-300 text-gray-400"></i>
            </summary>
            <div class="flex flex-col mt-1 mb-2 space-y-1 relative before:content-[''] before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[1.5px] before:bg-white/10 before:rounded-full">
                @foreach($masterItems as $item)
                <x-layouts.sidebar-link href="{{ route($item['route']) }}" icon="{{ $item['icon'] }}" :active="request()->is($item['match'])" :isSubmenu="true">
                    {{ $item['label'] }}
                </x-layouts.sidebar-link>
                @endforeach
            </div>
        </details>

        {{-- PENGATURAN (SUPERADMIN) --}}
        <x-layouts.sidebar-link href="{{ route('settings.index') }}" icon="fa-gear" :active="request()->is('settings*')">
            Pengaturan
        </x-layouts.sidebar-link>
        @endhasrole

    </div>
</aside>
