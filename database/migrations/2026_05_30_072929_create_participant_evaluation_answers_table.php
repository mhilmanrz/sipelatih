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
        Schema::create('participant_evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_evaluation_id')->constrained('participant_evaluations')->onDelete('cascade');
            $table->foreignId('evaluation_criteria_id')->constrained('evaluation_criteria')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-4
            $table->timestamps();

            $table->unique(['participant_evaluation_id', 'evaluation_criteria_id'], 'pea_unique_eval_criteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_evaluation_answers');
    }
};
