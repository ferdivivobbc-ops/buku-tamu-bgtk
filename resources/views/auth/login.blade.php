<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Petugas & Admin - Buku Tamu BGTK Sumatera Barat</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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
                            800: '#073c6c',
                            900: '#0b3259',
                            dark: '#072442',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-bgtk-900 to-slate-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Logo Emblem -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-xl p-2 mx-auto flex items-center justify-center text-bgtk-900 border-2 border-amber-400 mb-3">
                <svg class="w-12 h-12 text-bgtk-800" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7l10 5 10-5-10-5zm0 8.7L5.5 8 12 4.7 18.5 8 12 10.7z"/>
                    <path d="M4 10.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7v-5.8l-8 4-8-4z"/>
                </svg>
            </div>
            <span class="text-xs font-bold uppercase tracking-widest text-amber-300 block">KEMENDIKDASMEN RI</span>
            <h1 class="text-2xl font-black text-white tracking-tight">BGTK Sumatera Barat</h1>
            <p class="text-xs text-slate-300 mt-0.5">Portal Masuk Petugas & Administrator Buku Tamu</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-8 space-y-6">
                <!-- Session Alerts -->
                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl text-xs flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>
                    <div>
                        @foreach($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Username atau Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user-shield"></i>
                            </span>
                            <input type="text" name="login" id="login" required autofocus value="{{ old('login') }}"
                                   placeholder="Masukkan username atau email..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-800 focus:border-bgtk-800 text-sm outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                   placeholder="Masukkan password Anda..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-800 focus:border-bgtk-800 text-sm outline-none transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-bgtk-800 rounded border-slate-300">
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-bgtk-900 to-bgtk-800 hover:from-slate-900 hover:to-bgtk-900 text-white font-bold text-sm shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Masuk ke Panel Petugas</span>
                    </button>
                </form>

                <div class="text-center pt-2">
                    <a href="{{ route('guest.create') }}" class="text-xs text-slate-500 hover:text-bgtk-800 font-semibold flex items-center justify-center gap-1.5 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Halaman Pengunjung / Kiosk</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
