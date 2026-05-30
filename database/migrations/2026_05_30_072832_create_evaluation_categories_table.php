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
        Schema::create('evaluation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('evaluation_type'); // 1 atau 3
            $table->enum('form_type', ['speaker', 'activity'])->nullable(); // null untuk level 3
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index(['evaluation_type', 'form_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_categories');
    }
};
