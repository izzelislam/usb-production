<!-- SIDEBAR -->
<aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 bg-sidebar text-white transition-transform transform -translate-x-full lg:translate-x-0 flex flex-col">
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-6 border-b border-gray-700">
        <i class="ph ph-hexagon text-3xl text-primary-500 mr-2"></i>
        <span class="text-xl font-bold tracking-wide">USB</span>
    </div>

    <!-- Menu Items (Scrollable Area) -->
    <nav class="flex-1 overflow-y-auto custom-scroll py-4 px-3 space-y-1">

        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors">
            <i class="ph ph-squares-four text-xl mr-3"></i>
            Dashboard
        </a>

        <!-- Production Menu -->
        <div class="relative group-menu">
            <button onclick="toggleSubmenu('submenu-productions', this)" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('productions*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} transition-colors">
                <div class="flex items-center">
                    <i class="ph ph-factory text-xl mr-3"></i>
                    Produksi
                </div>
                <i class="ph ph-caret-down transition-transform duration-200 {{ request()->routeIs('productions*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="submenu-productions" class="pl-10 space-y-1 mt-1 {{ request()->routeIs('productions*') ? '' : 'hidden' }}">
                <a href="{{ route('productions.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('productions.index') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Daftar Produksi</a>
                <a href="{{ route('productions.worker_summary') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('productions.worker_summary') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Produksi Karyawan</a>
            </div>
        </div>

        <!-- Payroll Menu -->
        <div class="relative group-menu">
            <button onclick="toggleSubmenu('submenu-payrolls', this)" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('payrolls*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} transition-colors">
                <div class="flex items-center">
                    <i class="ph ph-wallet text-xl mr-3"></i>
                    Penggajian
                </div>
                <i class="ph ph-caret-down transition-transform duration-200 {{ request()->routeIs('payrolls*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="submenu-payrolls" class="pl-10 space-y-1 mt-1 {{ request()->routeIs('payrolls*') ? '' : 'hidden' }}">
                <a href="{{ route('payrolls.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('payrolls.index') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Daftar Gaji</a>
                <a href="{{ route('payrolls.create') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('payrolls.create') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Input Gaji</a>
            </div>
        </div>

        <!-- Purchases Menu -->
        <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors">
            <i class="ph ph-shopping-cart text-xl mr-3"></i>
            Pembelian
        </a>

        <!-- Sales Menu -->
        <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors">
            <i class="ph ph-shopping-bag text-xl mr-3"></i>
            Penjualan
        </a>

        <!-- Master Data Menu -->
        <p class="pt-4 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Master Data</p>
        <!-- Employees Menu -->
        <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors">
            <i class="ph ph-users text-xl mr-3"></i>
            Karyawan
        </a>

        <!-- Vendors Menu -->
        <a href="{{ route('vendors.index') }}" class="{{ request()->routeIs('vendors*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors">
            <i class="ph ph-storefront text-xl mr-3"></i>
            Supplier
        </a>

        <!-- Items Menu -->
        <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors">
            <i class="ph ph-package text-xl mr-3"></i>
            Produk
        </a>

        <!-- Reports Menu -->
        <p class="pt-4 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Laporan</p>

        <div class="relative group-menu">
            <button onclick="toggleSubmenu('submenu-reports', this)" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('reports*') ? 'bg-sidebarHover text-white' : 'text-gray-300 hover:bg-sidebarHover hover:text-white' }} transition-colors">
                <div class="flex items-center">
                    <i class="ph ph-file-text text-xl mr-3"></i>
                    Laporan
                </div>
                <i class="ph ph-caret-down transition-transform duration-200 {{ request()->routeIs('reports*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="submenu-reports" class="pl-10 space-y-1 mt-1 {{ request()->routeIs('reports*') ? '' : 'hidden' }}">
                <a href="{{ route('reports.production') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('reports.production') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Laporan Produksi</a>
                <a href="{{ route('reports.payroll') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('reports.payroll') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Laporan Gaji</a>
                <a href="{{ route('reports.purchase') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('reports.purchase') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Laporan Pembelian</a>
                <a href="{{ route('reports.export') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('reports.export') ? 'text-white bg-sidebarHover' : 'text-gray-400 hover:text-white hover:bg-sidebarHover' }}">Export Data</a>
            </div>
        </div>

    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}" onsubmit="event.preventDefault(); this.submit();">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2 rounded-lg text-gray-300 hover:bg-red-500/10 hover:text-red-400 transition-colors">
                <i class="ph ph-sign-out text-xl mr-3"></i>
                Log Out
            </button>
        </form>
    </div>
</aside>