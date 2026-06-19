<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityCategory;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityScope;
use App\Models\Act\ActivityType;
use App\Models\Act\Batch;
use App\Models\Act\MaterialType;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityNameSearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_requires_authentication(): void
    {
        $response = $this->getJson(route('activity-names.search'));
        $response->assertStatus(401);
    }

    public function test_can_search_activity_names_by_query_and_year(): void
    {
        $act1 = ActivityName::create([
            'name' => 'Pelatihan Jantung Sehat',
            'year' => 2026,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-05',
        ]);

        $act2 = ActivityName::create([
            'name' => 'Pelatihan Paru Sehat',
            'year' => 2026,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $act3 = ActivityName::create([
            'name' => 'Pelatihan Jantung Lama',
            'year' => 2025,
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-05',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('activity-names.search', ['q' => 'Jantung', 'year' => 2026]));

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $act1->id, 'name' => 'Pelatihan Jantung Sehat']);
    }

    public function test_excludes_taken_activity_names_unless_included(): void
    {
        $act = ActivityName::create([
            'name' => 'Pelatihan Khusus',
            'year' => 2026,
        ]);

        // Create reference models for Activity creation
        $type = ActivityType::create(['name' => 'Tipe']);
        $cat = ActivityCategory::create(['name' => 'Kategori']);
        $scope = ActivityScope::create(['name' => 'Lingkup']);
        $mat = MaterialType::create(['name' => 'Materi']);
        $method = ActivityMethod::create(['name' => 'Metode']);
        $batch = Batch::create(['name' => 'Angkatan']);
        $format = ActivityFormat::create(['name' => 'Format']);

        // Create an activity referencing this activity name
        Activity::create([
            'activity_name_id' => $act->id,
            'activity_type_id' => $type->id,
            'activity_category_id' => $cat->id,
            'activity_scope_id' => $scope->id,
            'material_type_id' => $mat->id,
            'activity_method_id' => $method->id,
            'batch_id' => $batch->id,
            'activity_format_id' => $format->id,
            'user_id' => $this->user->id,
        ]);

        // Search normally - should be excluded
        $response = $this->actingAs($this->user)
            ->getJson(route('activity-names.search', ['q' => 'Khusus', 'year' => 2026]));

        $response->assertStatus(200)
            ->assertJsonCount(0);

        // Search with include_id - should be included
        $response = $this->actingAs($this->user)
            ->getJson(route('activity-names.search', [
                'q' => 'Khusus',
                'year' => 2026,
                'include_id' => $act->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $act->id, 'name' => 'Pelatihan Khusus']);
    }
}
