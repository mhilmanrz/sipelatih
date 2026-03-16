    <div class="bg-dark border-end" id="sidebar-wrapper" style="width: 250px; min-height: calc(100vh - 56px);">
    <div class="list-group list-group-flush pt-3">
        <!-- COMMON MENUS FOR ALL USERS (PENGUSUL & ADMIN) -->
        <a href="{{ url('/') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary">
            <i class="fa-solid fa-house fa-fw me-2"></i> Dashboard
        </a>
        <a href="{{ url('/usulan-diklat') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('usulan-diklat*') ? 'active' : '' }}">
            <i class="fa-solid fa-folder fa-fw me-2"></i> Usulan Diklat
        </a>
        <a href="{{ url('/monitoring-jpl') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('monitoring-jpl*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line fa-fw me-2"></i> Monitoring JPL
        </a>

        <!-- MANAJEMEN SECTION (PENGUSUL & ADMIN) -->
        <div class="px-3 pt-3 pb-2 text-secondary text-uppercase small font-weight-bold">
            Manajemen
        </div>
        <a href="{{ url('/users') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('users*') ? 'active' : '' }}">
            <i class="fa-solid fa-id-card fa-fw me-2"></i> Manajemen Pegawai
        </a>
        <a href="{{ url('/sasaran-profesi') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('sasaran-profesi*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear fa-fw me-2"></i> Manajemen Sasaran Profesi
        </a>

        <!-- SUPER ADMIN EXCLUSIVE MENUS -->
        @if(auth()->check() && auth()->user()->role === 'superadmin')
        <div class="px-3 pt-3 pb-2 text-secondary text-uppercase small font-weight-bold">
            Evaluasi & Laporan (Admin)
        </div>
        <a href="{{ url('/pagu') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('pagu*') ? 'active' : '' }}">
            <i class="fa-solid fa-money-bill fa-fw me-2"></i> Pagu
        </a>
        <a href="{{ url('/input-nilai') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('input-nilai*') ? 'active' : '' }}">
            <i class="fa-solid fa-pen-to-square fa-fw me-2"></i> Input Nilai
        </a>
        <a href="{{ url('/laporan-kegiatan') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('laporan-kegiatan*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice fa-fw me-2"></i> Laporan Kegiatan
        </a>
        <a href="{{ url('/evaluasi-1') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('evaluasi-1*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-check fa-fw me-2"></i> Evaluasi I
        </a>
        <a href="{{ url('/evaluasi-2') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('evaluasi-2*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-check fa-fw me-2"></i> Evaluasi II
        </a>
        <a href="{{ url('/evaluasi-3') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('evaluasi-3*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-check fa-fw me-2"></i> Evaluasi III
        </a>
        <a href="{{ url('/bank-data') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('bank-data*') ? 'active' : '' }}">
            <i class="fa-solid fa-database fa-fw me-2"></i> Bank Data
        </a>

        <!-- Master Data -->
        <div class="px-3 pt-3 pb-2 text-secondary text-uppercase small font-weight-bold">
            Master Data (Admin)
        </div>
        <a href="{{ url('/professions') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('professions*') ? 'active' : '' }}">
            <i class="fa-solid fa-briefcase fa-fw me-2"></i> Profesi
        </a>
        <a href="{{ url('/work-units') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('work-units*') ? 'active' : '' }}">
            <i class="fa-solid fa-building fa-fw me-2"></i> Unit Kerja
        </a>
        <a href="{{ url('/positions') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('positions*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-tag fa-fw me-2"></i> Posisi
        </a>
        <a href="{{ url('/activity-types') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('activity-types*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags fa-fw me-2"></i> Jenis Kegiatan
        </a>
        <a href="{{ url('/activity-scopes') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary {{ request()->is('activity-scopes*') ? 'active' : '' }}">
            <i class="fa-solid fa-globe fa-fw me-2"></i> Ruang Lingkup
        </a>
        @endif
    </div>
</div>
