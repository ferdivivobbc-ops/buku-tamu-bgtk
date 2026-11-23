@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi Kunjungan')
@section('page_title', 'Laporan & Rekapitulasi Kunjungan')
@section('page_subtitle', 'Analisis data kunjungan tamu berdasarkan periode waktu, wilayah, dan tujuan')

@section('content')
<div class="space-y-6">

    <!-- Period Filter Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Filter Periode Laporan</h3>
            <p class="text-xs text-slate-400">Pilih tahun dan bulan untuk melihat analisis kunjungan</p>
        </div>

        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2">
            <select name="month" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none bg-white">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                </option>
                @endfor
            </select>

            <select name="year" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none bg-white">
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button type="submit" class="px-4 py-2 bg-bgtk-800 hover:bg-bgtk-900 text-white rounded-xl text-xs font-bold transition shadow">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Monthly Trend & Yearly Trend Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Trend of Selected Month -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Tren Kunjungan Harian</h4>
                    <p class="text-xs text-slate-400">Bulan {{ \Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }} {{ $year }}</p>
                </div>
                <span class="text-xs bg-amber-50 text-amber-900 border border-amber-200 px-2.5 py-1 rounded-lg font-bold">
                    Total: {{ $totalPeriod }} Tamu
                </span>
            </div>
            <div class="h-64">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trend of Selected Year -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div>
                <h4 class="text-sm font-bold text-slate-800">Tren Kunjungan Bulanan</h4>
                <p class="text-xs text-slate-400">Sepanjang Tahun {{ $year }}</p>
            </div>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Breakdown: City Distribution & Department Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- City / Regency Distribution -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-amber-500"></i> Sebaran Asal Kabupaten / Kota
                </h4>
            </div>
            <div class="overflow-x-auto max-h-80">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider sticky top-0">
                        <tr>
                            <th class="p-3">Kabupaten / Kota</th>
                            <th class="p-3 text-right">Jumlah Tamu</th>
                            <th class="p-3 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cityDistribution as $c)
                        @php
                            $pct = $totalPeriod > 0 ? round(($c->total / $totalPeriod) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-semibold text-slate-800">{{ $c->city_regency }}</td>
                            <td class="p-3 text-right font-bold text-bgtk-800">{{ $c->total }}</td>
                            <td class="p-3 text-right text-slate-500">{{ $pct }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-400">Belum ada data pada periode ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Department Distribution -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-sitemap text-bgtk-700"></i> Distribusi Kunjungan per Bagian
                </h4>
            </div>
            <div class="overflow-x-auto max-h-80">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider sticky top-0">
                        <tr>
                            <th class="p-3">Bagian / Tim Kerja</th>
                            <th class="p-3 text-right">Jumlah Tamu</th>
                            <th class="p-3 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($deptDistribution as $d)
                        @php
                            $pct = $totalPeriod > 0 ? round(($d->guests_count / $totalPeriod) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-semibold text-slate-800">{{ $d->name }}</td>
                            <td class="p-3 text-right font-bold text-bgtk-800">{{ $d->guests_count }}</td>
                            <td class="p-3 text-right text-slate-500">{{ $pct }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-400">Belum ada data pada periode ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // 1. Daily Chart
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    const dailyLabels = {!! json_encode(array_column($dailyData, 'day')) !!};
    const dailyCounts = {!! json_encode(array_column($dailyData, 'count')) !!};

    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Tamu Harian',
                data: dailyCounts,
                backgroundColor: '#0264b3',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { title: { display: true, text: 'Tanggal', font: { size: 10 } } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // 2. Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyLabels = {!! json_encode(array_column($monthlyData, 'month')) !!};
    const monthlyCounts = {!! json_encode(array_column($monthlyData, 'count')) !!};

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Tamu Bulanan',
                data: monthlyCounts,
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.15)',
                fill: true,
                tension: 0.3,
                borderWidth: 3,
                pointBackgroundColor: '#073c6c',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush
