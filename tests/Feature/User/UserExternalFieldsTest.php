<?php

namespace Tests\Feature\User;

use App\Models\User\Positions;
use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserExternalFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_external_defaults_to_false_and_casts_to_bool(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->is_external);
        $this->assertIsBool($user->fresh()->is_external);
    }

    public function test_internal_user_document_labels_use_rank_and_position(): void
    {
        $rank = Rank::create(['name' => 'Penata', 'code' => 'III/c']);
        $position = Positions::create(['name' => 'Kepala Seksi', 'code' => 'KS001']);

        $user = User::factory()->create([
            'rank_id' => $rank->id,
            'position_id' => $position->id,
        ]);

        $this->assertSame('Penata', $user->documentRankOrInstitution());
        $this->assertSame('Kepala Seksi', $user->documentPosition());
    }

    public function test_external_user_document_labels_use_institution_and_external_position(): void
    {
        $user = User::factory()->create([
            'is_external' => true,
            'institution' => 'Universitas Indonesia',
            'external_position' => 'Dosen',
        ]);

        $this->assertSame('Universitas Indonesia', $user->documentRankOrInstitution());
        $this->assertSame('Dosen', $user->documentPosition());
    }

    public function test_document_labels_fall_back_to_dash_when_missing(): void
    {
        $external = User::factory()->create(['is_external' => true]);
        $internal = User::factory()->create();

        $this->assertSame('-', $external->documentRankOrInstitution());
        $this->assertSame('-', $external->documentPosition());
        $this->assertSame('-', $internal->documentRankOrInstitution());
        $this->assertSame('-', $internal->documentPosition());
    }
}
