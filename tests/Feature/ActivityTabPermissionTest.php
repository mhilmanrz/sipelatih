<?php

namespace Tests\Feature;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTabPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $pengusulUser;

    protected Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create admin user (superadmin)
        $this->adminUser = User::factory()->create([
            'email' => 'admin@sipelatih.test',
        ]);
        $this->adminUser->assignRole('superadmin');

        // Create pengusul user
        $this->pengusulUser = User::factory()->create([
            'email' => 'pengusul@sipelatih.test',
        ]);
        $this->pengusulUser->assignRole('pengusul');

        // Create activity name
        $activityName = ActivityName::create([
            'name' => 'Pelatihan Test Tab Permission',
        ]);

        // Create activity
        $this->activity = Activity::create([
            'activity_name_id' => $activityName->id,
            'name' => 'Pelatihan Test Tab Permission',
            'start_date' => '2026-05-17',
            'end_date' => '2026-05-20',
        ]);
    }

    /**
     * Test superadmin can see all tabs.
     */
    public function test_superadmin_can_see_all_tabs(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('kegiatan.show', $this->activity->id));

        $response->assertStatus(200);

        // Check if all tabs are displayed in the sidebar
        $response->assertSee('Kegiatan');
        $response->assertSee('Dokumen');
        $response->assertSee('Justifikasi');
        $response->assertSee('Sasaran Profesi');
        $response->assertSee('KAK');
        $response->assertSee('Materi');
        $response->assertSee('Narasumber');
        $response->assertSee('Peserta');
        $response->assertSee('Input Nilai');
        $response->assertSee('Pengiriman');
        $response->assertSee('Penilaian');
        $response->assertSee('Sertifikat');
    }

    /**
     * Test pengusul user cannot see restricted tabs in the sidebar.
     */
    public function test_pengusul_user_cannot_see_restricted_tabs_in_sidebar(): void
    {
        $response = $this->actingAs($this->pengusulUser)
            ->get(route('kegiatan.show', $this->activity->id));

        $response->assertStatus(200);

        // Permitted tabs
        $response->assertSee('Kegiatan');
        $response->assertSee('Dokumen');
        $response->assertSee('Justifikasi');
        $response->assertSee('Sasaran Profesi');
        $response->assertSee('KAK');
        $response->assertSee('Materi');
        $response->assertSee('Narasumber');
        $response->assertSee('Peserta');
        $response->assertSee('Pengiriman');

        // Restricted tabs
        $response->assertDontSee('Input Nilai');
        $response->assertDontSee('Penilaian');
        $response->assertDontSee('Sertifikat');
    }

    /**
     * Test accessing a forbidden tab via URL query fallback to first permitted tab.
     */
    public function test_accessing_forbidden_tab_falls_back_to_first_permitted_tab(): void
    {
        // Pengusul tries to access 'input-nilai' which is forbidden for them
        $response = $this->actingAs($this->pengusulUser)
            ->get(route('kegiatan.show', ['kegiatan' => $this->activity->id, 'tab' => 'input-nilai']));

        $response->assertStatus(200);

        // Check that they are shown the fallback tab content (e.g. Kegiatan)
        $response->assertSee('Informasi Kegiatan'); // Title of the 'kegiatan' tab
        $response->assertDontSee('Input Nilai Peserta'); // Title of the 'input-nilai' tab
    }

    /**
     * Test user with no tab permissions gets 403.
     */
    public function test_user_with_no_tab_permissions_gets_403(): void
    {
        // Create user with no roles/permissions
        $restrictedUser = User::factory()->create([
            'email' => 'restricted@sipelatih.test',
        ]);

        $response = $this->actingAs($restrictedUser)
            ->get(route('kegiatan.show', $this->activity->id));

        $response->assertStatus(403);
    }
}
