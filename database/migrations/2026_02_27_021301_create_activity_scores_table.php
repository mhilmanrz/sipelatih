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
        Schema::create('activity_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_participant_id')->constrained('activity_participants')->onDelete('cascade');
            $table->integer('pre_test_score');
            $table->integer('post_test_score');
            $table->integer('practice_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_scores');
    }
};
