<?php

namespace Tests\Feature\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccountExternalScopeTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->adminUser = User::factory()->create(['email' => 'admin@sipelatih.test']);
        $this->adminUser->assignRole('superadmin');
    }

    public function test_pegawai_listing_excludes_external_users_even_without_a_role(): void
    {
        User::factory()->create(['name' => 'Eksternal Tanpa Role', 'is_external' => true]);
        User::factory()->create(['name' => 'Pegawai Biasa']);

        $response = $this->actingAs($this->adminUser)->get(route('users.index'));

        $response->assertDontSee('Eksternal Tanpa Role');
        $response->assertSee('Pegawai Biasa');
    }

    public function test_akun_listing_excludes_external_users_even_with_a_capacity_role(): void
    {
        $external = User::factory()->create(['name' => 'Eksternal Dengan Role', 'is_external' => true]);
        $external->assignRole('narasumber eksternal');

        $staffAccount = User::factory()->create(['name' => 'Staff Akun']);
        $staffAccount->assignRole('pengusul');

        $response = $this->actingAs($this->adminUser)->get(route('accounts.index'));

        $response->assertDontSee('Eksternal Dengan Role');
        $response->assertSee('Staff Akun');
    }
}
