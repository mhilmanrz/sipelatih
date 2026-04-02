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
            $table->unsignedInteger('quota_participant')->nullable()->after('collaboration_inst');
            $table->foreignId('fund_source_id')->nullable()->after('quota_participant')->constrained('fund_sources')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['fund_source_id']);
            $table->dropColumn(['fund_source_id', 'quota_participant']);
        });
    }
};
