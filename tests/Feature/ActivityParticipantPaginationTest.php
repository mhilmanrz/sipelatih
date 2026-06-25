<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityParticipantPaginationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('superadmin');

        $activityName = ActivityName::create(['name' => 'Pelatihan']);
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);
    }

    public function test_user_can_view_paginated_participants_by_default_limit(): void
    {
        // Create 15 participants
        $users = User::factory()->count(15)->create();
        foreach ($users as $user) {
            ActivityParticipant::create([
                'activity_id' => $this->activity->id,
                'user_id' => $user->id,
            ]);
        }

        $response = $this->actingAs($this->adminUser)
            ->get(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'peserta']));

        $response->assertStatus(200);

        // Should see the pagination variable in view data
        $participants = $response->viewData('participants');
        $this->assertNotNull($participants);
        $this->assertEquals(10, $participants->perPage()); // Default 10
        $this->assertEquals(15, $participants->total());
        $this->assertEquals(10, $participants->count()); // Page 1 has 10 items
    }

    public function test_user_can_change_entries_limit_for_participants(): void
    {
        // Create 15 participants
        $users = User::factory()->count(15)->create();
        foreach ($users as $user) {
            ActivityParticipant::create([
                'activity_id' => $this->activity->id,
                'user_id' => $user->id,
            ]);
        }

        $response = $this->actingAs($this->adminUser)
            ->get(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'peserta', 'entries' => 25]));

        $response->assertStatus(200);

        $participants = $response->viewData('participants');
        $this->assertEquals(25, $participants->perPage());
        $this->assertEquals(15, $participants->count()); // Page 1 has all 15 items since entries = 25
    }

    public function test_user_can_search_participants_in_paginated_view(): void
    {
        $user1 = User::factory()->create(['name' => 'Alice Margatroid', 'employee_id' => '11111']);
        $user2 = User::factory()->create(['name' => 'Bob Smith', 'employee_id' => '22222']);

        ActivityParticipant::create(['activity_id' => $this->activity->id, 'user_id' => $user1->id]);
        ActivityParticipant::create(['activity_id' => $this->activity->id, 'user_id' => $user2->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('kegiatan.show', [
                'kegiatan' => $this->activity->id,
                'tab' => 'peserta',
                'search_peserta' => 'Alice',
            ]));

        $response->assertStatus(200);

        $participants = $response->viewData('participants');
        $this->assertEquals(1, $participants->total());
        $this->assertEquals($user1->id, $participants->first()->user_id);
    }
}
