@extends('layouts.guest')

@section('title', 'Checkout Kunjungan & Survei Kepuasan (IKM)')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">

    <!-- Header & Info -->
    <div class="text-center space-y-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300">
            <i class="fa-solid fa-star text-amber-500"></i> Survei Kepuasan Masyarakat (IKM)
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800">
            Checkout Kunjungan & Penilaian Layanan
        </h2>
        <p class="text-sm text-slate-500 max-w-xl mx-auto">
            Sebelum meninggalkan gedung BGTK Sumatera Barat, mohon selesaikan kunjungan Anda dan berikan penilaian demi perbaikan mutu pelayanan publik kami.
        </p>
    </div>

    @if($selectedGuest)
    <!-- Checkout Form for Selected Guest -->
    <div class="bg-white rounded-3xl shadow-xl border-2 border-bgtk-700 overflow-hidden">
        <div class="bg-gradient-to-r from-bgtk-900 to-bgtk-800 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-user-check text-amber-400 text-lg"></i>
                <div>
                    <h3 class="font-bold text-base leading-tight">{{ $selectedGuest->name }}</h3>
                    <p class="text-xs text-slate-300">{{ $selectedGuest->institution }} | Tiket: {{ $selectedGuest->ticket_number }}</p>
                </div>
            </div>
            <a href="{{ route('guest.checkout') }}" class="text-xs bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg transition">
                Ganti Tamu
            </a>
        </div>

        <form action="{{ route('guest.checkout.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            <input type="hidden" name="ticket_number" value="{{ $selectedGuest->ticket_number }}">

            <!-- Guest Summary Info Box -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs">
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block">Bagian Dituju</span>
                    <span class="font-semibold text-slate-800">{{ $selectedGuest->department ? $selectedGuest->department->name : '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block">Jam Masuk</span>
                    <span class="font-semibold text-slate-800">{{ substr($selectedGuest->check_in_time, 0, 5) }} WIB</span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block">Kab / Kota Asal</span>
                    <span class="font-semibold text-slate-800">{{ $selectedGuest->city_regency }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block">Status Saat Ini</span>
                    <span class="px-2 py-0.5 rounded-full font-bold {{ $selectedGuest->status_badge_class }}">
                        {{ $selectedGuest->status_label }}
                    </span>
                </div>
            </div>

            <!-- IKM Star Rating Component -->
            <div class="space-y-3 text-center py-2">
                <label class="block text-sm font-extrabold uppercase tracking-wider text-slate-800">
                    Bagaimana Tingkat Kepuasan Anda Terhadap Pelayanan BGTK Sumbar? <span class="text-rose-500">*</span>
                </label>
                <p class="text-xs text-slate-500">Pilih salah satu bintang di bawah ini:</p>

                <!-- Hidden Input for Rating -->
                <input type="hidden" name="satisfaction_rating" id="satisfaction_rating" value="5" required>

                <!-- Rating Buttons / Stars -->
                <div class="flex items-center justify-center gap-2 sm:gap-4 py-2" id="starContainer">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" 
                            class="star-btn w-12 h-12 sm:w-16 sm:h-16 rounded-2xl flex flex-col items-center justify-center border-2 transition-all duration-200 {{ $i == 5 ? 'border-amber-400 bg-amber-50 text-amber-500 scale-105 shadow-md' : 'border-slate-200 bg-white text-slate-300 hover:border-amber-300 hover:text-amber-400' }}"
                            data-rating="{{ $i }}"
                            onclick="setRating({{ $i }})">
                        <i class="fa-solid fa-star text-2xl sm:text-3xl"></i>
                        <span class="text-[10px] font-bold mt-1 text-slate-600">{{ $i }}</span>
                    </button>
                    @endfor
                </div>

                <div id="ratingLabel" class="text-base font-bold text-amber-600 tracking-wide">
                    ⭐⭐⭐⭐⭐ Sangat Puas (Pelayanan Cepat, Ramah & Memuaskan)
                </div>
            </div>

            <!-- Feedback & Suggestions -->
            <div>
                <label for="satisfaction_feedback" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Kritik & Saran Perbaikan Layanan (Opsional)
                </label>
                <textarea name="satisfaction_feedback" id="satisfaction_feedback" rows="3"
                          placeholder="Tuliskan masukan atau testimoni Anda untuk kemajuan layanan BGTK Sumatera Barat..."
                          class="w-full p-3.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition"></textarea>
            </div>

            <!-- Submit Checkout Button -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('guest.checkout') }}" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-sm shadow-lg hover:shadow-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Selesaikan Kunjungan & Kirim Ulasan</span>
                </button>
            </div>
        </form>
    </div>
    @else
    <!-- Search Box to Find Ticket -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-6 sm:p-8 space-y-6">
        <form action="{{ route('guest.checkout') }}" method="GET" class="space-y-4">
            <label for="q" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                Cari Kunjungan Anda (Nomor Tiket / Nama Lengkap / Asal Sekolah):
            </label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="q" id="q" value="{{ $search }}"
                           placeholder="Ketik nomor tiket (contoh: BGTK-20261026-0001) atau nama Anda..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                </div>
                <button type="submit" class="px-6 py-3 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white font-bold text-sm transition flex items-center justify-center gap-2 shadow">
                    <i class="fa-solid fa-search"></i>
                    <span>Cari Tamu</span>
                </button>
            </div>
        </form>

        <!-- Active Guests Today List -->
        <div class="pt-4 border-t border-slate-200 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user-clock text-amber-500"></i>
                    Daftar Tamu Belum Checkout Hari Ini ({{ $todayGuests->count() }})
                </h3>
                <span class="text-xs text-slate-500">Klik "Checkout Ini" pada baris Anda</span>
            </div>

            @if($todayGuests->isEmpty())
                <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 text-slate-400 space-y-2">
                    <i class="fa-solid fa-circle-check text-4xl text-emerald-400"></i>
                    <p class="text-sm font-medium text-slate-600">Tidak ada tamu yang sedang menunggu checkout saat ini.</p>
                    <p class="text-xs text-slate-400">Semua tamu hari ini telah selesai atau silakan cari dengan nomor tiket di atas.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="p-3 rounded-l-xl">No. Tiket</th>
                                <th class="p-3">Nama Tamu</th>
                                <th class="p-3">Asal Instansi</th>
                                <th class="p-3">Bagian Dituju</th>
                                <th class="p-3">Jam Masuk</th>
                                <th class="p-3 text-right rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($todayGuests as $guest)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="p-3 font-mono font-bold text-bgtk-800">{{ $guest->ticket_number }}</td>
                                <td class="p-3 font-semibold text-slate-900">{{ $guest->name }}</td>
                                <td class="p-3 text-slate-700">{{ $guest->institution }}</td>
                                <td class="p-3">{{ $guest->department ? $guest->department->name : '-' }}</td>
                                <td class="p-3 text-slate-500">{{ substr($guest->check_in_time, 0, 5) }} WIB</td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('guest.checkout', ['ticket' => $guest->ticket_number]) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        <span>Checkout Ini</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    const ratingLabels = {
        5: "⭐⭐⭐⭐⭐ Sangat Puas (Pelayanan Cepat, Ramah & Memuaskan)",
        4: "⭐⭐⭐⭐ Puas (Pelayanan Baik & Informatif)",
        3: "⭐⭐⭐ Cukup (Pelayanan Cukup Memadai)",
        2: "⭐⭐ Kurang Puas (Pelayanan Perlu Peningkatan)",
        1: "⭐ Tidak Puas (Pelayanan Mengecewakan)",
    };

    function setRating(score) {
        document.getElementById('satisfaction_rating').value = score;
        document.getElementById('ratingLabel').textContent = ratingLabels[score] || "";

        const buttons = document.querySelectorAll('.star-btn');
        buttons.forEach(btn => {
            const btnScore = parseInt(btn.getAttribute('data-rating'));
            if (btnScore <= score) {
                btn.classList.add('border-amber-400', 'bg-amber-50', 'text-amber-500');
                btn.classList.remove('border-slate-200', 'bg-white', 'text-slate-300');
            } else {
                btn.classList.remove('border-amber-400', 'bg-amber-50', 'text-amber-500', 'scale-105', 'shadow-md');
                btn.classList.add('border-slate-200', 'bg-white', 'text-slate-300');
            }

            if (btnScore === score) {
                btn.classList.add('scale-105', 'shadow-md');
            } else {
                btn.classList.remove('scale-105', 'shadow-md');
            }
        });
    }
</script>
@endpush
