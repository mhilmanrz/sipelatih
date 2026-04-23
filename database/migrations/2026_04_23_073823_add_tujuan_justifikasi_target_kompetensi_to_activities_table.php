<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->text('tujuan')->nullable()->after('collaboration_inst');
            $table->text('justifikasi')->nullable()->after('tujuan');
            $table->text('target_kompetensi')->nullable()->after('justifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['tujuan', 'justifikasi', 'target_kompetensi']);
        });
    }
};
