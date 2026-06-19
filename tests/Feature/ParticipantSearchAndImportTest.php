<?php

namespace Tests\Feature;

use App\Imports\ParticipantImport;
use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ParticipantSearchAndImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected Activity $activity;

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

        // Create activity
        $activityName = ActivityName::create(['name' => 'Pelatihan Import Test']);
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'name' => 'Pelatihan Import Test',
            'start_date' => '2026-05-17',
            'end_date' => '2026-05-20',
        ]);
    }

    /**
     * Test participant search by NIP (employee_id) does not crash and works.
     */
    public function test_user_can_search_available_participants_by_nip(): void
    {
        $targetUser = User::factory()->create([
            'name' => 'John Doe',
            'employee_id' => '198607122012122001',
        ]);

        // Hit the availableUsers route
        $response = $this->actingAs($this->adminUser)
            ->getJson(route('kegiatan.peserta.available-users', [
                'kegiatan' => $this->activity->id,
                'search' => '198607122012122001',
            ]));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $targetUser->id,
            'name' => 'John Doe',
            'employee_id' => '198607122012122001',
        ]);
    }

    /**
     * Test participant import using ParticipantImport collection import.
     */
    public function test_participant_import_works_with_employee_id(): void
    {
        $targetUser = User::factory()->create([
            'name' => 'Target User',
            'employee_id' => '198607122012122001',
        ]);

        $rows = new Collection([
            [
                'nip' => '198607122012122001',
            ],
        ]);

        $import = new ParticipantImport($this->activity->id);
        $import->collection($rows);

        $this->assertDatabaseHas('activity_participants', [
            'activity_id' => $this->activity->id,
            'user_id' => $targetUser->id,
        ]);
    }
}
