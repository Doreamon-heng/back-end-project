<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_a_role_from_the_roles_table(): void
    {
        Role::create(['name' => 'customer']);

        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone_number' => '081234567890',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('message', 'User registered successfully');
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role_id' => Role::where('name', 'customer')->value('id'),
        ]);
    }

    public function test_user_can_login_and_access_protected_users_route_with_a_token(): void
    {
        $role = Role::create(['name' => 'admin']);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '081234567891',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertOk();
        $token = $loginResponse->json('data.token');
        $this->assertNotEmpty($token);

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users')
            ->assertOk();
    }

    public function test_admin_user_with_uppercase_role_name_can_access_admin_routes(): void
    {
        $role = Role::create(['name' => 'Admin']);
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone_number' => '081234567892',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertOk();
        $token = $loginResponse->json('data.token');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users')
            ->assertOk();
    }
}
