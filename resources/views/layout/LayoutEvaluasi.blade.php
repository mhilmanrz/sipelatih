<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/LayoutEvaluasi.css') }}">

    @stack('styles')
</head>
<body>

<div class="wrapper">
<div class="overlay" onclick="closeSidebar()"></div>
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="{{ asset('images/logo-sipelatih.png') }}" width="180">
            <small>RSUPN Dr. Cipto Mangunkusumo</small>
        </div>

        <div class="menu">

            <a href="{{ route('dashboard.pengusul') }}">
                <i class="fa fa-home"></i> Dashboard
            </a>

            <a href="{{ route('usulan.diklat') }}">
                <i class="fa fa-folder"></i> Usulan Diklat
            </a>

            <a href="{{ route('monitoring.jpl') }}">
                <i class="fa fa-chart-line"></i> Monitoring JPL
            </a>

            <a href="{{ route('laporan.kegiatan') }}">
                <i class="fa-solid fa-file-lines"></i> Laporan Kegiatan
            </a>

            <a href="{{ route('evaluasi.1') }}">
                <i class="fa-solid fa-clipboard-check"></i> Evaluasi I
            </a>

            <a href="{{ route('evaluasi.2') }}">
                <i class="fa-solid fa-square-poll-vertical"></i> Evaluasi II
            </a>

            <a href="{{ route('evaluasi.3') }}">
                <i class="fa-solid fa-list-check"></i> Evaluasi III
            </a>

            <a href="{{ route('bank.data') }}">
                <i class="fa-solid fa-database"></i> Bank Data
            </a>

        </div>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar" id="topbar">
            <i class="menu-toggle" onclick="toggleSidebar()">☰</i>

            <div class="profile-area">
                <i class="fa fa-bell"></i>

                <span class="user-badge" onclick="toggleProfileMenu()">
                    <i class="fa fa-user"></i> Diklat
                </span>

                <div class="profile-menu" id="profileMenu">
                    <a href="{{ route('ubah.password') }}">🔑 Ubah Password</a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;">
                            🚪 Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content" id="pageArea">
            @yield('content')
        </div>

    </div>
</div>

<footer class="footer">
    © {{ date('Y') }} Diklat RSUPN Dr. Cipto Mangunkusumo. All rights reserved.
</footer>

<script src="{{ asset('js/LayoutEvaluasi.js') }}"></script>
@stack('scripts')
<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('show');
    document.querySelector('.overlay').classList.toggle('active');
}

function closeSidebar(){
    document.querySelector('.sidebar').classList.remove('show');
    document.querySelector('.overlay').classList.remove('active');
}

/* Auto close saat resize ke desktop */
window.addEventListener('resize', function(){
    if(window.innerWidth > 768){
        closeSidebar();
    }
});
</script>
</body>
</html>