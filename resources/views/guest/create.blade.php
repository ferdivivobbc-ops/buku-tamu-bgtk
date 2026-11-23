@extends('layouts.guest')

@section('title', 'Pendaftaran Kunjungan Tamu')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Hero Card Banner -->
    <div class="bg-gradient-to-r from-bgtk-900 via-bgtk-800 to-bgtk-700 rounded-3xl shadow-xl overflow-hidden text-white mb-8 border border-bgtk-600">
        <div class="px-6 py-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6 relative">
            <div class="space-y-3 z-10 text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400 text-bgtk-950 shadow-sm">
                    <i class="fa-solid fa-sparkles"></i> Form Kunjungan Mandiri / Kiosk
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Selamat Datang di BGTK Sumatera Barat
                </h2>
                <p class="text-slate-200 text-sm max-w-2xl leading-relaxed">
                    Mohon kesediaan Bapak/Ibu Guru, Tenaga Kependidikan, dan Tamu Terhormat untuk mengisi formulir buku tamu digital ini demi ketertiban administrasi dan peningkatan mutu layanan kami.
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-xs text-amber-200 pt-1">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt text-amber-400"></i> Proses Cepat (1 Menit)</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-qrcode text-amber-400"></i> Tiket Kunjungan Digital</span>
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-amber-400"></i> Data Terjamin Aman</span>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center w-64 min-h-64 bg-white rounded-2xl p-4 text-center shrink-0 shadow-lg">
                <span class="text-xs font-extrabold text-bgtk-900 uppercase tracking-wider mb-2">Scan untuk membuka form</span>
                <div id="entry-qrcode" class="p-1 bg-white rounded-lg" aria-label="QR code untuk membuka formulir buku tamu"></div>
                <a href="{{ route('guest.create') }}" class="mt-2 text-[10px] leading-tight text-slate-500 break-all hover:text-bgtk-800">
                    {{ route('guest.create') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-bgtk-800 font-bold text-base">
                <i class="fa-solid fa-clipboard-list text-amber-500"></i>
                <span>Formulir Buku Tamu Digital</span>
            </div>
            <div class="text-xs text-slate-500">
                <span class="text-rose-500 font-bold">*</span> Wajib diisi
            </div>
        </div>

        <form action="{{ route('guest.store') }}" method="POST" enctype="multipart/form-data" id="guestForm" class="p-6 sm:p-10 space-y-8">
            @csrf

            <!-- Section 1: Data Identitas Pengunjung -->
            <div class="space-y-5">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                    <div class="w-7 h-7 rounded-lg bg-bgtk-100 text-bgtk-800 flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Identitas Pengunjung</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Lengkap (Beserta Gelar) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                   placeholder="Contoh: Drs. Syahril, M.Pd."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                        </div>
                        @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIP / NUPTK / NIK -->
                    <div>
                        <label for="identity_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nomor Identitas (NIP / NUPTK / NIK)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-address-card"></i>
                            </span>
                            <input type="text" name="identity_number" id="identity_number" value="{{ old('identity_number') }}"
                                   placeholder="18 Digit NIP atau 16 Digit NUPTK / NIK (Opsional jika umum)"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                        </div>
                        @error('identity_number')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Instansi / Satuan Pendidikan -->
                    <div>
                        <label for="institution" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Asal Instansi / Satuan Pendidikan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-school"></i>
                            </span>
                            <input type="text" name="institution" id="institution" required value="{{ old('institution') }}"
                                   placeholder="Contoh: SMAN 1 Padang / Disdik Kab. Agam / UNP"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                        </div>
                        @error('institution')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan / Peran -->
                    <div>
                        <label for="position" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Jabatan / Peran <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-briefcase"></i>
                            </span>
                            <select name="position" id="position" required
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition bg-white appearance-none">
                                <option value="">-- Pilih Jabatan / Peran --</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos }}" {{ old('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                        @error('position')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Kabupaten / Kota -->
                    <div>
                        <label for="city_regency" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Kabupaten / Kota Asal <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </span>
                            <select name="city_regency" id="city_regency" required
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition bg-white appearance-none">
                                <option value="">-- Pilih Kabupaten / Kota di Sumbar --</option>
                                @foreach($regions as $reg)
                                    <option value="{{ $reg }}" {{ old('city_regency') == $reg ? 'selected' : '' }}>{{ $reg }}</option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                        @error('city_regency')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp / Telepon -->
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            No. WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-base"></i>
                            </span>
                            <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                        </div>
                        @error('phone')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email (Opsional) -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Email (Opsional)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   placeholder="nama@email.com / nama@guru.smp.belajar.id"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                        </div>
                        @error('email')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah Rombongan -->
                    <div>
                        <label for="companion_count" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Jumlah Tamu / Rombongan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-users"></i>
                            </span>
                            <input type="number" name="companion_count" id="companion_count" min="1" max="100" required
                                   value="{{ old('companion_count', 1) }}"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs text-slate-400 font-medium">
                                Orang
                            </span>
                        </div>
                        @error('companion_count')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Tujuan & Keperluan Kunjungan -->
            <div class="space-y-5">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                    <div class="w-7 h-7 rounded-lg bg-bgtk-100 text-bgtk-800 flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Tujuan & Keperluan Kunjungan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bagian / Tim Kerja Dituju -->
                    <div>
                        <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Bagian / Tim Kerja BGTK Dituju <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-landmark"></i>
                            </span>
                            <select name="department_id" id="department_id" required
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition bg-white appearance-none">
                                <option value="">-- Pilih Bagian / Tim Kerja --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" 
                                            data-pic="{{ $dept->pic_name }}" 
                                            {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                        <div id="deptInfo" class="hidden mt-2 p-2.5 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-900">
                            <span class="font-bold"><i class="fa-solid fa-user-tie"></i> Penanggung Jawab:</span> <span id="deptPic">-</span>
                        </div>
                        @error('department_id')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pegawai / Pejabat Spesifik yang Dituju -->
                    <div>
                        <label for="destination_person" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Pegawai / Pejabat Tertentu yang Dituju
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user-tag"></i>
                            </span>
                            <input type="text" name="destination_person" id="destination_person" value="{{ old('destination_person') }}"
                                   placeholder="Nama Pejabat/Staf yang ingin ditemui (Opsional)"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">
                        </div>
                        @error('destination_person')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keperluan Kunjungan (Kategori Cepat) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Pilih Kategori Keperluan Populer
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($purposes as $purp)
                            <button type="button" 
                                    class="purpose-badge px-3 py-1.5 rounded-lg text-xs font-medium border border-slate-200 bg-slate-50 hover:bg-amber-100 hover:border-amber-300 text-slate-700 transition"
                                    onclick="selectPurpose('{{ $purp }}')">
                                {{ $purp }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Rincian Keperluan Kunjungan -->
                    <div class="md:col-span-2">
                        <label for="purpose" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Rincian Keperluan Kunjungan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="purpose" id="purpose" rows="3" required
                                  placeholder="Jelaskan secara singkat maksud dan agenda kunjungan Anda..."
                                  class="w-full p-3.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-bgtk-600 focus:border-bgtk-600 text-sm outline-none transition">{{ old('purpose') }}</textarea>
                        @error('purpose')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Foto Pengunjung & Tanda Tangan Digital -->
            <div class="space-y-5">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                    <div class="w-7 h-7 rounded-lg bg-bgtk-100 text-bgtk-800 flex items-center justify-center font-bold text-sm">
                        3
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Verifikasi Foto & Tanda Tangan Digital</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- 1. Ambil Foto Pengunjung (Webcam / Upload) -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-camera text-bgtk-700 text-sm"></i>
                                Foto Pengunjung
                            </span>
                            <span class="text-[11px] text-slate-500">Opsional / Sangat Dianjurkan</span>
                        </div>

                        <!-- Video / Snapshot Preview Box -->
                        <div class="relative w-full aspect-video bg-slate-900 rounded-xl overflow-hidden shadow-inner flex items-center justify-center border-2 border-dashed border-slate-300" id="cameraBox">
                            <!-- Live Video Stream -->
                            <video id="webcamVideo" autoplay playsinline class="hidden w-full h-full object-cover"></video>
                            
                            <!-- Captured Snapshot Image Preview -->
                            <img id="photoPreview" class="hidden w-full h-full object-cover" alt="Foto Pengunjung">

                            <!-- Placeholder initial -->
                            <div id="cameraPlaceholder" class="text-center p-4 text-slate-400">
                                <i class="fa-solid fa-user-astronaut text-4xl mb-2 text-slate-500"></i>
                                <p class="text-xs font-medium">Kamera belum diaktifkan</p>
                                <p class="text-[10px] text-slate-500">Klik tombol di bawah untuk menyalakan webcam</p>
                            </div>
                        </div>

                        <!-- Camera Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <button type="button" id="startCamBtn" onclick="startCamera()"
                                    class="flex-1 py-2 px-3 rounded-lg bg-bgtk-800 hover:bg-bgtk-900 text-white text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i class="fa-solid fa-video"></i>
                                <span>Buka Kamera</span>
                            </button>

                            <button type="button" id="snapBtn" onclick="snapPhoto()" disabled
                                    class="flex-1 py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i class="fa-solid fa-camera-retro"></i>
                                <span>Ambil Foto</span>
                            </button>

                            <button type="button" id="retakeBtn" onclick="retakePhoto()" class="hidden py-2 px-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-bgtk-950 text-xs font-semibold items-center justify-center gap-1.5 transition">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Ulangi</span>
                            </button>
                        </div>

                        <!-- Hidden File Fallback -->
                        <div class="pt-2 border-t border-slate-200">
                            <label class="block text-[11px] text-slate-500 mb-1">Atau unggah foto dari berkas/galeri ponsel:</label>
                            <input type="file" name="photo_file" id="photo_file" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-bgtk-100 file:text-bgtk-800 hover:file:bg-bgtk-200">
                        </div>

                        <!-- Hidden input for base64 photo -->
                        <input type="hidden" name="photo_data" id="photo_data">
                    </div>

                    <!-- 2. Tanda Tangan Digital (Signature Pad) -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-signature text-bgtk-700 text-sm"></i>
                                Tanda Tangan Digital
                            </span>
                            <span class="text-[11px] text-slate-500">Tandatangani di kotak bawah</span>
                        </div>

                        <!-- Canvas Box -->
                        <div class="relative w-full aspect-video bg-white rounded-xl overflow-hidden shadow-inner border-2 border-slate-300">
                            <canvas id="signatureCanvas" class="w-full h-full cursor-crosshair touch-none"></canvas>
                            <div id="sigPlaceholder" class="absolute inset-0 flex items-center justify-center pointer-events-none text-slate-300 text-xs font-medium">
                                <span>Goreskan tanda tangan di sini (layar sentuh / mouse)</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-2">
                            <button type="button" onclick="clearSignature()"
                                    class="py-2 px-3.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Hapus Tanda Tangan</span>
                            </button>

                            <span class="text-[11px] text-slate-500 italic">
                                Tanda tangan otomatis tersimpan saat submit
                            </span>
                        </div>

                        <!-- Hidden input for base64 signature -->
                        <input type="hidden" name="signature_data" id="signature_data">
                    </div>
                </div>
            </div>

            <!-- Agreement & Submit Button -->
            <div class="pt-6 border-t border-slate-200 space-y-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" required checked
                           class="mt-1 w-4 h-4 text-bgtk-700 rounded border-slate-300 focus:ring-bgtk-600">
                    <span class="text-xs text-slate-600 leading-relaxed">
                        Saya menyatakan bahwa data yang saya isikan pada buku tamu ini adalah benar dan dapat dipertanggungjawabkan untuk keperluan administrasi pelayanan Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat.
                    </span>
                </label>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4">
                    <button type="reset" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-100 transition">
                        Reset Formulir
                    </button>
                    <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-bgtk-800 to-bgtk-600 hover:from-bgtk-900 hover:to-bgtk-700 text-white font-bold text-sm shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Simpan & Dapatkan Tiket Kunjungan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Quick Purpose Helper
    function selectPurpose(text) {
        const area = document.getElementById('purpose');
        area.value = text;
        area.focus();
    }

    // Dynamic Department Information Listener
    const deptSelect = document.getElementById('department_id');
    const deptInfo = document.getElementById('deptInfo');
    const deptPic = document.getElementById('deptPic');
    const destPerson = document.getElementById('destination_person');

    deptSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            const pic = opt.getAttribute('data-pic') || '-';
            deptPic.textContent = pic;
            deptInfo.classList.remove('hidden');

            if (!destPerson.value && pic !== '-') {
                destPerson.placeholder = 'Misal: ' + pic;
            }
        } else {
            deptInfo.classList.add('hidden');
        }
    });

    // -------------------------------------------------------------
    // WEBCAM CAMERA HANDLING
    // -------------------------------------------------------------
    let videoStream = null;
    const video = document.getElementById('webcamVideo');
    const photoPreview = document.getElementById('photoPreview');
    const cameraPlaceholder = document.getElementById('cameraPlaceholder');
    const startCamBtn = document.getElementById('startCamBtn');
    const snapBtn = document.getElementById('snapBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const photoDataInput = document.getElementById('photo_data');

    async function startCamera() {
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: "user" }
            });
            video.srcObject = videoStream;
            video.classList.remove('hidden');
            cameraPlaceholder.classList.add('hidden');
            photoPreview.classList.add('hidden');
            startCamBtn.classList.add('hidden');
            snapBtn.disabled = false;
        } catch (err) {
            console.error("Webcam error:", err);
            alert("Tidak dapat mengakses kamera webcam. Anda tetap dapat mengunggah file foto melalui tombol unggah di bawah.");
        }
    }

    function snapPhoto() {
        if (!videoStream) return;
        
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        photoDataInput.value = dataUrl;

        // Show snapshot
        photoPreview.src = dataUrl;
        photoPreview.classList.remove('hidden');
        video.classList.add('hidden');

        // Stop video stream to save power
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;

        snapBtn.disabled = true;
        snapBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        retakeBtn.classList.add('flex');
    }

    function retakePhoto() {
        photoDataInput.value = '';
        photoPreview.classList.add('hidden');
        retakeBtn.classList.add('hidden');
        retakeBtn.classList.remove('flex');
        snapBtn.classList.remove('hidden');
        startCamera();
    }

    // -------------------------------------------------------------
    // SIGNATURE PAD HANDLING (HTML5 Canvas Touch & Mouse)
    // -------------------------------------------------------------
    const sigCanvas = document.getElementById('signatureCanvas');
    const sigCtx = sigCanvas.getContext('2d');
    const sigPlaceholder = document.getElementById('sigPlaceholder');
    const sigDataInput = document.getElementById('signature_data');
    let isDrawing = false;
    let hasSigned = false;

    // Resize canvas based on client rect
    function resizeCanvas() {
        const rect = sigCanvas.getBoundingClientRect();
        sigCanvas.width = rect.width;
        sigCanvas.height = rect.height;
        sigCtx.lineWidth = 2.5;
        sigCtx.lineCap = 'round';
        sigCtx.lineJoin = 'round';
        sigCtx.strokeStyle = '#024282';
    }

    window.addEventListener('load', resizeCanvas);
    window.addEventListener('resize', resizeCanvas);

    function getPos(e) {
        const rect = sigCanvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startSign(e) {
        isDrawing = true;
        hasSigned = true;
        sigPlaceholder.classList.add('hidden');
        const pos = getPos(e);
        sigCtx.beginPath();
        sigCtx.moveTo(pos.x, pos.y);
        e.preventDefault();
    }

    function drawSign(e) {
        if (!isDrawing) return;
        const pos = getPos(e);
        sigCtx.lineTo(pos.x, pos.y);
        sigCtx.stroke();
        e.preventDefault();
    }

    function stopSign() {
        if (isDrawing) {
            isDrawing = false;
            sigDataInput.value = sigCanvas.toDataURL('image/png');
        }
    }

    function clearSignature() {
        sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        sigDataInput.value = '';
        hasSigned = false;
        sigPlaceholder.classList.remove('hidden');
    }

    // Mouse listeners
    sigCanvas.addEventListener('mousedown', startSign);
    sigCanvas.addEventListener('mousemove', drawSign);
    sigCanvas.addEventListener('mouseup', stopSign);
    sigCanvas.addEventListener('mouseleave', stopSign);

    // Touch listeners
    sigCanvas.addEventListener('touchstart', startSign, { passive: false });
    sigCanvas.addEventListener('touchmove', drawSign, { passive: false });
    sigCanvas.addEventListener('touchend', stopSign);

    // Form Submit handling
    document.getElementById('guestForm').addEventListener('submit', function(e) {
        if (hasSigned) {
            sigDataInput.value = sigCanvas.toDataURL('image/png');
        }
        document.getElementById('btnSubmit').disabled = true;
        document.getElementById('btnSubmit').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan Kunjungan...';
    });
</script>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const entryQrContainer = document.getElementById('entry-qrcode');
    const entryFormUrl = @json(route('guest.create'));

    if (window.QRCode && entryQrContainer) {
        new QRCode(entryQrContainer, {
            text: entryFormUrl,
            width: 208,
            height: 208,
            colorDark: '#072442',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    } else if (entryQrContainer) {
        entryQrContainer.textContent = 'QR tidak dapat dimuat. Buka tautan di bawah.';
        entryQrContainer.classList.add('text-xs', 'text-rose-600', 'max-w-32');
    }
</script>
@endpush
