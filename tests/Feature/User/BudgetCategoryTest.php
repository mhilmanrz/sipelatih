<?php

namespace Tests\Feature\User;

use App\Models\BudgetCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetCategoryTest extends TestCase
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
     * Test list budget categories and search functionality.
     */
    public function test_user_can_view_budget_categories_index(): void
    {
        $category1 = BudgetCategory::create(['code' => 'BC001', 'name' => 'Honorarium']);
        $category2 = BudgetCategory::create(['code' => 'BC002', 'name' => 'Transportasi']);

        // View index
        $response = $this->actingAs($this->adminUser)
            ->get(route('budget-categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('budget_categories.index');
        $response->assertSee('BC001');
        $response->assertSee('Honorarium');
        $response->assertSee('BC002');
        $response->assertSee('Transportasi');

        // Search by name
        $response = $this->actingAs($this->adminUser)
            ->get(route('budget-categories.index', ['q' => 'Honora']));
        $response->assertSee('Honorarium');
        $response->assertDontSee('Transportasi');

        // Search by code
        $response = $this->actingAs($this->adminUser)
            ->get(route('budget-categories.index', ['q' => 'BC002']));
        $response->assertSee('Transportasi');
        $response->assertDontSee('Honorarium');
    }

    /**
     * Test view create form.
     */
    public function test_user_can_view_create_form(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('budget-categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('budget_categories.create');
        $response->assertSee('Tambah Kategori Pagu');
    }

    /**
     * Test store new budget category.
     */
    public function test_user_can_store_budget_category(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('budget-categories.store'), [
                'code' => 'BCSTORE',
                'name' => 'Budget Category Store Test',
            ]);

        $response->assertRedirect(route('budget-categories.index'));
        $this->assertDatabaseHas('budget_categories', [
            'code' => 'BCSTORE',
            'name' => 'Budget Category Store Test',
        ]);
    }

    /**
     * Test store validation constraints.
     */
    public function test_store_validation_requires_code_and_name_and_code_must_be_unique(): void
    {
        BudgetCategory::create(['code' => 'DUP', 'name' => 'Existing']);

        // Test missing fields
        $response = $this->actingAs($this->adminUser)
            ->post(route('budget-categories.store'), []);

        $response->assertSessionHasErrors(['code', 'name']);

        // Test duplicate code
        $response = $this->actingAs($this->adminUser)
            ->post(route('budget-categories.store'), [
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
        $category = BudgetCategory::create(['code' => 'EDIT', 'name' => 'To Edit']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('budget-categories.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('budget_categories.edit');
        $response->assertSee('Edit Kategori Pagu');
    }

    /**
     * Test update budget category.
     */
    public function test_user_can_update_budget_category(): void
    {
        $category = BudgetCategory::create(['code' => 'UPD', 'name' => 'Original Name']);

        $response = $this->actingAs($this->adminUser)
            ->put(route('budget-categories.update', $category->id), [
                'code' => 'UPD_NEW',
                'name' => 'Updated Name',
            ]);

        $response->assertRedirect(route('budget-categories.index'));
        $this->assertDatabaseHas('budget_categories', [
            'id' => $category->id,
            'code' => 'UPD_NEW',
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test update ignores own code uniqueness.
     */
    public function test_update_ignores_own_code_uniqueness(): void
    {
        $category = BudgetCategory::create(['code' => 'SAME', 'name' => 'Original']);

        $response = $this->actingAs($this->adminUser)
            ->put(route('budget-categories.update', $category->id), [
                'code' => 'SAME',
                'name' => 'Updated Name Only',
            ]);

        $response->assertRedirect(route('budget-categories.index'));
        $this->assertDatabaseHas('budget_categories', [
            'id' => $category->id,
            'code' => 'SAME',
            'name' => 'Updated Name Only',
        ]);
    }

    /**
     * Test delete budget category.
     */
    public function test_user_can_delete_budget_category(): void
    {
        $category = BudgetCategory::create(['code' => 'DEL', 'name' => 'To Delete']);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('budget-categories.destroy', $category->id));

        $response->assertRedirect(route('budget-categories.index'));
        $this->assertDatabaseMissing('budget_categories', [
            'id' => $category->id,
        ]);
    }
}
