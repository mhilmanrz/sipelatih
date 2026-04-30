<header class="h-[60px] bg-white flex items-center justify-between px-6 fixed top-0 right-0 z-[9000] shadow-sm transition-all duration-300"
    :class="sidebarOpen && !isMobile ? 'left-[240px]' : 'left-0'"
>
    <!-- Toggle Sidebar Button -->
    <button @click="sidebarOpen = !sidebarOpen; setTimeout(() => window.dispatchEvent(new Event('resize')), 300)" class="text-gray-600 hover:text-black focus:outline-none">
        <i class="fa fa-bars text-xl"></i>
    </button>

    <!-- Profile Area -->
    <div class="relative flex items-center gap-3" x-data="{ profileMenuOpen: false }">
        <div class="flex items-center gap-2 px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-full cursor-pointer select-none transition-colors" @click="profileMenuOpen = !profileMenuOpen" @click.away="profileMenuOpen = false">
            <i class="fa fa-user-circle text-gray-700 text-lg"></i>
            <span class="text-sm font-medium text-black">
                @if(Auth::check())
                    {{ Auth::user()->name }}
                @else
                    Guest
                @endif
            </span>
            <i class="fa fa-chevron-down text-gray-600 text-xs ml-1"></i>
        </div>

        <!-- Dropdown Menu -->
        <div x-show="profileMenuOpen" 
             x-transition.opacity
             class="absolute right-0 top-12 bg-white rounded-lg shadow-lg min-w-[150px] overflow-hidden z-[99]"
             style="display: none;">
             
             <!-- Logged in items -->
             @if(Auth::check())
                <a href="#" class="block px-4 py-2.5 text-sm text-gray-800 bg-gray-200 hover:bg-gray-300 hover:text-black transition-colors">
                    <i class="fa fa-user mr-2"></i> Profile
                </a>
                
                <!-- Assuming a generic logout route for now -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2.5 text-sm text-gray-800 bg-gray-200 hover:bg-gray-300 hover:text-black transition-colors border-t border-gray-300">
                        <i class="fa fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
             @else
                <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm text-gray-800 bg-gray-200 hover:bg-gray-300 hover:text-black transition-colors">
                    <i class="fa fa-sign-in-alt mr-2"></i> Login
                </a>
             @endif
        </div>
    </div>
</header>
