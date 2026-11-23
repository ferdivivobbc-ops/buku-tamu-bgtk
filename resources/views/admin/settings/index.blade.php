@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Profil Instansi')
@section('page_title', 'Pengaturan Sistem')
@section('page_subtitle', 'Konfigurasi identitas BGTK Sumatera Barat, akun petugas, dan keamanan')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Profil Instansi BGTK -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-building-columns text-amber-500"></i> Profil & Identitas Lembaga
                        </h3>
                        <p class="text-xs text-slate-400">Data ini digunakan pada kop surat cetak, badge tamu, dan monitor TV</p>
                    </div>
                </div>

                <form action="{{ route('admin.settings.institution') }}" method="POST" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Instansi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="institution_name" required
                               value="{{ old('institution_name', $settings['institution_name'] ?? 'Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Nama Kementerian / Induk <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="ministry_name" required
                                   value="{{ old('ministry_name', $settings['ministry_name'] ?? 'Kementerian Pendidikan Dasar dan Menengah RI') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Kode Singkat Instansi
                            </label>
                            <input type="text" name="institution_code"
                                   value="{{ old('institution_code', $settings['institution_code'] ?? 'BGTK-SB') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Alamat Kantor Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" rows="2" required
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">{{ old('address', $settings['address'] ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Nomor Telepon Kantor
                            </label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $settings['phone'] ?? '') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Email Resmi
                            </label>
                            <input type="email" name="email"
                                   value="{{ old('email', $settings['email'] ?? '') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Nama Kepala Balai (Lengkap Gelar)
                            </label>
                            <input type="text" name="head_of_institution"
                                   value="{{ old('head_of_institution', $settings['head_of_institution'] ?? '') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                                NIP Kepala Balai
                            </label>
                            <input type="text" name="nip_head"
                                   value="{{ old('nip_head', $settings['nip_head'] ?? '') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nomor WhatsApp Layanan ULT (Awali 62)
                        </label>
                        <input type="text" name="whatsapp_service"
                               value="{{ old('whatsapp_service', $settings['whatsapp_service'] ?? '') }}"
                               placeholder="Contoh: 628116609898"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none font-mono">
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Teks Pengumuman Berjalan (Running Text Kiosk & TV Display)
                        </label>
                        <textarea name="running_text" rows="2"
                                  placeholder="Teks ucapan selamat datang atau pengumuman penting..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">{{ old('running_text', $settings['running_text'] ?? '') }}</textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white font-bold text-xs shadow">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Profil Instansi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Petugas & Pengguna Sistem -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-users-gear text-bgtk-700"></i> Akun Petugas & Administrator
                        </h3>
                        <p class="text-xs text-slate-400">Kelola pengguna yang berhak mengakses dashboard ini</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-bold uppercase">
                            <tr>
                                <th class="p-3">Nama</th>
                                <th class="p-3">Username / Email</th>
                                <th class="p-3">Peran</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                            <tr>
                                <td class="p-3 font-bold text-slate-800">{{ $user->name }}</td>
                                <td class="p-3">
                                    <div class="font-mono text-slate-700">{{ $user->username }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $user->email }}</div>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    @if($user->is_active)
                                        <span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                    @else
                                        <span class="text-slate-400 font-bold"><i class="fa-solid fa-circle-xmark"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    @if($user->id !== $currentUser->id)
                                    <form action="{{ route('admin.settings.users.toggle', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2 py-1 rounded-lg text-[10px] font-bold {{ $user->is_active ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }}">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-[10px] text-slate-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tambah Akun Baru -->
                <div class="pt-3 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-700 mb-2">Tambah Akun Petugas Baru:</h4>
                    <form action="{{ route('admin.settings.users.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                        @csrf
                        <input type="text" name="name" required placeholder="Nama Lengkap" class="p-2 rounded-xl border border-slate-300 outline-none">
                        <input type="text" name="username" required placeholder="Username unik" class="p-2 rounded-xl border border-slate-300 outline-none font-mono">
                        <input type="email" name="email" required placeholder="Email" class="p-2 rounded-xl border border-slate-300 outline-none">
                        <input type="password" name="password" required placeholder="Password baru" class="p-2 rounded-xl border border-slate-300 outline-none">
                        <select name="role" class="p-2 rounded-xl border border-slate-300 outline-none bg-white">
                            <option value="petugas">Petugas Resepsionis</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <button type="submit" class="p-2 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white font-bold">
                            + Tambah Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Column 3: Ganti Password -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-amber-500"></i> Ganti Password Akun
                    </h3>
                    <p class="text-xs text-slate-400">Perbarui kata sandi login Anda secara berkala</p>
                </div>

                <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-3 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Password Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="current_password" required
                               placeholder="Ketik password lama Anda..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                        @error('current_password')
                            <p class="text-rose-500 text-[11px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" required minlength="6"
                               placeholder="Minimal 6 karakter..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                        @error('password')
                            <p class="text-rose-500 text-[11px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Konfirmasi Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required minlength="6"
                               placeholder="Ulangi password baru..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 outline-none">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white font-bold text-xs shadow">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
