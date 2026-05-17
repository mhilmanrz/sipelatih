<?php

namespace Tests\Feature;

use App\Imports\BudgetImport;
use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PaguTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected BudgetCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create admin user
        $this->adminUser = User::factory()->create([
            'email' => 'admin@sipelatih.test',
        ]);
        $this->adminUser->assignRole('superadmin');

        // Create a budget category
        $this->category = BudgetCategory::create([
            'code' => 'DIPA',
            'name' => 'Daftar Isian Pelaksanaan Anggaran',
        ]);
    }

    /**
     * Test pagu index.
     */
    public function test_user_can_view_pagu_index(): void
    {
        Budget::create([
            'year' => 2026,
            'rkkal_code' => 'RKAKL-123',
            'budget_category_id' => $this->category->id,
            'submark' => 'Kegiatan Utama',
            'total_amount' => 100000000,
            'blocked_amount' => 10000000,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('pagu.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pagu');
        $response->assertSee('RKAKL-123');
    }

    /**
     * Test create pagu from form.
     */
    public function test_user_can_store_pagu(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('pagu.store'), [
                'year' => 2026,
                'rkkal_code' => 'RKAKL-FORM',
                'budget_category_id' => $this->category->id,
                'submark' => 'Form Input',
                'total_amount' => 50000000,
                'blocked_amount' => 5000000,
            ]);

        $response->assertRedirect(route('pagu.index'));
        $this->assertDatabaseHas('budgets', [
            'rkkal_code' => 'RKAKL-FORM',
            'total_amount' => 50000000,
            'blocked_amount' => 5000000,
            'remaining_amount' => 45000000, // Auto-calculated total_amount - blocked_amount
        ]);
    }

    /**
     * Test import pagu.
     */
    public function test_budget_import_saves_and_calculates_remaining_amount_and_submark(): void
    {
        $import = new BudgetImport;

        $collection = new Collection([
            [
                'tahun' => '2026',
                'no_rkakl' => 'RKAKL-IMPORT-1',
                'kode_kategori_pagu' => 'DIPA',
                'submark' => 'Import Submark A',
                'total_anggaran' => '120000000',
                'dana_blokir' => '20000000',
            ],
            [
                'tahun' => '2026',
                'no_rkakl' => 'RKAKL-IMPORT-2',
                'kode_kategori_pagu' => 'PNBP', // Test brand new category auto-creation
                'submark' => null, // Test nullable fallback to empty string
                'total_anggaran' => '80000000',
                'dana_blokir' => null, // Test fallback to 0
            ],
        ]);

        $import->collection($collection);

        // Verify DIPA is linked
        $this->assertDatabaseHas('budgets', [
            'rkkal_code' => 'RKAKL-IMPORT-1',
            'budget_category_id' => $this->category->id,
            'total_amount' => 120000000,
            'blocked_amount' => 20000000,
            'remaining_amount' => 100000000,
            'submark' => 'Import Submark A',
        ]);

        // Verify PNBP category was automatically created in the database
        $this->assertDatabaseHas('budget_categories', [
            'code' => 'PNBP',
            'name' => 'PNBP',
        ]);

        $newCategory = BudgetCategory::where('code', 'PNBP')->first();

        // Verify budget record is linked to the newly created category
        $this->assertDatabaseHas('budgets', [
            'rkkal_code' => 'RKAKL-IMPORT-2',
            'budget_category_id' => $newCategory->id,
            'total_amount' => 80000000,
            'blocked_amount' => 0,
            'remaining_amount' => 80000000,
            'submark' => '', // Checked fallback
        ]);
    }
}
