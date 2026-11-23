<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Buku Tamu Digital') - Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
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
                            200: '#bae0fd',
                            600: '#0264b3',
                            700: '#034c8a',
                            800: '#073c6c',
                            900: '#0b3259',
                            dark: '#072442',
                        },
                        minang: {
                            gold: '#d4af37',
                            goldLight: '#f9df7b',
                            red: '#991b1b',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #0b3259;
            background-image: radial-gradient(rgba(212, 175, 55, 0.15) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">
    <!-- Header Kiosk BGTK Sumbar -->
    <header class="bg-gradient-to-r from-bgtk-900 via-bgtk-800 to-bgtk-700 text-white shadow-lg sticky top-0 z-50 border-b-4 border-minang-gold">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <!-- Logo & Brand Title -->
                <div class="flex items-center space-x-3.5 text-center sm:text-left">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-md p-1.5 flex items-center justify-center flex-shrink-0 border-2 border-amber-300">
                        <!-- Tut Wuri Handayani / Kemdikbud Vector Emblem -->
                        <svg class="w-9 h-9 text-bgtk-800" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zm0 8.7L5.5 8 12 4.7 18.5 8 12 10.7z"/>
                            <path d="M4 10.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7v-5.8l-8 4-8-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-amber-300">
                            Kementerian Pendidikan Dasar & Menengah RI
                        </div>
                        <h1 class="text-base sm:text-lg font-extrabold tracking-tight leading-tight">
                            Buku Tamu BGTK Sumatera Barat
                        </h1>
                        <p class="text-[11px] text-slate-200 hidden sm:block">
                            Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat
                        </p>
                    </div>
                </div>

                <!-- Navigation Buttons & Live Clock -->
                <div class="flex items-center flex-wrap justify-center gap-2">
                    <a href="{{ route('guest.create') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition {{ request()->routeIs('guest.create') ? 'bg-amber-400 text-bgtk-900 shadow' : 'bg-white/10 hover:bg-white/20 text-white' }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Isi Buku Tamu</span>
                    </a>

                    <a href="{{ route('guest.checkout') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition {{ request()->routeIs('guest.checkout') ? 'bg-amber-400 text-bgtk-900 shadow' : 'bg-white/10 hover:bg-white/20 text-white' }}">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Checkout / IKM</span>
                    </a>

                    <a href="{{ route('guest.display') }}" target="_blank"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white flex items-center gap-1.5 transition"
                       title="Buka Layar Monitor TV Kiosk">
                        <i class="fa-solid fa-tv"></i>
                        <span class="hidden md:inline">Layar TV</span>
                    </a>

                    <a href="{{ route('guest.qr-display') }}" target="_blank"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white/10 hover:bg-white/20 text-white flex items-center gap-1.5 transition"
                       title="QR besar dan unduhan untuk flashdisk">
                        <i class="fa-solid fa-qrcode"></i>
                        <span class="hidden md:inline">QR Flashdisk</span>
                    </a>

                    <a href="{{ route('login') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-amber-500 hover:bg-amber-400 text-bgtk-950 flex items-center gap-1.5 transition shadow">
                        <i class="fa-solid fa-lock"></i>
                        <span>Petugas</span>
                    </a>
                </div>
            </div>
        </div>

        @if(isset($runningText) && $runningText)
        <!-- Running Text Announcement -->
        <div class="bg-bgtk-950/80 text-amber-200 text-xs py-1 px-4 border-t border-bgtk-700/50 flex items-center overflow-hidden">
            <span class="inline-flex items-center gap-1 bg-amber-500 text-bgtk-950 font-bold px-2 py-0.5 rounded text-[10px] uppercase mr-3 shrink-0">
                <i class="fa-solid fa-bullhorn"></i> Pengumuman
            </span>
            <marquee class="font-medium tracking-wide">{{ $runningText }}</marquee>
        </div>
        @endif
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        <!-- Flash Alert Messages -->
        @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 mt-6">
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-4xl mx-auto px-4 mt-6">
            <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl shadow-sm flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-xl mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">
                    {{ session('error') }}
                </div>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-6 border-t border-slate-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-bgtk-800 text-amber-300 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-200">Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat</p>
                    <p class="text-[11px] text-slate-400">{{ \App\Models\Setting::get('address', 'Jl. Dewi Sartika, Rawang, Kec. Pariaman Tengah - Kota Pariaman') }}</p>
                </div>
            </div>

            <div class="text-center md:text-right text-[11px]">
                <p>&copy; {{ date('Y') }} BGTK Sumatera Barat. Hak Cipta Dilindungi.</p>
                <p class="text-slate-500 mt-0.5">Sistem Pelayanan & Buku Tamu Digital Front Office</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
