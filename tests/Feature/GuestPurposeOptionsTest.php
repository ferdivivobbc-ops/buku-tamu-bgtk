<?php

namespace Tests\Feature;

use Tests\TestCase;

class GuestPurposeOptionsTest extends TestCase
{
    public function test_guest_form_shows_requested_services_and_excludes_pgp_and_psp(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertViewHas('purposes', function ($purposes) {
                return in_array('Konsultasi PPG (Pendidikan Profesi Guru)', $purposes, true)
                    && in_array('Layanan Akun SIMPKB & Pengelolaan Kinerja PMM', $purposes, true)
                    && in_array('Manajemen Talenta', $purposes, true)
                    && in_array('BCKS', $purposes, true)
                    && in_array('UKKJ', $purposes, true)
                    && in_array('INFO GTK', $purposes, true)
                    && ! collect($purposes)->contains(fn ($purpose) => str_contains($purpose, '(PGP)') || str_contains($purpose, '(PSP)'));
            });
    }
}
