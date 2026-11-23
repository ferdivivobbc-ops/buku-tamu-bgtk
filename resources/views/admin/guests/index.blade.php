@extends('layouts.admin')

@section('title', 'Data Kunjungan Tamu')
@section('page_title', 'Buku Tamu Digital')
@section('page_subtitle', 'Seluruh catatan riwayat kunjungan dan mutasi status tamu BGTK')

@section('content')
<div class="space-y-6">

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-4">
        <form action="{{ route('admin.guests.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <!-- Search Text -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Pencarian</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="No. Tiket, Nama, Instansi, No HP..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                </div>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="sedang_bertemu" {{ $status == 'sedang_bertemu' ? 'selected' : '' }}>Sedang Bertemu</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Filter Bagian / Tim Kerja -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Bagian / Tim Kerja</label>
                <select name="department_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none bg-white">
                    <option value="">Semua Bagian</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $deptId == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Dari Tanggal -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
            </div>

            <!-- Filter Sampai Tanggal -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
            </div>

            <!-- Buttons Action -->
            <div class="lg:col-span-6 flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-bgtk-800 hover:bg-bgtk-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow">
                        <i class="fa-solid fa-filter"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.guests.index') }}" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition">
                        Reset
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.guests.export', request()->query()) }}" class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-300 hover:bg-emerald-100 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-file-csv"></i> Unduh CSV
                    </a>
                    <a href="{{ route('admin.guests.print', request()->query()) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 text-white hover:bg-slate-900 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-print"></i> Cetak Laporan
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Guests Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs font-bold text-slate-700">
                Menampilkan {{ $guests->firstItem() ?? 0 }} - {{ $guests->lastItem() ?? 0 }} dari {{ $guests->total() }} total tamu
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Foto</th>
                        <th class="p-3.5">No. Tiket</th>
                        <th class="p-3.5">Nama & Identitas</th>
                        <th class="p-3.5">Asal Instansi & Jabatan</th>
                        <th class="p-3.5">Kab / Kota</th>
                        <th class="p-3.5">Tujuan Bagian</th>
                        <th class="p-3.5">Waktu Kunjungan</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5">Kepuasan</th>
                        <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guests as $guest)
                    <tr class="hover:bg-slate-50 transition">
                        <!-- Foto Avatar -->
                        <td class="p-3.5">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                                @if($guest->photo_url)
                                    <img src="{{ $guest->photo_url }}" alt="{{ $guest->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user text-slate-400"></i>
                                @endif
                            </div>
                        </td>

                        <!-- Tiket -->
                        <td class="p-3.5 font-mono font-bold text-bgtk-800 whitespace-nowrap">
                            <a href="{{ route('admin.guests.show', $guest) }}" class="hover:underline">
                                {{ $guest->ticket_number }}
                            </a>
                        </td>

                        <!-- Nama & Identitas -->
                        <td class="p-3.5">
                            <div class="font-bold text-slate-900 text-sm">{{ $guest->name }}</div>
                            @if($guest->identity_number)
                                <div class="text-[11px] text-slate-400 font-mono">{{ $guest->identity_number }}</div>
                            @endif
                            <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">
                                <i class="fa-brands fa-whatsapp"></i> {{ $guest->phone }}
                            </div>
                        </td>

                        <!-- Instansi & Jabatan -->
                        <td class="p-3.5">
                            <div class="font-semibold text-slate-800">{{ $guest->institution }}</div>
                            <div class="text-[11px] text-slate-500">{{ $guest->position }}</div>
                        </td>

                        <!-- Kab / Kota -->
                        <td class="p-3.5 text-slate-600">
                            {{ $guest->city_regency }}
                        </td>

                        <!-- Bagian Dituju -->
                        <td class="p-3.5">
                            <div class="font-bold text-bgtk-700">
                                {{ $guest->department ? $guest->department->name : '-' }}
                            </div>
                            @if($guest->destination_person)
                                <div class="text-[11px] text-slate-500">
                                    <i class="fa-solid fa-user-tag text-[10px] text-amber-500 mr-1"></i>{{ $guest->destination_person }}
                                </div>
                            @endif
                        </td>

                        <!-- Waktu -->
                        <td class="p-3.5 whitespace-nowrap text-[11px]">
                            <div class="font-medium text-slate-700">
                                {{ $guest->visit_date ? $guest->visit_date->format('d/m/Y') : '-' }}
                            </div>
                            <div class="text-slate-500 font-mono">
                                In: {{ substr($guest->check_in_time, 0, 5) }}
                                @if($guest->check_out_time)
                                 &bull; Out: {{ substr($guest->check_out_time, 0, 5) }}
                                @endif
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="p-3.5">
                            <span class="inline-block px-2.5 py-1 rounded-full font-bold text-[10px] border {{ $guest->status_badge_class }}">
                                {{ $guest->status_label }}
                            </span>
                        </td>

                        <!-- Kepuasan (Rating) -->
                        <td class="p-3.5">
                            @if($guest->satisfaction_rating)
                                <div class="flex items-center text-amber-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $guest->satisfaction_rating ? '' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-[10px] font-bold text-slate-600">({{ $guest->satisfaction_rating }}/5)</span>
                            @else
                                <span class="text-slate-400 text-[11px] italic">Belum diisi</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="p-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <!-- View Details -->
                                <a href="{{ route('admin.guests.show', $guest) }}" 
                                   class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition" 
                                   title="Lihat Detail Lengkap">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.guests.destroy', $guest) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus Data">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-2 text-slate-300 block"></i>
                            Tidak ada data kunjungan tamu yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($guests->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $guests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
