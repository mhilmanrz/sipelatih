<?php

namespace Tests\Feature;

use App\Models\Act\ActivitySpeaker;
use App\Models\User\Positions;
use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SuratTugasNarasumberRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_lampiran_narasumber_shows_institution_for_external_and_rank_for_internal(): void
    {
        $rank = Rank::create(['name' => 'Pembina', 'code' => 'IV/a']);
        $position = Positions::create(['name' => 'Kepala Bidang', 'code' => 'KB001']);

        $internalUser = User::factory()->create([
            'name' => 'Internal Narasumber',
            'rank_id' => $rank->id,
            'position_id' => $position->id,
        ]);
        $externalUser = User::factory()->create([
            'name' => 'External Narasumber',
            'is_external' => true,
            'institution' => 'RS Mitra Sehat',
            'external_position' => 'Dokter Spesialis',
        ]);

        $internalSpeaker = new ActivitySpeaker;
        $internalSpeaker->setRelation('user', $internalUser);

        $externalSpeaker = new ActivitySpeaker;
        $externalSpeaker->setRelation('user', $externalUser);

        $narasumber = new Collection([$internalSpeaker, $externalSpeaker]);

        $html = view('pdf.surat-tugas.lampiran-narasumber', [
            'narasumber' => $narasumber,
            'nomorSurat' => '001/ST/2026',
            'tanggalSuratFormatted' => '7 Juli 2026',
        ])->render();

        $this->assertStringContainsString('Pembina', $html);
        $this->assertStringContainsString('Kepala Bidang', $html);
        $this->assertStringContainsString('RS Mitra Sehat', $html);
        $this->assertStringContainsString('Dokter Spesialis', $html);
    }
}
