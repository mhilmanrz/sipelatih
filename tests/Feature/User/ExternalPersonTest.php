<?php

namespace Tests\Feature\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalPersonTest extends TestCase
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

    public function test_index_only_lists_external_users(): void
    {
        $external = User::factory()->create(['name' => 'Dr. Eksternal', 'is_external' => true]);
        $internal = User::factory()->create(['name' => 'Pegawai Internal']);

        $response = $this->actingAs($this->adminUser)->get(route('external-persons.index'));

        $response->assertStatus(200);
        $response->assertSee('Dr. Eksternal');
        $response->assertDontSee('Pegawai Internal');
    }

    public function test_store_requires_at_least_one_capacity(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('external-persons.store'), [
            'name' => 'Dr. Tanpa Kapasitas',
        ]);

        $response->assertSessionHasErrors(['is_narasumber']);
        $this->assertDatabaseMissing('users', ['name' => 'Dr. Tanpa Kapasitas']);
    }

    public function test_store_creates_external_user_and_assigns_capacity_roles(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('external-persons.store'), [
            'name' => 'Dr. Baru',
            'institution' => 'Universitas Indonesia',
            'external_position' => 'Dosen',
            'is_narasumber' => '1',
            'is_moderator' => '1',
        ]);

        $response->assertRedirect(route('external-persons.index'));

        $person = User::where('name', 'Dr. Baru')->firstOrFail();
        $this->assertTrue($person->is_external);
        $this->assertNotEmpty($person->email);
        $this->assertTrue($person->hasRole('narasumber eksternal'));
        $this->assertTrue($person->hasRole('moderator eksternal'));
    }

    public function test_store_generates_placeholder_email_when_blank(): void
    {
        $this->actingAs($this->adminUser)->post(route('external-persons.store'), [
            'name' => 'Dr. Tanpa Email',
            'is_narasumber' => '1',
        ]);

        $person = User::where('name', 'Dr. Tanpa Email')->firstOrFail();
        $this->assertStringContainsString('@external.local', $person->email);
    }

    public function test_update_syncs_capacity_roles(): void
    {
        $person = User::factory()->create(['name' => 'Dr. Update', 'is_external' => true]);
        $person->assignRole('narasumber eksternal');

        $response = $this->actingAs($this->adminUser)->put(route('external-persons.update', $person->id), [
            'name' => 'Dr. Update',
            'is_moderator' => '1',
        ]);

        $response->assertRedirect(route('external-persons.index'));

        $person->refresh();
        $this->assertFalse($person->hasRole('narasumber eksternal'));
        $this->assertTrue($person->hasRole('moderator eksternal'));
    }

    public function test_destroy_deletes_external_user(): void
    {
        $person = User::factory()->create(['is_external' => true]);

        $response = $this->actingAs($this->adminUser)->delete(route('external-persons.destroy', $person->id));

        $response->assertRedirect(route('external-persons.index'));
        $this->assertDatabaseMissing('users', ['id' => $person->id]);
    }
}
