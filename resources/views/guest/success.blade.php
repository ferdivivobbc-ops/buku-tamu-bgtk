@extends('layouts.guest')

@section('title', 'Tiket Kunjungan Tamu - ' . $guest->ticket_number)

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableBadge, #printableBadge * {
            visibility: visible;
        }
        #printableBadge {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
        header, footer, .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

    <!-- Success Feedback Notification -->
    <div class="text-center mb-8 space-y-2 no-print">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner animate-bounce">
            <i class="fa-solid fa-check"></i>
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800">
            Pendaftaran Kunjungan Berhasil!
        </h2>
        <p class="text-sm text-slate-500 max-w-lg mx-auto">
            Terima kasih telah mengisi buku tamu. Simpan atau cetak tiket kunjungan di bawah ini sebagai tanda pengenal selama berada di lingkungan BGTK Sumatera Barat.
        </p>
    </div>

    <!-- Printable Official Visitor Badge -->
    <div id="printableBadge" class="bg-white rounded-3xl shadow-2xl border-2 border-bgtk-800 overflow-hidden relative">
        <!-- Top Badge Header -->
        <div class="bg-gradient-to-r from-bgtk-900 via-bgtk-800 to-bgtk-700 text-white p-6 border-b-4 border-amber-400 relative">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 bg-white rounded-xl p-1.5 flex items-center justify-center text-bgtk-900 shadow">
                        <svg class="w-9 h-9" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zm0 8.7L5.5 8 12 4.7 18.5 8 12 10.7z"/>
                            <path d="M4 10.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7v-5.8l-8 4-8-4z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold tracking-widest text-amber-300 uppercase block">KEMENDIKDASMEN RI</span>
                        <h3 class="text-base sm:text-lg font-black tracking-tight leading-tight">BGTK SUMATERA BARAT</h3>
                        <p class="text-[11px] text-slate-200">Kartu Tanda Pengenal Tamu / Visitor Pass</p>
                    </div>
                </div>

                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-amber-400 text-bgtk-950 font-black text-xs rounded-full uppercase tracking-wider shadow">
                        TAMU RESMI
                    </span>
                    <p class="text-[10px] text-slate-300 mt-1">{{ $guest->visit_date ? $guest->visit_date->translatedFormat('d F Y') : date('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Ticket Number Ribbon -->
        <div class="bg-slate-100 border-b border-slate-200 px-6 py-3 flex flex-wrap items-center justify-between gap-2">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Nomor Registrasi:</span>
                <span class="text-lg font-black text-bgtk-900 tracking-wider ml-1">{{ $guest->ticket_number }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500"><i class="fa-solid fa-clock"></i> Masuk: <strong>{{ substr($guest->check_in_time, 0, 5) }} WIB</strong></span>
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $guest->status_badge_class }}">
                    {{ $guest->status_label }}
                </span>
            </div>
        </div>

        @if($guest->status === 'menunggu')
        <div class="mx-6 mt-5 rounded-2xl border border-amber-300 bg-amber-50 p-4 sm:mx-8">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-amber-950">Perkiraan antrean Anda</h4>
                    @if($queueAhead === 0)
                        <p class="mt-1 text-sm text-amber-900">
                            Anda berada di antrean pertama. Mohon menunggu panggilan petugas.
                        </p>
                    @else
                        <p class="mt-1 text-sm text-amber-900">
                            Ada <strong>{{ $queueAhead }} tamu</strong> di depan Anda. Perkiraan dipanggil sekitar
                            <strong>{{ $estimatedCallTime->format('H:i') }} WIB</strong>.
                        </p>
                    @endif
                    <p class="mt-1 text-xs leading-relaxed text-amber-800">
                        Perkiraan menggunakan rata-rata 15 menit per tamu di depan untuk bagian yang dituju.
                        Waktu sebenarnya dapat berubah mengikuti lama pelayanan; mohon tetap berada di area tunggu dan pantau panggilan petugas.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Badge Body -->
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            <!-- Left: Visitor Photo & QR Code -->
            <div class="flex flex-col items-center justify-center space-y-4 text-center md:border-r border-slate-200 md:pr-6">
                <!-- Visitor Photo -->
                <div class="w-32 h-32 rounded-2xl overflow-hidden border-2 border-bgtk-800 shadow-md bg-slate-100 flex items-center justify-center">
                    @if($guest->photo_url)
                        <img src="{{ $guest->photo_url }}" alt="{{ $guest->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-slate-400">
                            <i class="fa-solid fa-user-tie text-5xl"></i>
                            <span class="block text-[10px] mt-1 font-medium">Tanpa Foto</span>
                        </div>
                    @endif
                </div>

                <!-- QR Code Container -->
                <div class="space-y-1">
                    <div id="qrcode" class="p-2 bg-white rounded-xl shadow-sm border border-slate-200 inline-block"></div>
                    <p class="text-[10px] font-mono text-slate-500">Scan untuk verifikasi / checkout</p>
                </div>
            </div>

            <!-- Right: Visitor Details -->
            <div class="md:col-span-2 space-y-3.5 text-sm">
                <div>
                    <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Nama Pengunjung</span>
                    <h4 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $guest->name }}</h4>
                    @if($guest->identity_number)
                        <p class="text-xs text-slate-500 font-mono">ID: {{ $guest->identity_number }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Asal Instansi</span>
                        <p class="font-semibold text-slate-800">{{ $guest->institution }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Jabatan</span>
                        <p class="font-semibold text-slate-800">{{ $guest->position }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kabupaten / Kota</span>
                        <p class="font-semibold text-slate-800">{{ $guest->city_regency }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Rombongan</span>
                        <p class="font-semibold text-slate-800">{{ $guest->companion_count }} Orang</p>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Bagian / Tim Kerja Dituju</span>
                    <p class="font-bold text-bgtk-800 text-sm">
                        <i class="fa-solid fa-building text-amber-500 mr-1"></i>
                        {{ $guest->department ? $guest->department->name : 'Layanan Terpadu BGTK' }}
                    </p>
                    @if($guest->department && !$guest->department->is_available)
                        <p class="mt-1 inline-flex items-center gap-1.5 rounded-lg border border-amber-300 bg-amber-50 px-2.5 py-1.5 text-xs font-bold text-amber-900">
                            <i class="fa-solid fa-person-walking-arrow-right"></i>
                            Tim kerja sedang di luar
                        </p>
                    @endif
                    @if($guest->destination_person)
                        <p class="text-xs text-slate-600 mt-0.5">
                            Menemui: <span class="font-semibold">{{ $guest->destination_person }}</span>
                        </p>
                    @endif
                </div>

                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Keperluan</span>
                    <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        {{ $guest->purpose }}
                    </p>
                </div>

                @if($guest->signature_url)
                <div class="pt-2 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Tanda Tangan Pengunjung:</span>
                    <img src="{{ $guest->signature_url }}" alt="Tanda Tangan" class="h-10 border-b border-slate-300">
                </div>
                @endif
            </div>
        </div>

        <!-- Badge Footer / Rules -->
        <div class="bg-slate-50 border-t border-slate-200 px-6 py-3 text-[10px] text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>Harap kenakan kartu pengenal ini & kembalikan/checkout sebelum meninggalkan gedung BGTK.</span>
            <span class="font-bold text-bgtk-900">BGTK Sumbar Melayani Sepenuh Hati</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3 no-print">
        <button onclick="window.print()" 
                class="px-6 py-3 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white font-bold text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Kartu Tamu / Simpan PDF</span>
        </button>

        @if($whatsapp)
        <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20BGTK%20Sumbar,%20saya%20telah%20mengisi%20buku%20tamu%20dengan%20No%20Tiket:%20{{ $guest->ticket_number }}" 
           target="_blank"
           class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-lg"></i>
            <span>Konfirmasi ke WhatsApp ULT BGTK</span>
        </a>
        @endif

        <a href="{{ route('guest.checkout', ['ticket' => $guest->ticket_number]) }}" 
           class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-bgtk-950 font-bold text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Checkout / Isi Survei Kepuasan</span>
        </a>

        <a href="{{ route('guest.create') }}" 
           class="px-5 py-3 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Daftar Tamu Baru</span>
        </a>
    </div>

</div>
@endsection

@push('scripts')
<!-- QRCode.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR Code containing the ticket verification link
    const qrcodeContainer = document.getElementById("qrcode");
    new QRCode(qrcodeContainer, {
        text: "{{ route('guest.checkout', ['ticket' => $guest->ticket_number]) }}",
        width: 96,
        height: 96,
        colorDark: "#072442",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.M
    });
</script>
@endpush
