<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'owner',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('register'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'owner',
            'status' => 'inactive',
        ]);
    }

    public function test_mechanic_registration_requires_admin_approval(): void
    {
        $response = $this->post('/register', [
            'role' => 'mechanic',
            'name' => 'Pending Mechanic',
            'email' => 'pending.mechanic@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('register'));

        $this->assertDatabaseHas('users', [
            'email' => 'pending.mechanic@example.com',
            'role' => 'mechanic',
            'status' => 'inactive',
        ]);
    }
}
