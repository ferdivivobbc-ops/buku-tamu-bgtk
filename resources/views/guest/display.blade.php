<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Monitor Layar Kunjungan Tamu - BGTK Sumatera Barat</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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
                        kiosk: {
                            bg: '#041527',
                            card: '#0a233d',
                            border: '#14385f',
                            gold: '#f59e0b',
                            accent: '#0284c7',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-kiosk-bg text-slate-100 min-h-screen flex flex-col font-sans overflow-x-hidden selection:bg-amber-400 selection:text-slate-900">

    <!-- Top Monitor Bar -->
    <header class="bg-gradient-to-r from-slate-950 via-kiosk-card to-slate-950 border-b-2 border-amber-400 px-6 py-4 shadow-2xl">
        <div class="flex items-center justify-between gap-4">
            <!-- Left Branding -->
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-white rounded-2xl p-2 flex items-center justify-center text-slate-900 shadow-lg border-2 border-amber-400 shrink-0">
                    <svg class="w-10 h-10 text-sky-900" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zm0 8.7L5.5 8 12 4.7 18.5 8 12 10.7z"/>
                        <path d="M4 10.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7v-5.8l-8 4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-400 block">
                        Kementerian Pendidikan Dasar dan Menengah RI
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white uppercase">
                        Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat
                    </h1>
                    <p class="text-xs text-slate-300">
                        Monitor Informasi Pelayanan & Buku Tamu Front Office Lobby
                    </p>
                </div>
            </div>

            <!-- Right Digital Clock -->
            <div class="text-right bg-slate-900/80 px-5 py-2.5 rounded-2xl border border-kiosk-border shadow-inner">
                <div id="liveClock" class="text-2xl sm:text-3xl font-black font-mono tracking-wider text-amber-300">
                    --:--:-- WIB
                </div>
                <div id="liveDate" class="text-xs text-slate-300 font-semibold mt-0.5">
                    Memuat tanggal...
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-6 space-y-6">
        <!-- Top Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Total Hari Ini -->
            <div class="bg-kiosk-card/90 border border-kiosk-border rounded-2xl p-4 shadow-lg flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <span class="text-[11px] uppercase font-bold text-slate-400 block">Total Tamu</span>
                    <span class="text-2xl sm:text-3xl font-black text-white">{{ $totalToday }}</span>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-kiosk-card/90 border border-kiosk-border rounded-2xl p-4 shadow-lg flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <span class="text-[11px] uppercase font-bold text-amber-400 block">Menunggu</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-300">{{ $waitingToday }}</span>
                </div>
            </div>

            <!-- Sedang Bertemu -->
            <div class="bg-kiosk-card/90 border border-kiosk-border rounded-2xl p-4 shadow-lg flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <div>
                    <span class="text-[11px] uppercase font-bold text-sky-400 block">Sedang Bertemu</span>
                    <span class="text-2xl sm:text-3xl font-black text-sky-300">{{ $activeToday }}</span>
                </div>
            </div>

            <!-- Selesai -->
            <div class="bg-kiosk-card/90 border border-kiosk-border rounded-2xl p-4 shadow-lg flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <span class="text-[11px] uppercase font-bold text-emerald-400 block">Selesai</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-300">{{ $finishedToday }}</span>
                </div>
            </div>

            <!-- Rating IKM -->
            <div class="bg-kiosk-card/90 border border-kiosk-border rounded-2xl p-4 shadow-lg flex items-center space-x-4 col-span-2 md:col-span-1">
                <div class="w-12 h-12 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <span class="text-[11px] uppercase font-bold text-amber-400 block">Kepuasan (IKM)</span>
                    <span class="text-2xl sm:text-3xl font-black text-white">{{ number_format($avgRating, 1) }} <span class="text-xs font-normal text-slate-400">/ 5.0</span></span>
                </div>
            </div>
        </div>

        <!-- Table Today Guests -->
        <div class="bg-kiosk-card/80 border border-kiosk-border rounded-3xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-900/60 border-b border-kiosk-border flex items-center justify-between">
                <div class="flex items-center space-x-2 text-amber-400 font-extrabold text-sm uppercase tracking-wider">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Daftar Kunjungan Tamu Hari Ini</span>
                </div>
                <div class="text-xs text-slate-400 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Live Display (Auto Refresh 30 Detik)</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/90 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-kiosk-border">
                        <tr>
                            <th class="p-4">No. Tiket</th>
                            <th class="p-4">Nama Tamu & Jabatan</th>
                            <th class="p-4">Asal Instansi</th>
                            <th class="p-4">Kab / Kota</th>
                            <th class="p-4">Tujuan Bagian / Pejabat</th>
                            <th class="p-4 text-center">Waktu</th>
                            <th class="p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-kiosk-border">
                        @forelse($todayGuests as $guest)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="p-4 font-mono font-bold text-amber-400">
                                {{ $guest->ticket_number }}
                            </td>
                            <td class="p-4">
                                <div class="font-extrabold text-white text-base">{{ $guest->name }}</div>
                                <div class="text-xs text-slate-400">{{ $guest->position }}</div>
                            </td>
                            <td class="p-4 font-semibold text-slate-200">
                                {{ $guest->institution }}
                            </td>
                            <td class="p-4 text-slate-300">
                                {{ $guest->city_regency }}
                            </td>
                            <td class="p-4">
                                <div class="text-sky-300 font-bold">
                                    {{ $guest->department ? $guest->department->name : 'Layanan Terpadu' }}
                                </div>
                                @if($guest->destination_person)
                                <div class="text-xs text-slate-400">
                                    <i class="fa-solid fa-user-tag text-[10px] text-amber-400 mr-1"></i>{{ $guest->destination_person }}
                                </div>
                                @endif
                            </td>
                            <td class="p-4 text-center text-xs font-mono text-slate-300">
                                <div><i class="fa-solid fa-arrow-right-to-bracket text-emerald-400 mr-1"></i>{{ substr($guest->check_in_time, 0, 5) }}</div>
                                @if($guest->check_out_time)
                                <div class="text-rose-400 mt-0.5"><i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>{{ substr($guest->check_out_time, 0, 5) }}</div>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($guest->status === 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 animate-pulse">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Menunggu
                                    </span>
                                @elseif($guest->status === 'sedang_bertemu')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-500/20 text-sky-300 border border-sky-500/40">
                                        <i class="fa-solid fa-comments text-[10px]"></i> Bertemu
                                    </span>
                                @elseif($guest->status === 'selesai')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                        <i class="fa-solid fa-check text-[10px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300">
                                        {{ $guest->status_label }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                <i class="fa-solid fa-clipboard-user text-4xl mb-2 text-slate-600 block"></i>
                                Belum ada kunjungan tamu terdaftar untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Bottom Marquee Banner -->
    <footer class="bg-slate-950 border-t-2 border-amber-500 py-2.5 px-6 flex items-center overflow-hidden">
        <span class="bg-amber-400 text-slate-950 font-black text-xs px-3 py-1 rounded-lg uppercase tracking-wider mr-4 shrink-0 flex items-center gap-1.5">
            <i class="fa-solid fa-bullhorn"></i> Warta BGTK
        </span>
        <marquee class="text-sm font-semibold text-amber-200 tracking-wide">
            {{ $runningText ?? 'Selamat Datang di Balai Guru dan Tenaga Kependidikan (BGTK) Provinsi Sumatera Barat. Melayani dengan Sepenuh Hati, Cepat, dan Akuntabel.' }}
        </marquee>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('liveClock').textContent = `${hours}:${minutes}:${seconds} WIB`;

            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayName = days[now.getDay()];
            const dayNum = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            document.getElementById('liveDate').textContent = `${dayName}, ${dayNum} ${monthName} ${year}`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
