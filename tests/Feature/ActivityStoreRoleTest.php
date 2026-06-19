<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityCategory;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityScope;
use App\Models\Act\ActivityType;
use App\Models\Act\Batch;
use App\Models\Act\MaterialType;
use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityStoreRoleTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadminUser;

    protected User $perencanaanUser;

    protected User $pengusulUser;

    protected ActivityName $activityName;

    protected ActivityType $activityType;

    protected ActivityCategory $activityCategory;

    protected ActivityScope $activityScope;

    protected MaterialType $materialType;

    protected ActivityMethod $activityMethod;

    protected Batch $batch;

    protected ActivityFormat $activityFormat;

    protected Budget $budget;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create users with different roles
        $this->superadminUser = User::factory()->create();
        $this->superadminUser->assignRole('superadmin');

        $this->perencanaanUser = User::factory()->create();
        $this->perencanaanUser->assignRole('perencanaan');

        $this->pengusulUser = User::factory()->create();
        $this->pengusulUser->assignRole('pengusul');

        // Create reference models
        $this->activityName = ActivityName::create(['name' => 'Pelatihan']);
        $this->activityType = ActivityType::create(['name' => 'Tipe']);
        $this->activityCategory = ActivityCategory::create(['name' => 'Kategori']);
        $this->activityScope = ActivityScope::create(['name' => 'Lingkup']);
        $this->materialType = MaterialType::create(['name' => 'Materi']);
        $this->activityMethod = ActivityMethod::create(['name' => 'Metode']);
        $this->batch = Batch::create(['name' => 'Angkatan']);
        $this->activityFormat = ActivityFormat::create(['name' => 'Format']);

        $budgetCategory = BudgetCategory::create(['code' => 'BC01', 'name' => 'Kategori Anggaran']);
        $this->budget = Budget::create([
            'budget_category_id' => $budgetCategory->id,
            'year' => 2026,
            'rkkal_code' => 'RK01',
            'submark' => 'Submark',
            'total_amount' => 5000000,
        ]);
    }

    protected function getValidPayload(array $overrides = []): array
    {
        return array_merge([
            'activity_name_id' => $this->activityName->id,
            'activity_type_id' => $this->activityType->id,
            'activity_category_id' => $this->activityCategory->id,
            'activity_scope_id' => $this->activityScope->id,
            'material_type_id' => $this->materialType->id,
            'activity_method_id' => $this->activityMethod->id,
            'batch_id' => $this->batch->id,
            'activity_format_id' => $this->activityFormat->id,
            'budget_id' => $this->budget->id,
        ], $overrides);
    }

    public function test_pengusul_cannot_store_activity_with_budget_id(): void
    {
        $payload = $this->getValidPayload();

        $response = $this->actingAs($this->pengusulUser)
            ->post(route('kegiatan.store'), $payload);

        $response->assertRedirect(route('usulan-diklat'));

        $this->assertDatabaseHas('activities', [
            'activity_name_id' => $this->activityName->id,
            'budget_id' => null, // Forced to null
        ]);
    }

    public function test_perencanaan_can_store_activity_with_budget_id(): void
    {
        $payload = $this->getValidPayload();

        $response = $this->actingAs($this->perencanaanUser)
            ->post(route('kegiatan.store'), $payload);

        $response->assertRedirect(route('usulan-diklat'));

        $this->assertDatabaseHas('activities', [
            'activity_name_id' => $this->activityName->id,
            'budget_id' => $this->budget->id, // Allowed
        ]);
    }

    public function test_superadmin_can_store_activity_with_budget_id(): void
    {
        $payload = $this->getValidPayload();

        $response = $this->actingAs($this->superadminUser)
            ->post(route('kegiatan.store'), $payload);

        $response->assertRedirect(route('usulan-diklat'));

        $this->assertDatabaseHas('activities', [
            'activity_name_id' => $this->activityName->id,
            'budget_id' => $this->budget->id, // Allowed
        ]);
    }

    public function test_pengusul_cannot_update_activity_with_budget_id(): void
    {
        // First create activity with budget_id = null (since created by superadmin but we set budget_id as null)
        $activity = Activity::create(array_merge($this->getValidPayload(['budget_id' => null]), ['user_id' => $this->superadminUser->id]));

        $payload = $this->getValidPayload(); // contains budget_id

        $response = $this->actingAs($this->pengusulUser)
            ->put(route('kegiatan.update', $activity->id), $payload);

        $response->assertRedirect(route('usulan-diklat'));

        $activity->refresh();
        $this->assertNull($activity->budget_id); // Kept null, update ignored budget_id
    }

    public function test_perencanaan_can_update_activity_with_budget_id(): void
    {
        $activity = Activity::create(array_merge($this->getValidPayload(['budget_id' => null]), ['user_id' => $this->superadminUser->id]));

        $payload = $this->getValidPayload(); // contains budget_id

        $response = $this->actingAs($this->perencanaanUser)
            ->put(route('kegiatan.update', $activity->id), $payload);

        $response->assertRedirect(route('usulan-diklat'));

        $activity->refresh();
        $this->assertEquals($this->budget->id, $activity->budget_id); // Updated successfully
    }
}
