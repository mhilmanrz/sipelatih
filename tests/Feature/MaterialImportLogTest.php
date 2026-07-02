<?php

namespace Tests\Feature;

use App\Imports\MaterialImport;
use App\Models\Act\Activity;
use App\Models\Act\ActivityMaterial;
use App\Models\Act\ActivityName;
use App\Models\Act\MaterialImportLog;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class MaterialImportLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->adminUser = User::factory()->create([
            'email' => 'admin@sipelatih.test',
        ]);
        $this->adminUser->assignRole('superadmin');

        $activityName = ActivityName::create(['name' => 'Pelatihan Materi Log Test']);
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'name' => 'Pelatihan Materi Log Test',
            'start_date' => '2026-05-17',
            'end_date' => '2026-05-20',
        ]);
    }

    public function test_successful_import_creates_completed_log(): void
    {
        $log = MaterialImportLog::create([
            'activity_id' => $this->activity->id,
            'filename' => 'valid_materials.xlsx',
            'status' => 'pending',
        ]);

        $rows = new Collection([
            ['nama_materi' => 'Pengantar Kebijakan', 'menit' => 90],
            ['nama_materi' => 'Praktik Lapangan', 'menit' => 135],
        ]);

        $import = new MaterialImport($this->activity->id, $log->id);
        $import->collection($rows);

        $log->refresh();

        $this->assertEquals('completed', $log->status);
        $this->assertEquals(2, $log->total_rows);
        $this->assertEquals(2, $log->success_count);
        $this->assertEquals(0, $log->failed_count);
        $this->assertEmpty($log->errors);

        $this->assertDatabaseHas('activity_materials', [
            'activity_id' => $this->activity->id,
            'name' => 'Pengantar Kebijakan',
            'value' => 90,
        ]);
        $this->assertDatabaseHas('activity_materials', [
            'activity_id' => $this->activity->id,
            'name' => 'Praktik Lapangan',
            'value' => 135,
        ]);
    }

    public function test_import_with_validation_errors_records_failures(): void
    {
        ActivityMaterial::create([
            'activity_id' => $this->activity->id,
            'name' => 'Sudah Ada',
            'value' => 60,
        ]);

        $log = MaterialImportLog::create([
            'activity_id' => $this->activity->id,
            'filename' => 'mixed_materials.xlsx',
            'status' => 'pending',
        ]);

        $rows = new Collection([
            ['nama_materi' => '', 'menit' => 60],
            ['nama_materi' => 'Materi Tanpa Menit', 'menit' => 0],
            ['nama_materi' => 'Materi Menit Bukan Angka', 'menit' => 'abc'],
        ]);

        $import = new MaterialImport($this->activity->id, $log->id);
        $import->collection($rows);

        $log->refresh();

        $this->assertEquals('completed', $log->status);
        $this->assertEquals(3, $log->total_rows);
        $this->assertEquals(0, $log->success_count);
        $this->assertEquals(3, $log->failed_count);
        $this->assertCount(3, $log->errors);

        $this->assertEquals(2, $log->errors[0]['row']);
        $this->assertStringContainsString('Nama Materi kosong', $log->errors[0]['reason']);

        $this->assertEquals(3, $log->errors[1]['row']);
        $this->assertStringContainsString('MENIT wajib diisi', $log->errors[1]['reason']);

        $this->assertEquals(4, $log->errors[2]['row']);
        $this->assertStringContainsString('MENIT wajib diisi', $log->errors[2]['reason']);
    }

    public function test_invalid_excel_heading_throws_exception_and_fails_log(): void
    {
        $log = MaterialImportLog::create([
            'activity_id' => $this->activity->id,
            'filename' => 'bad_headings.xlsx',
            'status' => 'pending',
        ]);

        $rows = new Collection([
            ['wrong_column' => 'foo'],
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Kolom 'NAMA_MATERI' tidak ditemukan");

        $import = new MaterialImport($this->activity->id, $log->id);
        $import->collection($rows);
    }
}
