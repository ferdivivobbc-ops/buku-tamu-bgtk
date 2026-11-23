<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Guest;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator BGTK',
                'email' => 'admin@bgtk.sumbar.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'Petugas Resepsionis / Front Office',
                'email' => 'resepsionis@bgtk.sumbar.go.id',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'phone' => '081298765432',
                'is_active' => true,
            ]
        );

        // 2. Settings
        $settings = [
            'institution_name' => 'Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat',
            'institution_code' => 'BGTK-SB',
            'ministry_name' => 'Kementerian Pendidikan Dasar dan Menengah RI',
            'address' => 'Jl. Dewi Sartika, Rawang, Kec. Pariaman Tengah - Kota Pariaman',
            'phone' => '(0751) 7055123 / 0811-660-9898',
            'email' => 'bgtk.sumbar@kemdikbud.go.id',
            'head_of_institution' => 'Drs. H. Mulyadi, M.Pd.',
            'nip_head' => '19680512 199303 1 002',
            'running_text' => 'Selamat Datang di Balai Guru dan Tenaga Kependidikan (BGTK) Provinsi Sumatera Barat. Melayani dengan Sepenuh Hati untuk Kemajuan GTK Sumatera Barat.',
            'whatsapp_service' => '628116609898',
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // 3. Departments
        $departments = [
            [
                'name' => 'Kepala Balai BGTK Sumbar',
                'code' => 'KABALAI',
                'pic_name' => 'Drs. H. Mulyadi, M.Pd.',
                'room_location' => 'Gedung Utama Lantai 2',
                'description' => 'Audiensi kedinasan, koordinasi pimpinan, dan urusan strategis kelembagaan BGTK.',
            ],
            [
                'name' => 'Subbagian Tata Usaha (Kepegawaian & Umum)',
                'code' => 'TU',
                'pic_name' => 'Rahmat Hidayat, S.E., M.Si.',
                'room_location' => 'Gedung A Lantai 1',
                'description' => 'Layanan persuratan, kepegawaian, keuangan, perlengkapan BMN, dan tata usaha umum.',
            ],
            [
                'name' => 'Tim Kerja Transformasi Pembelajaran & Kurikulum',
                'code' => 'TPK',
                'pic_name' => 'Dr. Hj. Nuraini, M.Pd.',
                'room_location' => 'Gedung B Lantai 2',
                'description' => 'Fasilitasi Kurikulum Merdeka, Platform Merdeka Mengajar (PMM), dan inovasi pembelajaran.',
            ],
            [
                'name' => 'Tim Kerja Peningkatan Kompetensi Guru & Tendik (PPG / PKB)',
                'code' => 'PKGTK',
                'pic_name' => 'H. Irfan Maulana, S.Pd., M.Ed.',
                'room_location' => 'Gedung B Lantai 1',
                'description' => 'Konsultasi Program Profesi Guru (PPG), Pengembangan Keprofesian Berkelanjutan, dan Uji Kompetensi.',
            ],
            [
                'name' => 'Tim Kerja Kemitraan & Pemberdayaan Komunitas Belajar',
                'code' => 'KPKB',
                'pic_name' => 'Ahmad Fauzi, S.Kom., M.T.',
                'room_location' => 'Gedung C Lantai 1',
                'description' => 'Kerjasama pemda / dinas pendidikan, organisasi profesi (PGRI, IGI), LPTK, dan komunitas belajar (KKG/MGMP/MKKS).',
            ],
            [
                'name' => 'Unit Layanan Terpadu (ULT) & Informasi',
                'code' => 'ULT',
                'pic_name' => 'Staf Pelayanan Terpadu',
                'room_location' => 'Lobby Utama Gedung BGTK',
                'description' => 'Pusat informasi umum, pengaduan masyarakat, konsultasi SIMPKB, dan penyerahan berkas.',
            ],
            [
                'name' => 'Subbagian Umum',
                'code' => 'SUBBAG-UMUM',
            ],
            [
                'name' => 'Tim Kerja Data dan Evaluasi',
                'code' => 'DATA-EVALUASI',
            ],
            [
                'name' => 'Tim Kerja Peningkatan Kompetensi',
                'code' => 'PENINGKATAN-KOMPETENSI',
            ],
            [
                'name' => 'Tim Kerja Kemitraan dan Fasilitasi',
                'code' => 'KEMITRAAN-FASILITASI',
            ],
            [
                'name' => 'Tim Kerja Pengembangan Model Peningkatan Kompetensi dan Pengembangan Media Pembelajaran',
                'code' => 'MODEL-MEDIA',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['name' => $dept['name']],
                array_merge($dept, ['is_active' => true])
            );
        }
        Department::where('code', 'PGP-PSP')->update(['is_active' => false]);
        Department::where('name', 'Pimpinan')->update(['is_active' => false]);

        // 4. Sample Guests (for realistic initial data)
        $deptULT = Department::where('code', 'ULT')->first();
        $deptTU = Department::where('code', 'TU')->first();
        $deptPKGTK = Department::where('code', 'PKGTK')->first();
        $deptKab = Department::where('code', 'KABALAI')->first();

        $today = Carbon::today();

        $sampleGuests = [
            [
                'ticket_number' => 'BGTK-' . $today->format('Ymd') . '-0001',
                'visit_date' => $today->format('Y-m-d'),
                'check_in_time' => '08:15:00',
                'check_out_time' => '09:20:00',
                'name' => 'Drs. Hendri Susanto, M.Pd.',
                'identity_number' => '197403152000031004',
                'phone' => '081267891234',
                'email' => 'hendri.susanto@sman1bukittinggi.sch.id',
                'institution' => 'SMAN 1 Bukittinggi',
                'position' => 'Kepala Sekolah',
                'city_regency' => 'Kota Bukittinggi',
                'purpose' => 'Konsultasi kelanjutan Lokakarya Guru Penggerak Angkatan 11 dan koordinasi pengimbasan praktik baik di wilayah Bukittinggi.',
                'department_id' => $deptPKGTK?->id,
                'destination_person' => 'H. Irfan Maulana, S.Pd., M.Ed.',
                'companion_count' => 2,
                'status' => 'selesai',
                'officer_notes' => 'Telah berkonsultasi mengenai teknis lokakarya dan sinkronisasi data calon fasilitator.',
                'satisfaction_rating' => 5,
                'satisfaction_feedback' => 'Pelayanan sangat ramah, tempat nyaman dan penjelasannya sangat jelas. Terima kasih BGTK Sumbar!',
                'rated_at' => $today->copy()->setTime(9, 21),
            ],
            [
                'ticket_number' => 'BGTK-' . $today->format('Ymd') . '-0002',
                'visit_date' => $today->format('Y-m-d'),
                'check_in_time' => '08:45:00',
                'check_out_time' => '10:05:00',
                'name' => 'Siti Rahmah, S.Pd.',
                'identity_number' => '198811202014022001',
                'phone' => '085274123890',
                'email' => 'sitirahmah88@gmail.com',
                'institution' => 'SMPN 2 Batusangkar',
                'position' => 'Guru Mata Pelajaran',
                'city_regency' => 'Kab. Tanah Datar',
                'purpose' => 'Konsultasi kendala verifikasi berkas PPG Bagi Guru Tertentu tahun 2026 dan integrasi data SIMPKB.',
                'department_id' => $deptPKGTK?->id,
                'destination_person' => 'H. Irfan Maulana, S.Pd., M.Ed.',
                'companion_count' => 1,
                'status' => 'selesai',
                'officer_notes' => 'Kendala akun SIMPKB telah dibantu reset dan diarahkan langkah verifikasi lanjutan.',
                'satisfaction_rating' => 5,
                'satisfaction_feedback' => 'Sangat terbantu, petugas sangat sabar membantu kendala akun saya sampai selesai.',
                'rated_at' => $today->copy()->setTime(10, 6),
            ],
            [
                'ticket_number' => 'BGTK-' . $today->format('Ymd') . '-0003',
                'visit_date' => $today->format('Y-m-d'),
                'check_in_time' => '09:30:00',
                'check_out_time' => null,
                'name' => 'Drs. H. Asril, M.Si.',
                'identity_number' => '196708101994031003',
                'phone' => '081374561234',
                'email' => 'disdik@agamkab.go.id',
                'institution' => 'Dinas Pendidikan dan Kebudayaan Kab. Agam',
                'position' => 'Pejabat / Staf Dinas',
                'city_regency' => 'Kab. Agam',
                'purpose' => 'Koordinasi dan audiensi program peningkatan kompetensi guru penggerak tingkat SD dan SMP di Kabupaten Agam.',
                'department_id' => $deptKab?->id,
                'destination_person' => 'Drs. H. Mulyadi, M.Pd.',
                'companion_count' => 3,
                'status' => 'sedang_bertemu',
                'officer_notes' => 'Sedang berlangsung audiensi di Ruang Kepala Balai.',
                'satisfaction_rating' => null,
                'satisfaction_feedback' => null,
                'rated_at' => null,
            ],
            [
                'ticket_number' => 'BGTK-' . $today->format('Ymd') . '-0004',
                'visit_date' => $today->format('Y-m-d'),
                'check_in_time' => '10:10:00',
                'check_out_time' => null,
                'name' => 'Rina Marlina, S.Pd.SD',
                'identity_number' => '199204052020122015',
                'phone' => '082169874561',
                'email' => 'rinamarlina.sd@gmail.com',
                'institution' => 'SDN 05 Painan',
                'position' => 'Guru Kelas',
                'city_regency' => 'Kab. Pesisir Selatan',
                'purpose' => 'Menyerahkan surat tugas dan berkas fisik keikutsertaan lokakarya pendampingan komunitas belajar.',
                'department_id' => $deptTU?->id,
                'destination_person' => 'Rahmat Hidayat, S.E., M.Si.',
                'companion_count' => 1,
                'status' => 'menunggu',
                'officer_notes' => null,
                'satisfaction_rating' => null,
                'satisfaction_feedback' => null,
                'rated_at' => null,
            ],
            [
                'ticket_number' => 'BGTK-' . $today->format('Ymd') . '-0005',
                'visit_date' => $today->format('Y-m-d'),
                'check_in_time' => '10:35:00',
                'check_out_time' => null,
                'name' => 'Yusrizal, S.Kom.',
                'identity_number' => '198506142010011012',
                'phone' => '081275349821',
                'email' => 'yusrizal@padang.go.id',
                'institution' => 'Dinas Pendidikan Kota Padang',
                'position' => 'Tenaga Administrasi / Operator',
                'city_regency' => 'Kota Padang',
                'purpose' => 'Sinkronisasi data Pengelolaan Kinerja Guru dan Kepala Sekolah pada Platform Merdeka Mengajar (PMM).',
                'department_id' => $deptULT?->id,
                'destination_person' => 'Staf Pelayanan Terpadu',
                'companion_count' => 2,
                'status' => 'menunggu',
                'officer_notes' => null,
                'satisfaction_rating' => null,
                'satisfaction_feedback' => null,
                'rated_at' => null,
            ],
        ];

        foreach ($sampleGuests as $guestData) {
            Guest::updateOrCreate(['ticket_number' => $guestData['ticket_number']], $guestData);
        }
    }
}
