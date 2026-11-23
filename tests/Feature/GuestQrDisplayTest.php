<?php

namespace Tests\Feature;

use Tests\TestCase;

class GuestQrDisplayTest extends TestCase
{
    public function test_qr_flashdisk_page_displays_large_qr_export_options(): void
    {
        $response = $this->get('/qr-flashdisk');

        $response->assertOk()
            ->assertSee('Unduh PNG')
            ->assertSee('Unduh HTML')
            ->assertSee('Perbarui QR')
            ->assertSee('localhost:8000');
    }
}
