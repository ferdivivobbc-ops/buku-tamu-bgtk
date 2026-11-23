@extends('layouts.admin')

@section('title', 'Manajemen Bagian & Tim Kerja')
@section('page_title', 'Bagian & Tim Kerja BGTK')
@section('page_subtitle', 'Master data unit kerja, ruangan, dan penanggung jawab di lingkungan BGTK Sumatera Barat')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Daftar Bagian & Tim Kerja ({{ $departments->count() }})</h3>
            <p class="text-xs text-slate-400">Unit kerja ini ditampilkan pada formulir buku tamu pengunjung</p>
        </div>
        <button onclick="openAddModal()" 
                class="px-4 py-2.5 bg-bgtk-800 hover:bg-bgtk-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Bagian / Tim Kerja Baru</span>
        </button>
    </div>

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($departments as $dept)
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between space-y-4 hover:border-amber-300 transition">
            <div class="space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <span class="px-2.5 py-0.5 rounded-lg bg-bgtk-50 text-bgtk-800 text-[10px] font-mono font-bold uppercase tracking-wider border border-bgtk-200">
                        {{ $dept->code ?: 'BAGIAN' }}
                    </span>
                    @if($dept->is_active)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">Non-aktif</span>
                    @endif
                </div>

                <h4 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $dept->name }}</h4>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold {{ $dept->is_available ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                    <i class="fa-solid {{ $dept->is_available ? 'fa-circle-check' : 'fa-person-walking-arrow-right' }}"></i>
                    {{ $dept->is_available ? 'Tersedia' : 'Sedang di luar' }}
                </span>
                <p class="text-xs text-slate-500 leading-relaxed">{{ $dept->description ?: 'Tidak ada deskripsi' }}</p>

                <div class="pt-2 border-t border-slate-100 space-y-1 text-xs">
                    <div class="text-slate-600 flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-amber-500 text-[11px] w-4 text-center"></i>
                        <span>{{ $dept->room_location ?: 'Lokasi belum diatur' }}</span>
                    </div>
                    <div class="text-slate-600 flex items-center gap-1.5">
                        <i class="fa-solid fa-user-tie text-bgtk-700 text-[11px] w-4 text-center"></i>
                        <span>{{ $dept->pic_name ?: 'Staf Pelayanan' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Bottom Bar -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-bold text-slate-500">
                    <i class="fa-solid fa-users text-bgtk-600 mr-1"></i> {{ $dept->guests_count }} Tamu
                </span>

                <div class="flex items-center gap-1">
                    <button type="button" 
                            onclick='openEditModal(@json($dept))'
                            class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition" 
                            title="Edit Bagian">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    @if($dept->guests_count == 0)
                    <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus bagian ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Hapus Bagian">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- Modal Tambah / Edit Bagian -->
<div id="deptModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="bg-gradient-to-r from-bgtk-900 to-bgtk-800 text-white px-6 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="font-bold text-sm">Tambah Bagian / Tim Kerja Baru</h3>
            <button onclick="closeModal()" class="text-slate-300 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="deptForm" method="POST" action="{{ route('admin.departments.store') }}" class="p-6 space-y-4">
            @csrf
            <div id="methodContainer"></div>

            <div>
                <label for="dept_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Bagian / Tim Kerja <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="dept_name" required
                       placeholder="Misal: Tim Kerja Transformasi Pembelajaran"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 text-xs outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="dept_code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Kode Singkat
                    </label>
                    <input type="text" name="code" id="dept_code"
                           placeholder="Misal: TPK"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 text-xs outline-none uppercase font-mono">
                </div>

                <div>
                    <label for="dept_room" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Lokasi Ruangan
                    </label>
                    <input type="text" name="room_location" id="dept_room"
                           placeholder="Misal: Gedung B Lt. 2"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 text-xs outline-none">
                </div>
            </div>

            <div>
                <label for="dept_pic" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Pejabat / Penanggung Jawab (PIC)
                </label>
                <input type="text" name="pic_name" id="dept_pic"
                       placeholder="Misal: Dr. Hj. Nuraini, M.Pd."
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 text-xs outline-none">
            </div>

            <div>
                <label for="dept_desc" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Deskripsi Tugas / Fungsi
                </label>
                <textarea name="description" id="dept_desc" rows="2"
                          placeholder="Fasilitasi kurikulum, pengelolaan kinerja, dsb..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-1 focus:ring-bgtk-700 text-xs outline-none"></textarea>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_active" id="dept_active" value="1" checked class="w-4 h-4 text-bgtk-800 rounded">
                    <span>Aktif (Tampilkan di Formulir Tamu)</span>
                </label>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_available" id="dept_available" value="1" checked class="w-4 h-4 text-bgtk-800 rounded">
                    <span>Tersedia (hapus centang jika tim sedang di luar)</span>
                </label>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-bgtk-800 hover:bg-bgtk-900 text-white text-xs font-bold shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('deptModal');
    const modalTitle = document.getElementById('modalTitle');
    const deptForm = document.getElementById('deptForm');
    const methodContainer = document.getElementById('methodContainer');

    function openAddModal() {
        modalTitle.textContent = 'Tambah Bagian / Tim Kerja Baru';
        deptForm.action = "{{ route('admin.departments.store') }}";
        methodContainer.innerHTML = '';
        
        document.getElementById('dept_name').value = '';
        document.getElementById('dept_code').value = '';
        document.getElementById('dept_room').value = '';
        document.getElementById('dept_pic').value = '';
        document.getElementById('dept_desc').value = '';
        document.getElementById('dept_active').checked = true;
        document.getElementById('dept_available').checked = true;

        modal.classList.remove('hidden');
    }

    function openEditModal(dept) {
        modalTitle.textContent = 'Edit Bagian / Tim Kerja: ' + dept.name;
        deptForm.action = `/admin/departments/${dept.id}`;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('dept_name').value = dept.name || '';
        document.getElementById('dept_code').value = dept.code || '';
        document.getElementById('dept_room').value = dept.room_location || '';
        document.getElementById('dept_pic').value = dept.pic_name || '';
        document.getElementById('dept_desc').value = dept.description || '';
        document.getElementById('dept_active').checked = Boolean(dept.is_active);
        document.getElementById('dept_available').checked = Boolean(dept.is_available);

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endpush
