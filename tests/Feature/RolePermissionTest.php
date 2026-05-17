<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create admin user
        $this->adminUser = User::factory()->create([
            'email' => 'admin@sipelatih.test',
        ]);

        // Assign superadmin role
        $this->adminUser->assignRole('superadmin');
    }

    /**
     * Test permission index page is accessible.
     */
    public function test_permission_index_page_is_accessible(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('permissions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('permission.index');
        $response->assertSee('Manajemen Permission');
    }

    /**
     * Test permission creation.
     */
    public function test_can_create_permission(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('permissions.store'), [
                'name' => 'view special reports',
            ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', [
            'name' => 'view special reports',
            'guard_name' => 'web',
        ]);
    }

    /**
     * Test permission edit & update.
     */
    public function test_can_update_permission(): void
    {
        $permission = Permission::create(['name' => 'temp permission', 'guard_name' => 'web']);

        $response = $this->actingAs($this->adminUser)
            ->put(route('permissions.update', $permission->id), [
                'name' => 'updated permission name',
            ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'updated permission name',
        ]);
    }

    /**
     * Test permission deletion.
     */
    public function test_can_delete_permission(): void
    {
        $permission = Permission::create(['name' => 'to be deleted', 'guard_name' => 'web']);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('permissions.destroy', $permission->id));

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id,
        ]);
    }

    /**
     * Test creating a role and syncing permissions.
     */
    public function test_can_create_role_with_permissions(): void
    {
        $perm1 = Permission::create(['name' => 'custom perm 1', 'guard_name' => 'web']);
        $perm2 = Permission::create(['name' => 'custom perm 2', 'guard_name' => 'web']);

        $response = $this->actingAs($this->adminUser)
            ->post(route('roles.store'), [
                'name' => 'Custom Manager Role',
                'permissions' => [$perm1->id, $perm2->id],
            ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', [
            'name' => 'Custom Manager Role',
        ]);

        $role = Role::findByName('Custom Manager Role', 'web');
        $this->assertTrue($role->hasPermissionTo('custom perm 1'));
        $this->assertTrue($role->hasPermissionTo('custom perm 2'));
    }

    /**
     * Test updating a role and syncing/updating permissions.
     */
    public function test_can_update_role_with_permissions(): void
    {
        $role = Role::create(['name' => 'Custom Secretary', 'guard_name' => 'web']);
        $perm1 = Permission::create(['name' => 'secret perm 1', 'guard_name' => 'web']);
        $perm2 = Permission::create(['name' => 'secret perm 2', 'guard_name' => 'web']);

        $role->syncPermissions([$perm1->id]);

        $response = $this->actingAs($this->adminUser)
            ->put(route('roles.update', $role->id), [
                'name' => 'Updated Custom Secretary',
                'permissions' => [$perm2->id], // Switch from perm1 to perm2
            ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Custom Secretary',
        ]);

        $role->refresh();
        $this->assertFalse($role->hasPermissionTo('secret perm 1'));
        $this->assertTrue($role->hasPermissionTo('secret perm 2'));
    }
}
