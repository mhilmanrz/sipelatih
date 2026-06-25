<?php

namespace Tests\Feature\User;

use App\Models\Act\Activity;
use App\Models\Act\ActivityMaterial;
use App\Models\Act\ActivityModerator;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivitySpeaker;
use App\Models\Act\ActivityStatus;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyActivityTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected ActivityName $activityName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->activityName = ActivityName::create(['name' => 'Pelatihan Kepemimpinan']);
    }

    public function test_user_can_view_my_activities_as_participant(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        ActivityParticipant::create([
            'activity_id' => $activity->id,
            'user_id' => $this->user->id,
            'is_passed' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('my-activities.index'));

        $response->assertStatus(200);
        $response->assertSee('Pelatihan Kepemimpinan');
    }

    public function test_user_can_view_my_activities_as_speaker(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        $material = ActivityMaterial::create([
            'activity_id' => $activity->id,
            'name' => 'Materi Kepemimpinan',
            'value' => 2,
        ]);

        ActivitySpeaker::create([
            'activity_material_id' => $material->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('my-activities.index'));

        $response->assertStatus(200);
        $response->assertSee('Pelatihan Kepemimpinan');
    }

    public function test_user_can_view_my_activities_as_moderator(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        $material = ActivityMaterial::create([
            'activity_id' => $activity->id,
            'name' => 'Materi Kepemimpinan',
            'value' => 2,
        ]);

        ActivityModerator::create([
            'activity_material_id' => $material->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('my-activities.index'));

        $response->assertStatus(200);
        $response->assertSee('Pelatihan Kepemimpinan');
    }

    public function test_user_can_view_my_activity_detail(): void
    {
        $activity = Activity::create([
            'activity_name_id' => $this->activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'accepted',
        ]);

        $material = ActivityMaterial::create([
            'activity_id' => $activity->id,
            'name' => 'Materi Kepemimpinan',
            'value' => 2,
        ]);

        ActivitySpeaker::create([
            'activity_material_id' => $material->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('my-activities.show', $activity->id));

        $response->assertStatus(200);
        $response->assertSee('Detail Kegiatan');
        $response->assertSee('Pelatihan Kepemimpinan');
    }

    public function test_administrative_accounts_cannot_access_my_activities(): void
    {
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $adminRoles = ['superadmin', 'perencanaan', 'penyelenggara', 'evaluasi', 'pengusul'];

        foreach ($adminRoles as $role) {
            $adminUser = User::factory()->create();
            $adminUser->assignRole($role);

            $response = $this->actingAs($adminUser)
                ->get(route('my-activities.index'));

            $response->assertStatus(403);
        }
    }
}
