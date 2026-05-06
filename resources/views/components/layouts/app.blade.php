<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? $appSettings?->get('app_name', 'siPELATIH') ?? 'siPELATIH' }}</title>
    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-brand-secondary font-['Poppins'] text-gray-800 antialiased overflow-x-hidden min-h-screen"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          isMobile: window.innerWidth < 1024,
          handleResize() {
              let wasMobile = this.isMobile;
              this.isMobile = window.innerWidth < 1024;
              if (wasMobile && !this.isMobile) this.sidebarOpen = true;
              if (!wasMobile && this.isMobile) this.sidebarOpen = false;
          }
      }"
      @resize.window.debounce.200ms="handleResize()">

    <div class="flex relative w-full min-h-screen">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen && isMobile"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-[990] lg:hidden"
             @click="sidebarOpen = false"></div>

        <!-- SIDEBAR -->
        <x-layouts.sidebar />

        <!-- MAIN CONTENT WRAPPER -->
        <div
            class="flex flex-col transition-all duration-300 ease-in-out min-w-0"
            :class="sidebarOpen && !isMobile ? 'ml-[240px] w-[calc(100%-240px)]' : 'w-full'"
        >

            <!-- TOPBAR -->
            <x-layouts.topbar />

            <!-- CONTENT -->
            <main class="flex-1 p-6 mt-[60px] transition-all duration-300 ease-in-out">
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
