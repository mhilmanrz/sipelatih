<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityEvaluation;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityStatus;
use App\Models\Act\EvaluationCriteria;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected ActivityName $activityName;

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

        // Create default activity name
        $this->activityName = ActivityName::create([
            'name' => 'Pelatihan Kepemimpinan Administrator',
        ]);
    }

    /**
     * Test view evaluation criteria index.
     */
    public function test_user_can_view_evaluation_criteria_index(): void
    {
        EvaluationCriteria::create([
            'code' => 'K1-01',
            'name' => 'Kriteria Kepuasan Layanan',
            'evaluation_type' => 1,
            'is_fillable' => false,
            'type' => 'string',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('evaluation-criteria.index'));

        $response->assertStatus(200);
        $response->assertViewIs('evaluation_criteria.index');
        $response->assertSee('K1-01');
        $response->assertSee('Kriteria Kepuasan Layanan');
    }

    /**
     * Test store evaluation criteria.
     */
    public function test_user_can_store_evaluation_criteria(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('evaluation-criteria.store'), [
                'code' => 'K1-NEW',
                'name' => 'Kriteria Kognitif Baru',
                'evaluation_type' => 1,
                'form_type' => 'activity',
                'type' => 'rating',
                'order' => 5,
            ]);

        $response->assertRedirect(route('evaluation-criteria.index'));
        $this->assertDatabaseHas('evaluation_criteria', [
            'code' => 'K1-NEW',
            'name' => 'Kriteria Kognitif Baru',
            'evaluation_type' => 1,
            'form_type' => 'activity',
            'type' => 'rating',
        ]);
    }

    /**
     * Test view evaluations index containing accepted activities.
     */
    public function test_user_can_view_activity_evaluations_index(): void
    {
        // Create activity
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-05-17',
            'end_date' => '2026-05-20',
        ]);

        // Activity status must be accepted
        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
            'note' => 'Disetujui',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('evaluations.index', ['year' => 2026, 'tab' => 1]));

        $response->assertStatus(200);
        $response->assertViewIs('evaluations.index');
        $response->assertSee('Pelatihan Kepemimpinan Administrator');
    }

    /**
     * Test user can successfully evaluate level 1.
     */
    public function test_user_can_evaluate_activity_level_1(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-05-17',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        $criteria = EvaluationCriteria::create([
            'code' => 'K1-TEST',
            'name' => 'Kriteria Test Level 1',
            'evaluation_type' => 1,
            'is_fillable' => true,
            'type' => 'number',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('evaluations.store', $activity->id), [
                'evaluation_type' => 1,
                'is_passed' => 1,
                'notes' => 'Penyelenggaraan sangat baik dan memuaskan.',
                'criteria' => [
                    $criteria->id => [
                        'is_passed' => 1,
                        'value' => '85.5',
                    ],
                ],
            ]);

        $response->assertRedirect(route('evaluations.show', $activity->id));
        $this->assertDatabaseHas('activity_evaluations', [
            'activity_id' => $activity->id,
            'evaluation_type' => 1,
            'is_passed' => true,
            'notes' => 'Penyelenggaraan sangat baik dan memuaskan.',
        ]);

        $this->assertDatabaseHas('activity_evaluation_criteria', [
            'evaluation_criteria_id' => $criteria->id,
            'value' => '85.5',
            'is_passed' => true,
        ]);
    }

    /**
     * Test user cannot evaluate level 2 without level 1 passed.
     */
    public function test_user_cannot_evaluate_level_2_without_passing_level_1(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-05-17',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        $criteria = EvaluationCriteria::create([
            'code' => 'K2-TEST',
            'name' => 'Kriteria Test Level 2',
            'evaluation_type' => 2,
            'is_fillable' => false,
            'type' => 'string',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('evaluations.show', $activity->id))
            ->post(route('evaluations.store', $activity->id), [
                'evaluation_type' => 2,
                'is_passed' => 1,
                'notes' => 'Rekomendasi level 2.',
                'criteria' => [
                    $criteria->id => [
                        'is_passed' => 1,
                        'value' => null,
                    ],
                ],
            ]);

        $response->assertRedirect(route('evaluations.show', $activity->id));
        $response->assertSessionHasErrors(['error']);

        $this->assertDatabaseMissing('activity_evaluations', [
            'activity_id' => $activity->id,
            'evaluation_type' => 2,
        ]);
    }

    /**
     * Test user can successfully evaluate level 2 after passing level 1.
     */
    public function test_user_can_evaluate_level_2_after_passing_level_1(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-05-17',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        // First pass level 1
        $eval1 = ActivityEvaluation::create([
            'activity_id' => $activity->id,
            'evaluation_type' => 1,
            'is_passed' => true,
            'evaluated_by' => $this->adminUser->id,
            'evaluated_at' => now(),
        ]);

        $criteria = EvaluationCriteria::create([
            'code' => 'K2-TEST',
            'name' => 'Kriteria Test Level 2',
            'evaluation_type' => 2,
            'is_fillable' => false,
            'type' => 'string',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('evaluations.store', $activity->id), [
                'evaluation_type' => 2,
                'is_passed' => 1,
                'notes' => 'Lulus evaluasi tingkat 2.',
                'criteria' => [
                    $criteria->id => [
                        'is_passed' => 1,
                        'value' => null,
                    ],
                ],
            ]);

        $response->assertRedirect(route('evaluations.show', $activity->id));
        $this->assertDatabaseHas('activity_evaluations', [
            'activity_id' => $activity->id,
            'evaluation_type' => 2,
            'is_passed' => true,
        ]);
    }
}
