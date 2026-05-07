<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        AppSetting::set('nota_dinas_signer_position', 'Manajer Tim Kerja Pendidikan dan Pelatihan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        AppSetting::where('key', 'nota_dinas_signer_position')->delete();
    }
};
