<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_search_without_authentication(): void
    {
        $response = $this->getJson(route('users.search', ['q' => 'test']));
        $response->assertStatus(401);
    }

    public function test_returns_empty_when_search_term_is_too_short(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.search', ['q' => 'ab']));

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function test_returns_matching_users(): void
    {
        $currentUser = User::factory()->create();

        // User with matching name and no roles
        $userWithoutRole = User::factory()->create([
            'name' => 'Budi Sudarsono',
            'employee_id' => '123456',
        ]);

        // User with matching name and has a role
        $role = Role::create(['name' => 'superadmin']);
        $userWithRole = User::factory()->create([
            'name' => 'Budi Permadi',
        ]);
        $userWithRole->assignRole($role);

        // User with matching name but is admin@mail.com
        $adminEmailUser = User::factory()->create([
            'name' => 'Budi Admin',
            'email' => 'admin@mail.com',
        ]);

        // 1. Search generally (should return both budis except admin@mail.com)
        $response = $this->actingAs($currentUser)
            ->getJson(route('users.search', ['q' => 'Budi']));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['id' => $userWithoutRole->id, 'name' => 'Budi Sudarsono']);
        $response->assertJsonFragment(['id' => $userWithRole->id, 'name' => 'Budi Permadi']);

        // 2. Search with exclude_roles=true (should only return Budi Sudarsono)
        $response = $this->actingAs($currentUser)
            ->getJson(route('users.search', ['q' => 'Budi', 'exclude_roles' => 'true']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $userWithoutRole->id, 'name' => 'Budi Sudarsono']);
        $response->assertJsonMissing(['id' => $userWithRole->id]);
    }
}
