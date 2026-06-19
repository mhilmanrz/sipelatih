<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserSearchCandidatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_search_candidates_without_authentication(): void
    {
        $response = $this->getJson(route('users.search-candidates', ['q' => 'test']));
        $response->assertStatus(401);
    }

    public function test_returns_empty_when_search_term_is_too_short(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.search-candidates', ['q' => 'ab']));

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function test_returns_matching_users_without_roles(): void
    {
        $currentUser = User::factory()->create();

        // Target user to find
        $matchingUser = User::factory()->create([
            'name' => 'Budi Sudarsono',
            'employee_id' => '123456',
        ]);

        // User with matching name but has a role
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

        // Search by name
        $response = $this->actingAs($currentUser)
            ->getJson(route('users.search-candidates', ['q' => 'Budi']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $matchingUser->id, 'name' => 'Budi Sudarsono']);

        // Search by employee_id
        $response = $this->actingAs($currentUser)
            ->getJson(route('users.search-candidates', ['q' => '1234']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $matchingUser->id, 'name' => 'Budi Sudarsono']);
    }
}
