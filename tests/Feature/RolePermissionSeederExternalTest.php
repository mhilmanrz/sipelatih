<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionSeederExternalTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_external_classification_roles_with_no_permissions(): void
    {
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $narasumber = Role::where('name', 'narasumber eksternal')->firstOrFail();
        $moderator = Role::where('name', 'moderator eksternal')->firstOrFail();

        $this->assertCount(0, $narasumber->permissions);
        $this->assertCount(0, $moderator->permissions);
    }

    public function test_seeder_grants_view_external_persons_to_superadmin_and_penyelenggara(): void
    {
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->assertNotNull(Permission::where('name', 'view external persons')->first());

        $superadmin = Role::where('name', 'superadmin')->firstOrFail();
        $penyelenggara = Role::where('name', 'penyelenggara')->firstOrFail();

        $this->assertTrue($superadmin->hasPermissionTo('view external persons'));
        $this->assertTrue($penyelenggara->hasPermissionTo('view external persons'));
    }
}
