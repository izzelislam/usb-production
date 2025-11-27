<!-- NAVBAR -->
<header class="h-16 bg-white shadow-sm sticky top-0 z-40 px-4 lg:px-8 flex items-center justify-between">
    <!-- Left: Toggle & Title -->
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">
            <i class="ph ph-list text-2xl"></i>
        </button>
        <h2 class="text-xl font-bold text-gray-800 hidden sm:block">@yield('page-title', 'Dashboard Overview')</h2>
    </div>

    <!-- Right: Actions & Avatar -->
    <div class="flex items-center gap-4">
        <!-- Notification Icon -->
        <button class="p-2 relative rounded-full text-gray-500 hover:bg-gray-100">
            <i class="ph ph-bell text-xl"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>

        <!-- Avatar Dropdown -->
        <div class="relative">
            <button onclick="toggleDropdown('user-dropdown')" class="flex items-center gap-2 focus:outline-none">
                <img src="https://i.pravatar.cc/150?img=12" alt="User" class="w-9 h-9 rounded-full border border-gray-200 object-cover">
                <div class="hidden md:block text-left">
                    <p class="text-sm font-semibold text-gray-700">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::check() ? Auth::user()->email : 'Not logged in' }}</p>
                </div>
                <i class="ph ph-caret-down text-gray-400 text-sm hidden md:block"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-100 hidden transition-all duration-200 origin-top-right z-50">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600">Profil Saya</a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600">Pengaturan Akun</a>
                <div class="border-t border-gray-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}" onsubmit="event.preventDefault(); this.submit();">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>