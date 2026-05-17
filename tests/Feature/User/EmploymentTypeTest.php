<?php

namespace Tests\Feature\User;

use App\Models\User\EmploymentType;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmploymentTypeTest extends TestCase
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
     * Test list employment types and search functionality.
     */
    public function test_user_can_view_employment_types_index(): void
    {
        $type1 = EmploymentType::create(['code' => 'TEST1', 'name' => 'First Test Type']);
        $type2 = EmploymentType::create(['code' => 'XYZ', 'name' => 'Another Unique One']);

        // View index
        $response = $this->actingAs($this->adminUser)
            ->get(route('employment-types.index'));

        $response->assertStatus(200);
        $response->assertViewIs('employment_type.index');
        $response->assertSee('TEST1');
        $response->assertSee('First Test Type');
        $response->assertSee('XYZ');

        // Search by name
        $response = $this->actingAs($this->adminUser)
            ->get(route('employment-types.index', ['q' => 'Unique']));
        $response->assertSee('XYZ');
        $response->assertDontSee('TEST1');

        // Search by code
        $response = $this->actingAs($this->adminUser)
            ->get(route('employment-types.index', ['q' => 'TEST1']));
        $response->assertSee('First Test Type');
        $response->assertDontSee('Another Unique One');
    }

    /**
     * Test view create form.
     */
    public function test_user_can_view_create_form(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('employment-types.create'));

        $response->assertStatus(200);
        $response->assertViewIs('employment_type.create');
        $response->assertSee('Tambah Jenis Kepegawaian');
    }

    /**
     * Test store new employment type.
     */
    public function test_user_can_store_employment_type(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('employment-types.store'), [
                'code' => 'NEWCODE',
                'name' => 'Newly Added Type',
            ]);

        $response->assertRedirect(route('employment-types.index'));
        $this->assertDatabaseHas('employment_types', [
            'code' => 'NEWCODE',
            'name' => 'Newly Added Type',
        ]);
    }

    /**
     * Test store validation constraints.
     */
    public function test_store_validation_requires_code_and_name_and_code_must_be_unique(): void
    {
        EmploymentType::create(['code' => 'DUP', 'name' => 'Existing']);

        // Test missing fields
        $response = $this->actingAs($this->adminUser)
            ->post(route('employment-types.store'), []);

        $response->assertSessionHasErrors(['code', 'name']);

        // Test duplicate code
        $response = $this->actingAs($this->adminUser)
            ->post(route('employment-types.store'), [
                'code' => 'DUP',
                'name' => 'Different Name',
            ]);

        $response->assertSessionHasErrors(['code']);
    }

    /**
     * Test view edit form.
     */
    public function test_user_can_view_edit_form(): void
    {
        $type = EmploymentType::create(['code' => 'EDIT', 'name' => 'To Edit']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('employment-types.edit', $type->id));

        $response->assertStatus(200);
        $response->assertViewIs('employment_type.edit');
        $response->assertSee('Edit Data Jenis Kepegawaian');
    }

    /**
     * Test update employment type.
     */
    public function test_user_can_update_employment_type(): void
    {
        $type = EmploymentType::create(['code' => 'UPD', 'name' => 'Original Name']);

        $response = $this->actingAs($this->adminUser)
            ->put(route('employment-types.update', $type->id), [
                'code' => 'UPD_NEW',
                'name' => 'Updated Name',
            ]);

        $response->assertRedirect(route('employment-types.index'));
        $this->assertDatabaseHas('employment_types', [
            'id' => $type->id,
            'code' => 'UPD_NEW',
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test update ignores own code uniqueness.
     */
    public function test_update_ignores_own_code_uniqueness(): void
    {
        $type = EmploymentType::create(['code' => 'SAME', 'name' => 'Original']);

        $response = $this->actingAs($this->adminUser)
            ->put(route('employment-types.update', $type->id), [
                'code' => 'SAME',
                'name' => 'Updated Name Only',
            ]);

        $response->assertRedirect(route('employment-types.index'));
        $this->assertDatabaseHas('employment_types', [
            'id' => $type->id,
            'code' => 'SAME',
            'name' => 'Updated Name Only',
        ]);
    }

    /**
     * Test delete employment type.
     */
    public function test_user_can_delete_employment_type(): void
    {
        $type = EmploymentType::create(['code' => 'DEL', 'name' => 'To Delete']);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('employment-types.destroy', $type->id));

        $response->assertRedirect(route('employment-types.index'));
        $this->assertDatabaseMissing('employment_types', [
            'id' => $type->id,
        ]);
    }
}
