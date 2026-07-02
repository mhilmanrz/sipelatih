<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityStatus;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityBudgetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $perencanaanUser;

    protected User $penyelenggaraUser;

    protected User $pengusulUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->perencanaanUser = User::factory()->create();
        $this->perencanaanUser->assignRole('perencanaan');

        $this->penyelenggaraUser = User::factory()->create();
        $this->penyelenggaraUser->assignRole('penyelenggara');

        $this->pengusulUser = User::factory()->create();
        $this->pengusulUser->assignRole('pengusul');
    }

    protected function activityAtStage(string $stage): Activity
    {
        $activity = Activity::create(['user_id' => $this->pengusulUser->id]);
        ActivityStatus::create(['activity_id' => $activity->id, 'stage' => $stage, 'status' => 'pending']);

        return $activity;
    }

    public function test_perencanaan_can_set_budget_diterima_while_at_perencanaan_stage(): void
    {
        $activity = $this->activityAtStage('perencanaan');

        $response = $this->actingAs($this->perencanaanUser)
            ->post(route('kegiatan.budget_diterima', $activity->id), ['budget_diterima' => 1500000]);

        $response->assertRedirect(route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'kegiatan']));
        $this->assertEquals(1500000, $activity->fresh()->budget_diterima);
    }

    public function test_pengusul_cannot_set_budget_diterima(): void
    {
        $activity = $this->activityAtStage('perencanaan');

        $response = $this->actingAs($this->pengusulUser)
            ->post(route('kegiatan.budget_diterima', $activity->id), ['budget_diterima' => 1500000]);

        $response->assertForbidden();
        $this->assertNull($activity->fresh()->budget_diterima);
    }

    public function test_perencanaan_cannot_set_budget_diterima_outside_perencanaan_stage(): void
    {
        $activity = $this->activityAtStage('penyelenggara');

        $response = $this->actingAs($this->perencanaanUser)
            ->post(route('kegiatan.budget_diterima', $activity->id), ['budget_diterima' => 1500000]);

        $response->assertForbidden();
        $this->assertNull($activity->fresh()->budget_diterima);
    }

    public function test_penyelenggara_can_set_budget_diserap_while_at_penyelenggara_stage(): void
    {
        $activity = $this->activityAtStage('penyelenggara');

        $response = $this->actingAs($this->penyelenggaraUser)
            ->post(route('kegiatan.budget_diserap', $activity->id), ['budget_diserap' => 900000]);

        $response->assertRedirect(route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'kegiatan']));
        $this->assertEquals(900000, $activity->fresh()->budget_diserap);
    }

    public function test_penyelenggara_cannot_set_budget_diserap_outside_penyelenggara_stage(): void
    {
        $activity = $this->activityAtStage('perencanaan');

        $response = $this->actingAs($this->penyelenggaraUser)
            ->post(route('kegiatan.budget_diserap', $activity->id), ['budget_diserap' => 900000]);

        $response->assertForbidden();
        $this->assertNull($activity->fresh()->budget_diserap);
    }
}
