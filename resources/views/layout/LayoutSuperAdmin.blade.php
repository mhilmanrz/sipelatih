<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'siPELATIH')</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}?v={{ time() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="wrapper">

        <!-- SIDEBAR -->
        @include('layout.sidebar-legacy')

        <!-- MAIN -->
        <div class="main">

            <!-- TOPBAR -->
            @include('layout.topbar-legacy')

            <!-- CONTENT -->
            <div class="content" id="pageArea">
                @yield('content')
            </div>

        </div>

    </div>

    <footer class="footer">
        © 2026 — Saskya • Nina • Sandra • Hilman
    </footer>

    <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}?v={{ time() }}"></script>
    <!-- Jika ada fungsi toggle untuk profil yang bergantung pada js Pengusul -->
    <script>
        function toggleProfileMenu() {
            let menu = document.getElementById('profileMenu');
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
    </script>
    @stack('scripts')
</body>

</html>
