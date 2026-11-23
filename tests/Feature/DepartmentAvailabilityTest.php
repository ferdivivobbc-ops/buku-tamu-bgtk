<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DepartmentAvailabilityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_mark_department_as_out_and_status_is_saved(): void
    {
        $user = User::create([
            'name' => 'Petugas Tes',
            'username' => 'petugas-tes-' . bin2hex(random_bytes(4)),
            'email' => 'petugas-' . bin2hex(random_bytes(4)) . '@example.test',
            'password' => 'password',
            'role' => 'petugas',
            'is_active' => true,
        ]);
        $department = Department::create([
            'name' => 'Tim tes ketersediaan ' . bin2hex(random_bytes(4)),
            'code' => 'AVA-' . bin2hex(random_bytes(3)),
            'is_active' => true,
            'is_available' => true,
        ]);

        $response = $this->actingAs($user)->put(route('admin.departments.update', $department), [
            'name' => $department->name,
            'code' => $department->code,
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'is_available' => false,
        ]);
    }
}
