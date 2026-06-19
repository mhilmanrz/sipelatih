<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ActivityKakUploadTest extends TestCase
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

        // Create activity name
        $activityName = ActivityName::create([
            'name' => 'Pelatihan KAK Test',
        ]);

        // Create activity
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'name' => 'Pelatihan KAK Test',
            'start_date' => '2026-05-17',
            'end_date' => '2026-05-20',
        ]);
    }

    public function test_user_can_upload_valid_kak_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test-kak.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->adminUser)
            ->post(route('kegiatan.upload-kak', $this->activity->id), [
                'kak_file' => $file,
            ]);

        $response->assertRedirect(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'kak']));
        $response->assertSessionHas('success', 'Dokumen KAK berhasil diunggah.');

        $this->assertDatabaseHas('activity_kak_files', [
            'activity_id' => $this->activity->id,
        ]);

        $kakFile = $this->activity->fresh()->activityKakFiles->first();
        $this->assertNotNull($kakFile);
        Storage::disk('public')->assertExists($kakFile->url);
    }

    public function test_user_cannot_upload_invalid_file_extension(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test-image.png', 100, 'image/png');

        $response = $this->actingAs($this->adminUser)
            ->post(route('kegiatan.upload-kak', $this->activity->id), [
                'kak_file' => $file,
            ]);

        $response->assertSessionHasErrors(['kak_file']);
        $this->assertDatabaseMissing('activity_kak_files', [
            'activity_id' => $this->activity->id,
        ]);
    }

    public function test_user_cannot_upload_file_exceeding_max_size(): void
    {
        Storage::fake('public');

        // 11 MB file
        $file = UploadedFile::fake()->create('heavy-kak.pdf', 11 * 1024, 'application/pdf');

        $response = $this->actingAs($this->adminUser)
            ->post(route('kegiatan.upload-kak', $this->activity->id), [
                'kak_file' => $file,
            ]);

        $response->assertSessionHasErrors(['kak_file']);
        $this->assertDatabaseMissing('activity_kak_files', [
            'activity_id' => $this->activity->id,
        ]);
    }
}
