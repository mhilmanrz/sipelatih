<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityScoreSetting;
use App\Models\Act\ActivityStatus;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create a user and assign 'pengusul' role
        $this->user = User::factory()->create();
        $this->user->assignRole('pengusul');
    }

    public function test_dashboard_shows_correct_dynamic_stats(): void
    {
        // 1. Activity with NO status record (should be counted as Draft)
        Activity::create();

        // 2. Activity with latest status = 'draft' (should be counted as Draft)
        $actDraft = Activity::create();
        ActivityStatus::create([
            'activity_id' => $actDraft->id,
            'status' => 'draft',
            'note' => 'Draft status',
        ]);

        // 3. Activity with latest status = 'submitted' and no revision history (should be counted as Tahap Pengajuan)
        $actSubmitted = Activity::create();
        ActivityStatus::create([
            'activity_id' => $actSubmitted->id,
            'status' => 'pending',
            'note' => 'Submitted first time',
        ]);

        // 4. Activity with latest status = 'submitted' and has revision in history (should be counted as Telah Perbaikan)
        $actResubmitted = Activity::create();
        ActivityStatus::create([
            'activity_id' => $actResubmitted->id,
            'status' => 'pending',
            'note' => 'First submission',
        ]);
        ActivityStatus::create([
            'activity_id' => $actResubmitted->id,
            'status' => 'revision',
            'note' => 'Need revision',
        ]);
        ActivityStatus::create([
            'activity_id' => $actResubmitted->id,
            'status' => 'pending',
            'note' => 'Resubmitted after revision',
        ]);

        // 5. Activity with latest status = 'revision' (should be counted as Butuh Perbaikan)
        $actRevision = Activity::create();
        ActivityStatus::create([
            'activity_id' => $actRevision->id,
            'status' => 'revision',
            'note' => 'Need revision',
        ]);

        // 6. Activity with latest status = 'accepted' with score settings (should be counted as Proses Penilaian & Disetujui)
        $actAcceptedWithScore = Activity::create();
        ActivityStatus::create([
            'activity_id' => $actAcceptedWithScore->id,
            'status' => 'completed',
            'note' => 'Accepted',
        ]);
        ActivityScoreSetting::create([
            'activity_id' => $actAcceptedWithScore->id,
            'passing_threshold' => 70,
        ]);

        // 7. Activity with latest status = 'accepted' without score settings (should be counted as Disetujui, but not Proses Penilaian)
        $actAcceptedWithoutScore = Activity::create();
        ActivityStatus::create([
            'activity_id' => $actAcceptedWithoutScore->id,
            'status' => 'completed',
            'note' => 'Accepted',
        ]);

        // Visit the dashboard
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert all calculated variables passed to the view are correct
        $response->assertViewHas('draftCount', 2);
        $response->assertViewHas('submittedCount', 1);
        $response->assertViewHas('telahPerbaikanCount', 1);
        $response->assertViewHas('prosesPenilaianCount', 1);
        $response->assertViewHas('revisionCount', 1);
        $response->assertViewHas('acceptedCount', 2);
        $response->assertViewHas('rejectedCount', 0);
    }
}
