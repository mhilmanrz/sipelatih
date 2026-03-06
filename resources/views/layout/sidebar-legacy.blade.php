<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="{{ asset('assets/images/logo-sipelatih.png') }}" width="180">
        <small>RSUPN Dr. Cipto Mangunkusumo</small>
    </div>

    <div class="menu">
        <a href="{{ url('/') }}">
            <i class="fa fa-home"></i> Dashboard
        </a>

        <a href="{{ url('/usulan-diklat') }}">
            <i class="fa fa-folder"></i> Usulan Diklat
        </a>

        <!-- Menu Khusus SuperAdmin -->
        @hasrole('SuperAdmin')
            <a href="{{ route('users.index') }}">
                <i class="fa-solid fa-id-card"></i> Manajemen Pegawai
            </a>

            <a href="{{ route('positions.index') }}">
                <i class="fa-solid fa-user-tie"></i> Manajemen Jabatan
            </a>

            <a href="{{ url('/manajemen-sasaran-profesi') }}">
                <i class="fa-solid fa-user-gear"></i> Manajemen Sasaran Profesi
            </a>

            <a href="{{ url('/pagu') }}">
                <i class="fa fa-chart-line"></i> Pagu
            </a>
            <a href="{{ url('/input-nilai') }}">
                <i class="fa fa-chart-line"></i> Input Nilai
            </a>
            <a href="{{ url('/laporan-kegiatan') }}">
                <i class="fa fa-chart-line"></i> Laporan Kegiatan
            </a>
            <a href="{{ url('/evaluasi1') }}">
                <i class="fa fa-chart-line"></i> Evaluasi I
            </a>
            <a href="{{ url('/evaluasi2') }}">
                <i class="fa fa-chart-line"></i> Evaluasi II
            </a>
            <a href="{{ url('/evaluasi3') }}">
                <i class="fa fa-chart-line"></i> Evaluasi III
            </a>
            <a href="{{ url('/bank-data') }}">
                <i class="fa-solid fa-briefcase"></i> Bank Data
            </a>
        @endhasrole

        <!-- Menu Universal / Pengusul -->
        <a href="{{ url('/monitoring-jpl') }}">
            <i class="fa fa-chart-line"></i> Monitoring JPL
        </a>

        @hasrole('Pengusul')
            <a href="{{ route('users.index') }}">
                <i class="fa-solid fa-id-card"></i> Management Pegawai
            </a>
            <a href="{{ url('/manajemen-sasaran-profesi') }}">
                <i class="fa-solid fa-briefcase"></i> Management Sasaran profesi
            </a>
        @endhasrole

    </div>
</div>
