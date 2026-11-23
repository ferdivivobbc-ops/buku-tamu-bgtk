<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $departments = [
            ['name' => 'Pimpinan', 'code' => 'PIMPINAN'],
            ['name' => 'Subbagian Umum', 'code' => 'SUBBAG-UMUM'],
            ['name' => 'Tim Kerja Data dan Evaluasi', 'code' => 'DATA-EVALUASI'],
            ['name' => 'Tim Kerja Peningkatan Kompetensi', 'code' => 'PENINGKATAN-KOMPETENSI'],
            ['name' => 'Tim Kerja Kemitraan dan Fasilitasi', 'code' => 'KEMITRAAN-FASILITASI'],
            ['name' => 'Tim Kerja Pengembangan Model Peningkatan Kompetensi dan Pengembangan Media Pembelajaran', 'code' => 'MODEL-MEDIA'],
        ];

        DB::transaction(function () use ($departments, $now) {
            DB::table('departments')->update([
                'is_active' => false,
                'updated_at' => $now,
            ]);

            foreach ($departments as $department) {
                $existing = DB::table('departments')
                    ->where('name', $department['name'])
                    ->first();

                if ($existing) {
                    DB::table('departments')
                        ->where('id', $existing->id)
                        ->update([
                            'code' => $department['code'],
                            'is_active' => true,
                            'updated_at' => $now,
                        ]);
                } else {
                    DB::table('departments')->insert(array_merge($department, [
                        'pic_name' => null,
                        'room_location' => null,
                        'description' => null,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]));
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('departments')
            ->whereIn('code', [
                'PIMPINAN',
                'SUBBAG-UMUM',
                'DATA-EVALUASI',
                'PENINGKATAN-KOMPETENSI',
                'KEMITRAAN-FASILITASI',
                'MODEL-MEDIA',
            ])
            ->update(['is_active' => false, 'updated_at' => now()]);
    }
};
