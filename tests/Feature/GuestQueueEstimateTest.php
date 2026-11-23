<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuestQueueEstimateTest extends TestCase
{
    use DatabaseTransactions;

    public function test_ticket_estimates_call_time_from_guests_ahead_in_the_same_department(): void
    {
        Carbon::setTestNow('2026-10-28 15:00:00');

        try {
            $department = Department::create([
                'name' => 'Tes antrean ' . bin2hex(random_bytes(4)),
                'code' => 'TEST-' . bin2hex(random_bytes(3)),
                'is_active' => true,
            ]);
            $otherDepartment = Department::create([
                'name' => 'Tes antrean lain ' . bin2hex(random_bytes(4)),
                'code' => 'TEST-' . bin2hex(random_bytes(3)),
                'is_active' => true,
            ]);

            $this->createGuest($department, 'menunggu');
            $this->createGuest($department, 'menunggu');
            $this->createGuest($department, 'sedang_bertemu');
            $this->createGuest($otherDepartment, 'menunggu');
            $guest = $this->createGuest($department, 'menunggu');

            $response = $this->get(route('guest.success', $guest->ticket_number));

            $response->assertOk()
                ->assertViewHas('queueAhead', 3)
                ->assertViewHas('estimatedCallTime', function (Carbon $estimatedCallTime) {
                    return $estimatedCallTime->format('Y-m-d H:i') === '2026-10-28 15:45';
                })
                ->assertSee('15:45 WIB')
                ->assertSee('3 tamu');
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_ticket_displays_when_the_destination_department_is_unavailable(): void
    {
        $department = Department::create([
            'name' => 'Tim di luar ' . bin2hex(random_bytes(4)),
            'code' => 'OUT-' . bin2hex(random_bytes(3)),
            'is_active' => true,
            'is_available' => false,
        ]);
        $guest = $this->createGuest($department, 'menunggu');

        $this->get(route('guest.success', $guest->ticket_number))
            ->assertOk()
            ->assertSee('Tim kerja sedang di luar');
    }

    private function createGuest(Department $department, string $status): Guest
    {
        return Guest::create([
            'ticket_number' => 'BGTK-TEST-' . bin2hex(random_bytes(6)),
            'visit_date' => Carbon::today()->toDateString(),
            'check_in_time' => '14:00:00',
            'name' => 'Pengunjung Tes',
            'phone' => '081234567890',
            'institution' => 'Instansi Tes',
            'position' => 'Tamu',
            'city_regency' => 'Kota Padang',
            'purpose' => 'Tes estimasi antrean',
            'department_id' => $department->id,
            'companion_count' => 1,
            'status' => $status,
        ]);
    }
}
