<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - USB Cake Production</title>

    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons (Phosphor Icons) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Konfigurasi Kustomisasi Tema (Warna & Font) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        // Ganti warna utama di sini
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6', // Warna tombol/link utama
                            600: '#2563eb', // Hover state
                            700: '#1d4ed8',
                        },
                        // Warna sidebar
                        sidebar: '#1e293b', // Slate-800
                        sidebarHover: '#334155', // Slate-700
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar untuk Sidebar agar terlihat rapi */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }
    </style>

    <!-- Laravel CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <!-- Overlay untuk Mobile saat Sidebar terbuka -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity opacity-0"></div>

    @include('layouts.sidebar')

    <!-- MAIN CONTENT WRAPPER -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        @include('layouts.header')

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-4 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Include Components -->
    @include('components.modal')

    <!-- Flash Messages -->
    @if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
        @include('components.alert')
    @endif

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // 1. Sidebar Logic (Mobile)
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                // Open Sidebar
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.remove('opacity-0'), 10); // Fade in
            } else {
                // Close Sidebar
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => sidebarOverlay.classList.add('hidden'), 300); // Wait for fade out
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // 2. Dropdown Logic (Avatar)
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                const button = dropdown.previousElementSibling; // The button that toggles it
                if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            }
        });

        // 3. Submenu Sidebar Logic
        function toggleSubmenu(id, btn) {
            const submenu = document.getElementById(id);
            const arrow = btn.querySelector('.ph-caret-down');

            submenu.classList.toggle('hidden');

            if (submenu.classList.contains('hidden')) {
                arrow.classList.remove('rotate-180');
            } else {
                arrow.classList.add('rotate-180');
            }
        }

        // 4. Modal Logic
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[data-alert]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>