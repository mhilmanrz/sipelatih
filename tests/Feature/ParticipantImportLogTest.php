<?php

namespace Tests\Feature;

use App\Imports\ParticipantImport;
use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ParticipantImportLog;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ParticipantImportLogTest extends TestCase
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
        $activityName = ActivityName::create(['name' => 'Pelatihan Log Test']);
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'name' => 'Pelatihan Log Test',
            'start_date' => '2026-05-17',
            'end_date' => '2026-05-20',
        ]);
    }

    /**
     * Test successful import creates log with status completed and correct counts.
     */
    public function test_successful_import_creates_completed_log(): void
    {
        $user1 = User::factory()->create(['employee_id' => '1111111111']);
        $user2 = User::factory()->create(['employee_id' => '2222222222']);

        $log = ParticipantImportLog::create([
            'activity_id' => $this->activity->id,
            'filename' => 'valid_participants.xlsx',
            'status' => 'pending',
        ]);

        $rows = new Collection([
            ['nip' => '1111111111', 'nama_opsional' => 'User One'],
            ['nip' => '2222222222', 'nama_opsional' => 'User Two'],
        ]);

        $import = new ParticipantImport($this->activity->id, $log->id);
        $import->collection($rows);

        $log->refresh();

        $this->assertEquals('completed', $log->status);
        $this->assertEquals(2, $log->total_rows);
        $this->assertEquals(2, $log->success_count);
        $this->assertEquals(0, $log->failed_count);
        $this->assertEmpty($log->errors);

        $this->assertDatabaseHas('activity_participants', [
            'activity_id' => $this->activity->id,
            'user_id' => $user1->id,
        ]);
        $this->assertDatabaseHas('activity_participants', [
            'activity_id' => $this->activity->id,
            'user_id' => $user2->id,
        ]);
    }

    /**
     * Test import with errors logs reasons.
     */
    public function test_import_with_validation_errors_records_failures(): void
    {
        // $user1 exists
        $user1 = User::factory()->create(['employee_id' => '1111111111']);
        // $user2 does not exist

        // Add user1 already as participant
        ActivityParticipant::create([
            'activity_id' => $this->activity->id,
            'user_id' => $user1->id,
            'is_passed' => false,
        ]);

        $log = ParticipantImportLog::create([
            'activity_id' => $this->activity->id,
            'filename' => 'mixed_participants.xlsx',
            'status' => 'pending',
        ]);

        $rows = new Collection([
            ['nip' => '1111111111', 'nama_opsional' => 'Already Participant'],
            ['nip' => '9999999999', 'nama_opsional' => 'Not Found User'],
            ['nip' => '', 'nama_opsional' => 'Empty NIP'],
        ]);

        $import = new ParticipantImport($this->activity->id, $log->id);
        $import->collection($rows);

        $log->refresh();

        $this->assertEquals('completed', $log->status);
        $this->assertEquals(3, $log->total_rows);
        $this->assertEquals(0, $log->success_count);
        $this->assertEquals(3, $log->failed_count);
        $this->assertCount(3, $log->errors);

        $this->assertEquals(2, $log->errors[0]['row']);
        $this->assertStringContainsString('sudah terdaftar', $log->errors[0]['reason']);

        $this->assertEquals(3, $log->errors[1]['row']);
        $this->assertStringContainsString('tidak ditemukan', $log->errors[1]['reason']);

        $this->assertEquals(4, $log->errors[2]['row']);
        $this->assertStringContainsString('NIP kosong', $log->errors[2]['reason']);
    }

    /**
     * Test that invalid Excel heading fails the import.
     */
    public function test_invalid_excel_heading_throws_exception_and_fails_log(): void
    {
        $log = ParticipantImportLog::create([
            'activity_id' => $this->activity->id,
            'filename' => 'bad_headings.xlsx',
            'status' => 'pending',
        ]);

        $rows = new Collection([
            ['wrong_column' => '12345'],
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Kolom 'NIP' tidak ditemukan");

        $import = new ParticipantImport($this->activity->id, $log->id);
        $import->collection($rows);
    }
}
