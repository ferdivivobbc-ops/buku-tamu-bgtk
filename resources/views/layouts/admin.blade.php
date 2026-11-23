<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Buku Tamu BGTK Sumatera Barat</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        bgtk: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            600: '#0264b3',
                            700: '#034c8a',
                            800: '#073c6c',
                            900: '#0b3259',
                            dark: '#072442',
                        },
                        minang: {
                            gold: '#d4af37',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-bgtk-dark text-white flex-shrink-0 flex flex-col justify-between shadow-xl border-r border-slate-800 z-40">
        <div>
            <!-- Sidebar Header Brand -->
            <div class="p-5 border-b border-slate-800 flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-xl p-1 flex items-center justify-center text-bgtk-900 border-2 border-amber-400 shrink-0">
                    <svg class="w-7 h-7 text-bgtk-800" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zm0 8.7L5.5 8 12 4.7 18.5 8 12 10.7z"/>
                        <path d="M4 10.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7v-5.8l-8 4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-amber-300 block">KEMENDIKDASMEN RI</span>
                    <h2 class="font-extrabold text-sm text-white leading-tight">BGTK Sumbar</h2>
                    <span class="text-[10px] text-slate-400">Panel Manajemen</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs font-semibold">
                <div class="text-[10px] uppercase font-bold text-slate-400 px-3 py-2 tracking-wider">
                    Menu Utama
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-bgtk-dark font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Buku Tamu -->
                <a href="{{ route('admin.guests.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.guests.*') ? 'bg-amber-400 text-bgtk-dark font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-address-book w-4 text-center"></i>
                    <span>Buku Tamu</span>
                </a>

                <!-- Bagian / Tim Kerja -->
                <a href="{{ route('admin.departments.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.departments.*') ? 'bg-amber-400 text-bgtk-dark font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-sitemap w-4 text-center"></i>
                    <span>Bagian / Tim Kerja</span>
                </a>

                <div class="text-[10px] uppercase font-bold text-slate-400 px-3 pt-4 pb-2 tracking-wider">
                    Laporan & Evaluasi
                </div>

                <!-- Laporan Kunjungan -->
                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.index') ? 'bg-amber-400 text-bgtk-dark font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-4 text-center"></i>
                    <span>Rekap Kunjungan</span>
                </a>

                <!-- Survei Kepuasan IKM -->
                <a href="{{ route('admin.reports.satisfaction') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.satisfaction') ? 'bg-amber-400 text-bgtk-dark font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-star-half-stroke w-4 text-center"></i>
                    <span>Survei Kepuasan (IKM)</span>
                </a>

                <div class="text-[10px] uppercase font-bold text-slate-400 px-3 pt-4 pb-2 tracking-wider">
                    Sistem
                </div>

                <!-- Pengaturan -->
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-amber-400 text-bgtk-dark font-bold shadow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-4 text-center"></i>
                    <span>Pengaturan Instansi</span>
                </a>

                <div class="pt-4 border-t border-slate-800 mt-4 space-y-1">
                    <!-- Buka Form Tamu Kiosk -->
                    <a href="{{ route('guest.create') }}" target="_blank"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-amber-300 hover:bg-white/10 transition">
                        <i class="fa-solid fa-pen-nib w-4 text-center"></i>
                        <span>Buka Kiosk Tamu <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i></span>
                    </a>

                    <!-- Buka TV Display -->
                    <a href="{{ route('guest.display') }}" target="_blank"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-sky-300 hover:bg-white/10 transition">
                        <i class="fa-solid fa-tv w-4 text-center"></i>
                        <span>Buka Layar TV <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i></span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer / User Profile -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-amber-400 text-bgtk-dark font-black flex items-center justify-center text-xs shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Petugas' }}</div>
                        <div class="text-[10px] text-amber-400 uppercase tracking-wider">{{ Auth::user()->role ?? 'petugas' }}</div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 transition" title="Keluar / Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar Header -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div>
                <h1 class="text-lg font-extrabold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                <p class="text-xs text-slate-500">@yield('page_subtitle', 'Sistem Administrasi Tamu BGTK Sumatera Barat')</p>
            </div>

            <div class="flex items-center space-x-3">
                <span class="text-xs text-slate-500 hidden sm:inline font-medium">
                    <i class="fa-regular fa-calendar-check mr-1 text-amber-500"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>

                <a href="{{ route('admin.guests.export') }}" 
                   class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-file-excel text-emerald-600"></i>
                    <span class="hidden sm:inline">Export Excel/CSV</span>
                </a>

                <a href="{{ route('admin.guests.print') }}" target="_blank"
                   class="px-3 py-1.5 rounded-lg bg-bgtk-800 hover:bg-bgtk-900 text-white text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                    <i class="fa-solid fa-print"></i>
                    <span class="hidden sm:inline">Cetak Laporan</span>
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-6 space-y-6">
            <!-- Flash Message Alerts -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl shadow-sm text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl shadow-sm text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
