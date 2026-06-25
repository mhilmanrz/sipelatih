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
        Schema::create('participant_import_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->string('filename');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('total_rows')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->json('errors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_import_logs');
    }
};
