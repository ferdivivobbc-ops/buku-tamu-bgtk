@extends('layouts.admin')

@section('title', 'Detail Tamu - ' . $guest->ticket_number)
@section('page_title', 'Detail Kunjungan: ' . $guest->ticket_number)
@section('page_subtitle', 'Informasi lengkap data tamu, foto, tanda tangan, dan pembaruan status')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.guests.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Tamu</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('guest.success', $guest->ticket_number) }}" target="_blank"
               class="px-4 py-2 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-id-badge"></i>
                <span>Lihat Kartu Tamu / QR</span>
            </a>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left Column: Photo & Signature & Contact -->
        <div class="space-y-6">
            <!-- Photo Box -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm text-center space-y-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Foto Pengunjung</span>
                <div class="w-40 h-40 mx-auto rounded-2xl overflow-hidden bg-slate-100 border-2 border-bgtk-800 shadow-md flex items-center justify-center">
                    @if($guest->photo_url)
                        <img src="{{ $guest->photo_url }}" alt="{{ $guest->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-slate-400">
                            <i class="fa-solid fa-user text-5xl"></i>
                            <span class="block text-[10px] mt-2">Tanpa Foto</span>
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="font-extrabold text-base text-slate-900">{{ $guest->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $guest->position }}</p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold border {{ $guest->status_badge_class }}">
                        {{ $guest->status_label }}
                    </span>
                </div>

                <!-- WhatsApp Direct Chat Button -->
                <div class="pt-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guest->phone) }}?text=Halo%20Bapak/Ibu%20{{ urlencode($guest->name) }},%20kami%20dari%20BGTK%20Sumatera%20Barat." 
                       target="_blank"
                       class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-brands fa-whatsapp text-base"></i>
                        <span>Hubungi via WhatsApp</span>
                    </a>
                </div>
            </div>

            <!-- Signature Box -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Tanda Tangan Digital</span>
                <div class="w-full h-28 bg-slate-50 rounded-xl border border-slate-200 p-2 flex items-center justify-center">
                    @if($guest->signature_url)
                        <img src="{{ $guest->signature_url }}" alt="Tanda Tangan" class="max-h-full object-contain">
                    @else
                        <span class="text-xs text-slate-400 italic">Tidak ada tanda tangan</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Details & Status Update Form -->
        <div class="md:col-span-2 space-y-6">
            <!-- Details Grid -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-amber-500"></i> Informasi Kunjungan
                    </h4>
                    <span class="font-mono text-xs font-bold text-bgtk-800">{{ $guest->ticket_number }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Nomor Identitas (NIP/NIK)</span>
                        <span class="font-mono font-semibold text-slate-800">{{ $guest->identity_number ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">No. Kontak / WhatsApp</span>
                        <span class="font-semibold text-slate-800">{{ $guest->phone }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Asal Instansi</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $guest->institution }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Jabatan / Peran</span>
                        <span class="font-semibold text-slate-800">{{ $guest->position }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Kabupaten / Kota Asal</span>
                        <span class="font-semibold text-slate-800">{{ $guest->city_regency }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Jumlah Rombongan</span>
                        <span class="font-semibold text-slate-800">{{ $guest->companion_count }} Orang</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Tanggal Kunjungan</span>
                        <span class="font-semibold text-slate-800">{{ $guest->visit_date ? $guest->visit_date->translatedFormat('l, d F Y') : '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Waktu Check-In / Check-Out</span>
                        <span class="font-mono font-semibold text-slate-800">
                            {{ substr($guest->check_in_time, 0, 5) }} WIB - 
                            {{ $guest->check_out_time ? substr($guest->check_out_time, 0, 5) . ' WIB' : 'Sedang Berkunjung' }}
                        </span>
                    </div>
                </div>

                <!-- Bagian & Pegawai Dituju -->
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Bagian & Pejabat BGTK yang Dituju</span>
                    <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-xl">
                        <div class="font-bold text-bgtk-900 text-sm">
                            <i class="fa-solid fa-landmark text-amber-500 mr-1.5"></i>
                            {{ $guest->department ? $guest->department->name : 'Unit Layanan Terpadu BGTK' }}
                        </div>
                        @if($guest->department && $guest->department->room_location)
                            <div class="text-[11px] text-slate-600 mt-0.5">
                                <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> Lokasi: {{ $guest->department->room_location }}
                            </div>
                        @endif
                        @if($guest->destination_person)
                            <div class="text-[11px] text-blue-900 font-semibold mt-1">
                                <i class="fa-solid fa-user-tag text-amber-600 mr-1"></i> Pejabat/Pegawai: {{ $guest->destination_person }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Keperluan Kunjungan -->
                <div class="pt-2">
                    <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Rincian Keperluan Kunjungan</span>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 leading-relaxed">
                        {{ $guest->purpose }}
                    </div>
                </div>

                <!-- Feedback / IKM -->
                @if($guest->satisfaction_rating)
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Penilaian Kepuasan Tamu (IKM)</span>
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex text-amber-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $guest->satisfaction_rating ? '' : 'text-slate-300' }}"></i>
                                @endfor
                            </div>
                            <span class="font-bold text-amber-950 text-xs">Rating {{ $guest->satisfaction_rating }} dari 5</span>
                        </div>
                        @if($guest->satisfaction_feedback)
                            <p class="text-xs text-slate-700 italic pt-1">"{{ $guest->satisfaction_feedback }}"</p>
                        @endif
                        @if($guest->rated_at)
                            <span class="text-[10px] text-slate-400 block">Dinilai pada: {{ $guest->rated_at->translatedFormat('d M Y H:i') }} WIB</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Status Update Form Box -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-bgtk-700"></i> Mutasi Status & Catatan Petugas
                    </h4>
                </div>

                <form action="{{ route('admin.guests.status', $guest) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Ubah Status Kunjungan
                        </label>
                        <select name="status" id="status" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none bg-white font-semibold">
                            <option value="menunggu" {{ $guest->status == 'menunggu' ? 'selected' : '' }}>⏳ Menunggu (Di Ruang Tunggu)</option>
                            <option value="sedang_bertemu" {{ $guest->status == 'sedang_bertemu' ? 'selected' : '' }}>💬 Sedang Bertemu / Diterima</option>
                            <option value="selesai" {{ $guest->status == 'selesai' ? 'selected' : '' }}>✅ Selesai (Kunjungan Usai)</option>
                            <option value="dibatalkan" {{ $guest->status == 'dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label for="officer_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Catatan Petugas / Tindak Lanjut Layanan
                        </label>
                        <textarea name="officer_notes" id="officer_notes" rows="3"
                                  placeholder="Tuliskan catatan tindak lanjut pelayanan, berkas yang diterima, dsb..."
                                  class="w-full p-3 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">{{ old('officer_notes', $guest->officer_notes) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white font-bold text-xs shadow transition flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Perubahan Status</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
