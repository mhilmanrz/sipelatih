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
        Schema::create('participant_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_participant_id')->constrained('activity_participants')->onDelete('cascade');
            $table->unsignedTinyInteger('evaluation_type'); // 1 atau 3
            $table->enum('form_type', ['speaker', 'activity'])->nullable(); // level 1 only
            $table->foreignId('activity_speaker_id')->nullable()->constrained('activity_speakers')->onDelete('cascade');
            $table->text('supervisor_recommendation')->nullable(); // level 3 only
            $table->string('token', 64)->unique();
            $table->timestamp('submitted_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->unique(['activity_participant_id', 'evaluation_type', 'form_type', 'activity_speaker_id'], 'pe_unique_eval');
            $table->index(['token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_evaluations');
    }
};
