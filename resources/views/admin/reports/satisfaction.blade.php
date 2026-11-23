@extends('layouts.admin')

@section('title', 'Laporan Survei Kepuasan (IKM)')
@section('page_title', 'Indeks Kepuasan Masyarakat (IKM)')
@section('page_subtitle', 'Analisis mutu pelayanan publik dan hasil survei kepuasan tamu BGTK Sumatera Barat')

@section('content')
<div class="space-y-6">

    <!-- Filter Year -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Evaluasi Mutu Pelayanan</h3>
            <p class="text-xs text-slate-400">Berdasarkan penilaian kepuasan pengunjung saat checkout</p>
        </div>

        <form action="{{ route('admin.reports.satisfaction') }}" method="GET" class="flex items-center gap-2">
            <select name="year" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none bg-white">
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-4 py-2 bg-bgtk-800 hover:bg-bgtk-900 text-white rounded-xl text-xs font-bold transition shadow">
                Filter
            </button>
        </form>
    </div>

    <!-- IKM Summary Score Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Nilai IKM Konversi (Permenpan RB) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Indeks Kepuasan Konversi</span>
            <div class="flex items-baseline space-x-3">
                <span class="text-4xl font-black text-slate-900">{{ number_format($ikmIndex, 1) }}</span>
                <span class="text-xs text-slate-400 font-semibold">Skala 25 - 100</span>
            </div>
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold border {{ $ikmQuality[2] }}">
                    Mutu: {{ $ikmQuality[0] }} ({{ $ikmQuality[1] }})
                </span>
            </div>
            <p class="text-[11px] text-slate-400">Standar Permenpan RB No. 14 Tahun 2017</p>
        </div>

        <!-- Rata-rata Bintang (Skala 5) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Rata-rata Penilaian</span>
            <div class="flex items-baseline space-x-2">
                <span class="text-4xl font-black text-amber-500">{{ number_format($avgScore, 2) }}</span>
                <span class="text-sm font-bold text-slate-400">/ 5.0</span>
            </div>
            <div class="flex text-amber-400 text-base">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa-solid fa-star {{ $i <= round($avgScore) ? '' : 'text-slate-200' }}"></i>
                @endfor
            </div>
            <p class="text-[11px] text-slate-400">Berdasarkan akumulasi rating seluruh pengunjung</p>
        </div>

        <!-- Total Responden -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Responden</span>
            <div class="text-4xl font-black text-bgtk-800">{{ $totalRespondents }}</div>
            <div class="text-xs text-slate-500 font-medium">
                Tamu yang memberikan ulasan di {{ $year }}
            </div>
            <p class="text-[11px] text-slate-400">Respon survei digital secara langsung</p>
        </div>
    </div>

    <!-- Rating Distribution Progress Bars -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        <h4 class="text-sm font-bold text-slate-800">Distribusi Penilaian Bintang Tamu</h4>

        <div class="space-y-3 pt-2">
            @for($stars = 5; $stars >= 1; $stars--)
            @php
                $count = $ratingCounts[$stars] ?? 0;
                $percent = $totalRespondents > 0 ? round(($count / $totalRespondents) * 100, 1) : 0;
            @endphp
            <div class="flex items-center gap-3 text-xs">
                <div class="w-20 font-bold text-slate-700 flex items-center gap-1 shrink-0">
                    <span>{{ $stars }}</span>
                    <i class="fa-solid fa-star text-amber-400 text-[11px]"></i>
                </div>
                <div class="flex-1 bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                </div>
                <div class="w-24 text-right text-slate-500 font-mono text-[11px] shrink-0">
                    <span class="font-bold text-slate-800">{{ $count }}</span> ({{ $percent }}%)
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Feedback & Testimonials List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-comments text-amber-500"></i> Kritik & Saran Pengunjung Terbaru
            </h4>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($recentFeedbacks as $fb)
            <div class="p-5 hover:bg-slate-50 transition space-y-2">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <span class="font-bold text-slate-900 text-xs">{{ $fb->name }}</span>
                        <span class="text-[11px] text-slate-400">&bull; {{ $fb->institution }} ({{ $fb->city_regency }})</span>
                    </div>
                    <div class="flex items-center gap-1 text-amber-400 text-xs">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star {{ $i <= $fb->satisfaction_rating ? '' : 'text-slate-200' }}"></i>
                        @endfor
                    </div>
                </div>

                <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">
                    "{{ $fb->satisfaction_feedback }}"
                </p>

                <div class="text-[10px] text-slate-400 flex items-center justify-between pt-1">
                    <span>Tujuan: {{ $fb->department ? $fb->department->name : 'Layanan Terpadu' }}</span>
                    <span>{{ $fb->rated_at ? $fb->rated_at->translatedFormat('d M Y H:i') : '' }} WIB</span>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 text-xs">
                Belum ada kritik dan saran yang masuk untuk tahun {{ $year }}.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
