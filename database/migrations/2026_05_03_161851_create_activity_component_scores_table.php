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
        Schema::create('activity_component_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_participant_id')->constrained('activity_participants')->onDelete('cascade');
            $table->foreignId('activity_score_component_id')->constrained('activity_score_components')->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['activity_participant_id', 'activity_score_component_id'], 'acs_participant_component_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_component_scores');
    }
};
