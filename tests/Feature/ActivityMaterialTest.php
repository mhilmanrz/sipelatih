<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityMaterial;
use App\Models\Act\ActivityName;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $activityName = ActivityName::create(['name' => 'Pelatihan']);
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'start_date' => '2026-06-19',
            'end_date' => '2026-06-20',
        ]);
    }

    public function test_user_can_store_material(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('kegiatan.materi.store', $this->activity->id), [
                'name' => 'Materi Baru',
                'value' => 2.5,
            ]);

        $response->assertRedirect(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'materi']));

        $this->assertDatabaseHas('activity_materials', [
            'activity_id' => $this->activity->id,
            'name' => 'Materi Baru',
            'value' => 2.5,
        ]);
    }

    public function test_user_can_update_material(): void
    {
        $material = ActivityMaterial::create([
            'activity_id' => $this->activity->id,
            'name' => 'Materi Lama',
            'value' => 1.5,
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('kegiatan.materi.update', ['kegiatan' => $this->activity->id, 'id' => $material->id]), [
                'name' => 'Materi Diperbarui',
                'value' => 3.0,
            ]);

        $response->assertRedirect(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'materi']));

        $this->assertDatabaseHas('activity_materials', [
            'id' => $material->id,
            'name' => 'Materi Diperbarui',
            'value' => 3.0,
        ]);
    }

    public function test_user_can_destroy_material(): void
    {
        $material = ActivityMaterial::create([
            'activity_id' => $this->activity->id,
            'name' => 'Materi Hapus',
            'value' => 1.0,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('kegiatan.materi.destroy', ['kegiatan' => $this->activity->id, 'id' => $material->id]));

        $response->assertRedirect(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'materi']));

        $this->assertDatabaseMissing('activity_materials', [
            'id' => $material->id,
        ]);
    }
}
