<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'siPELATIH')</title>
    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#0DBBCB] font-['Poppins'] text-gray-800 antialiased overflow-x-hidden min-h-screen" x-data="{ sidebarOpen: true }">

    <div class="flex relative w-full min-h-screen">
        
        <!-- SIDEBAR -->
        <x-layouts.sidebar />

        <!-- MAIN CONTENT WRAPPER -->
        <div 
            class="flex flex-col w-full transition-all duration-300 ease-in-out"
            :class="sidebarOpen ? 'ml-[240px]' : 'ml-0'"
        >
            
            <!-- TOPBAR -->
            <x-layouts.topbar />

            <!-- CONTENT -->
            <main class="flex-1 p-6 mt-[60px] transition-all duration-300 ease-in-out" @resize.window="$dispatch('resize')">
                {{ $slot }}
            </main>

            <!-- FOOTER -->
            <footer class="text-center p-3 text-sm text-white/80">
                © 2026 — Saskya • Nina • Sandra • Hilman
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
