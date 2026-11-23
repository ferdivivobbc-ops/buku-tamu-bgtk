<?php

namespace Tests\Feature;

use Tests\TestCase;

class GuestDepartmentOptionsTest extends TestCase
{
    public function test_guest_form_keeps_existing_departments_adds_requested_ones_and_excludes_pgp(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertViewHas('departments', function ($departments) {
                $actualNames = $departments->pluck('name')->all();
                $expectedNames = [
                    'Kepala Balai BGTK Sumbar',
                    'Subbagian Tata Usaha (Kepegawaian & Umum)',
                    'Tim Kerja Transformasi Pembelajaran & Kurikulum',
                    'Tim Kerja Peningkatan Kompetensi Guru & Tendik (PPG / PKB)',
                    'Tim Kerja Kemitraan & Pemberdayaan Komunitas Belajar',
                    'Unit Layanan Terpadu (ULT) & Informasi',
                    'Subbagian Umum',
                    'Tim Kerja Data dan Evaluasi',
                    'Tim Kerja Peningkatan Kompetensi',
                    'Tim Kerja Kemitraan dan Fasilitasi',
                    'Tim Kerja Pengembangan Model Peningkatan Kompetensi dan Pengembangan Media Pembelajaran',
                ];

                sort($actualNames);
                sort($expectedNames);

                return $actualNames === $expectedNames
                    && ! $departments->contains(fn ($department) => str_contains($department->name, '(PGP)'));
            });
    }
}
