@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Eksekutif Buku Tamu')
@section('page_subtitle', 'Ringkasan statistik kunjungan tamu BGTK Provinsi Sumatera Barat')

@section('content')
<div class="space-y-6">

    <!-- Counter Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Tamu Hari Ini -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Tamu Hari Ini</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalToday }}</div>
                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-1">
                    <span class="text-amber-600 font-semibold">{{ $waitingCount }} Menunggu</span> &bull; 
                    <span class="text-sky-600 font-semibold">{{ $activeCount }} Bertemu</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-bgtk-50 text-bgtk-700 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Tamu Bulan Ini -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Kunjungan Bulan Ini</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalMonth }}</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <!-- Total Tamu Terdaftar -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Keseluruhan</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalAll }}</div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Seluruh riwayat tamu
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-database"></i>
            </div>
        </div>

        <!-- Indeks Kepuasan (IKM) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600 block">Indeks Kepuasan (IKM)</span>
                <div class="text-3xl font-black text-slate-900 mt-1">
                    {{ number_format($avgRating, 1) }} <span class="text-sm font-semibold text-slate-400">/ 5.0</span>
                </div>
                <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                    <i class="fa-solid fa-face-smile"></i> {{ $satisfactionPercentage }}% Respon Sangat Puas
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 7-Day Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tren Kunjungan 7 Hari Terakhir</h3>
                    <p class="text-xs text-slate-400">Statistik fluktuasi kedatangan tamu harian</p>
                </div>
                <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg font-medium">Harian</span>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Department Share Doughnut -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Tujuan Bagian / Tim Kerja</h3>
                <p class="text-xs text-slate-400">Distribusi kunjungan berdasarkan bagian</p>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    <!-- City / Regency Top Origins -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Asal Kabupaten / Kota Terbanyak</h3>
                <p class="text-xs text-slate-400">Sebaran daerah asal pengunjung di Sumatera Barat</p>
            </div>
            <div class="h-64">
                <canvas id="cityChart"></canvas>
            </div>
        </div>

        <!-- Recent Guests Today Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Kunjungan Hari Ini</h3>
                        <p class="text-xs text-slate-400">Tindak lanjuti status kedatangan tamu</p>
                    </div>
                    <a href="{{ route('admin.guests.index') }}" class="text-xs text-bgtk-700 hover:text-bgtk-900 font-bold">
                        Lihat Semua <i class="fa-solid fa-arrow-right text-[10px] ml-0.5"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="p-3.5">Tiket & Tamu</th>
                                <th class="p-3.5">Asal Instansi</th>
                                <th class="p-3.5">Bagian Dituju</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentGuests as $guest)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="p-3.5">
                                    <div class="font-mono font-bold text-bgtk-800">{{ $guest->ticket_number }}</div>
                                    <div class="font-semibold text-slate-900">{{ $guest->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ substr($guest->check_in_time, 0, 5) }} WIB</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-medium text-slate-800">{{ $guest->institution }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $guest->city_regency }}</div>
                                </td>
                                <td class="p-3.5">
                                    <span class="font-semibold text-bgtk-700">{{ $guest->department ? $guest->department->name : '-' }}</span>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $guest->status_badge_class }}">
                                        {{ $guest->status_label }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                                    @if($guest->status === 'menunggu')
                                    <form action="{{ route('admin.guests.status', $guest) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="sedang_bertemu">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-[11px]" title="Tandai Sedang Bertemu">
                                            <i class="fa-solid fa-comments mr-1"></i> Terima
                                        </button>
                                    </form>
                                    @elseif($guest->status === 'sedang_bertemu')
                                    <form action="{{ route('admin.guests.status', $guest) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px]" title="Tandai Selesai">
                                            <i class="fa-solid fa-check mr-1"></i> Selesai
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('admin.guests.show', $guest) }}" 
                                       class="px-2.5 py-1 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold text-[11px]">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400">
                                    Belum ada tamu yang berkunjung hari ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // 1. Trend Line Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendLabels = {!! json_encode($sevenDays->pluck('date')) !!};
    const trendData = {!! json_encode($sevenDays->pluck('count')) !!};

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Jumlah Tamu',
                data: trendData,
                borderColor: '#0264b3',
                backgroundColor: 'rgba(2, 100, 179, 0.1)',
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointBackgroundColor: '#d4af37',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Department Share Doughnut Chart
    const deptCtx = document.getElementById('deptChart').getContext('2d');
    const deptLabels = {!! json_encode($deptStats->pluck('name')->map(fn($n) => \Illuminate\Support\Str::limit($n, 18))) !!};
    const deptData = {!! json_encode($deptStats->pluck('guests_count')) !!};

    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: deptLabels,
            datasets: [{
                data: deptData,
                backgroundColor: ['#073c6c', '#0284c7', '#d4af37', '#10b981', '#8b5cf6', '#f97316'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10, font: { size: 10 } }
                }
            }
        }
    });

    // 3. City/Regency Bar Chart
    const cityCtx = document.getElementById('cityChart').getContext('2d');
    const cityLabels = {!! json_encode($cityStats->pluck('city_regency')) !!};
    const cityData = {!! json_encode($cityStats->pluck('total')) !!};

    new Chart(cityCtx, {
        type: 'bar',
        data: {
            labels: cityLabels,
            datasets: [{
                label: 'Tamu',
                data: cityData,
                backgroundColor: '#d4af37',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endpush
