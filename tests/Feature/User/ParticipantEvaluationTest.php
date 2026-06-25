<?php

namespace Tests\Feature\User;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ParticipantEvaluation;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected ActivityName $activityName1;

    protected ActivityName $activityName2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->activityName1 = ActivityName::create(['name' => 'Pelatihan Keselamatan Kerja']);
        $this->activityName2 = ActivityName::create(['name' => 'Workshop IT Security']);
    }

    public function test_user_can_view_my_evaluations_index_page_with_stats(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName1->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);

        $participant = ActivityParticipant::create([
            'activity_id' => $activity->id,
            'user_id' => $this->user->id,
            'is_passed' => true,
        ]);

        // Create 3 evaluations: 1 completed, 2 pending
        ParticipantEvaluation::create([
            'activity_participant_id' => $participant->id,
            'evaluation_type' => 1,
            'form_type' => 'activity',
            'submitted_at' => now(),
        ]);

        ParticipantEvaluation::create([
            'activity_participant_id' => $participant->id,
            'evaluation_type' => 1,
            'form_type' => 'speaker',
            'submitted_at' => null,
        ]);

        ParticipantEvaluation::create([
            'activity_participant_id' => $participant->id,
            'evaluation_type' => 2,
            'form_type' => 'activity',
            'submitted_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('my-evaluations.index'));

        $response->assertStatus(200);
        $response->assertSee('Evaluasi Saya');
        $response->assertSee('Pelatihan Keselamatan Kerja');

        // Check stats
        $response->assertSee('Total Evaluasi');
        $response->assertSee('3'); // Total
        $response->assertSee('Sudah Diisi');
        $response->assertSee('1'); // Completed
        $response->assertSee('Belum Diisi');
        $response->assertSee('2'); // Pending
        $response->assertSee('33%'); // Rate
    }

    public function test_user_can_filter_evaluations_by_activity(): void
    {
        $activityA = Activity::create([
            'activity_name_id' => $this->activityName1->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);

        $activityB = Activity::create([
            'activity_name_id' => $this->activityName2->id,
            'start_date' => '2026-06-21',
            'end_date' => '2026-06-22',
        ]);

        $participantA = ActivityParticipant::create([
            'activity_id' => $activityA->id,
            'user_id' => $this->user->id,
            'is_passed' => true,
        ]);

        $participantB = ActivityParticipant::create([
            'activity_id' => $activityB->id,
            'user_id' => $this->user->id,
            'is_passed' => true,
        ]);

        // Activity A has 1 completed evaluation
        ParticipantEvaluation::create([
            'activity_participant_id' => $participantA->id,
            'evaluation_type' => 1,
            'form_type' => 'activity',
            'submitted_at' => now(),
        ]);

        // Activity B has 1 pending evaluation
        ParticipantEvaluation::create([
            'activity_participant_id' => $participantB->id,
            'evaluation_type' => 1,
            'form_type' => 'activity',
            'submitted_at' => null,
        ]);

        // Filter by Activity A
        $response = $this->actingAs($this->user)
            ->get(route('my-evaluations.index', ['activity_id' => $activityA->id]));

        $response->assertStatus(200);
        $response->assertSee('Pelatihan Keselamatan Kerja');

        $evaluationsA = $response->original->getData()['evaluations'];
        $this->assertCount(1, $evaluationsA);
        $this->assertEquals($activityA->id, $evaluationsA->first()->participant->activity_id);

        // Check stats for A
        $response->assertSee('1'); // Total for A
        $response->assertSee('100%'); // Rate for A

        // Filter by Activity B
        $response = $this->actingAs($this->user)
            ->get(route('my-evaluations.index', ['activity_id' => $activityB->id]));

        $response->assertStatus(200);
        $response->assertSee('Workshop IT Security');

        $evaluationsB = $response->original->getData()['evaluations'];
        $this->assertCount(1, $evaluationsB);
        $this->assertEquals($activityB->id, $evaluationsB->first()->participant->activity_id);

        // Check stats for B
        $response->assertSee('1'); // Total for B
        $response->assertSee('0%'); // Rate for B
    }

    public function test_administrative_accounts_cannot_access_my_evaluations(): void
    {
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $adminRoles = ['superadmin', 'perencanaan', 'penyelenggara', 'evaluasi', 'pengusul'];

        foreach ($adminRoles as $role) {
            $adminUser = User::factory()->create();
            $adminUser->assignRole($role);

            $response = $this->actingAs($adminUser)
                ->get(route('my-evaluations.index'));

            $response->assertStatus(403);
        }
    }
}
