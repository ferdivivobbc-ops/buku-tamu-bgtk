<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $departmentNames = [
            'Kepala Balai BGTK Sumbar',
            'Subbagian Tata Usaha (Kepegawaian & Umum)',
            'Tim Kerja Transformasi Pembelajaran & Kurikulum',
            'Tim Kerja Peningkatan Kompetensi Guru & Tendik (PPG / PKB)',
            'Tim Kerja Kemitraan & Pemberdayaan Komunitas Belajar',
            'Unit Layanan Terpadu (ULT) & Informasi',
            'Pimpinan',
            'Subbagian Umum',
            'Tim Kerja Data dan Evaluasi',
            'Tim Kerja Peningkatan Kompetensi',
            'Tim Kerja Kemitraan dan Fasilitasi',
            'Tim Kerja Pengembangan Model Peningkatan Kompetensi dan Pengembangan Media Pembelajaran',
        ];

        DB::transaction(function () use ($departmentNames) {
            DB::table('departments')
                ->whereIn('name', $departmentNames)
                ->update(['is_active' => true, 'updated_at' => now()]);

            DB::table('departments')
                ->where(function ($query) {
                    $query->where('code', 'PGP-PSP')
                        ->orWhere('name', 'like', '%Program Guru Penggerak%');
                })
                ->update(['is_active' => false, 'updated_at' => now()]);
        });
    }

    public function down(): void
    {
        DB::table('departments')
            ->whereIn('name', [
                'Kepala Balai BGTK Sumbar',
                'Subbagian Tata Usaha (Kepegawaian & Umum)',
                'Tim Kerja Transformasi Pembelajaran & Kurikulum',
                'Tim Kerja Peningkatan Kompetensi Guru & Tendik (PPG / PKB)',
                'Tim Kerja Kemitraan & Pemberdayaan Komunitas Belajar',
                'Unit Layanan Terpadu (ULT) & Informasi',
            ])
            ->update(['is_active' => false, 'updated_at' => now()]);
    }
};
