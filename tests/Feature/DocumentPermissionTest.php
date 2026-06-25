<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $userWithoutPermissions;

    protected User $userWithPermissions;

    protected Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->userWithoutPermissions = User::factory()->create();
        $this->userWithPermissions = User::factory()->create();
        $this->userWithPermissions->assignRole('pengusul');

        $activityName = ActivityName::create(['name' => 'Pelatihan']);
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);
    }

    public function test_user_without_permission_cannot_access_documents(): void
    {
        // 1. Formulir
        $response = $this->actingAs($this->userWithoutPermissions)
            ->get(route('kegiatan.pdf.formulir', $this->activity->id));
        $response->assertStatus(403);

        // 2. Nota Dinas
        $response = $this->actingAs($this->userWithoutPermissions)
            ->get(route('kegiatan.nota-dinas.pdf', $this->activity->id));
        $response->assertStatus(403);

        // 3. Surat Pemanggilan
        $response = $this->actingAs($this->userWithoutPermissions)
            ->get(route('kegiatan.surat-pemanggilan.pdf', $this->activity->id));
        $response->assertStatus(403);

        // 4. Surat Tugas
        $response = $this->actingAs($this->userWithoutPermissions)
            ->get(route('kegiatan.surat-tugas.pdf', $this->activity->id));
        $response->assertStatus(403);
    }

    public function test_user_with_permission_can_access_documents(): void
    {
        // 1. Formulir
        $response = $this->actingAs($this->userWithPermissions)
            ->get(route('kegiatan.pdf.formulir', $this->activity->id));
        $response->assertStatus(200);

        // 2. Nota Dinas
        $response = $this->actingAs($this->userWithPermissions)
            ->get(route('kegiatan.nota-dinas.pdf', $this->activity->id));
        $response->assertStatus(200);

        // 3. Surat Pemanggilan
        $response = $this->actingAs($this->userWithPermissions)
            ->get(route('kegiatan.surat-pemanggilan.pdf', $this->activity->id));
        $response->assertStatus(200);

        // 4. Surat Tugas
        $response = $this->actingAs($this->userWithPermissions)
            ->get(route('kegiatan.surat-tugas.pdf', $this->activity->id));
        $response->assertStatus(200);
    }
}
