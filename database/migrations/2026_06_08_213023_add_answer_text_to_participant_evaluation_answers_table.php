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
        Schema::table('participant_evaluation_answers', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->change();
            $table->text('answer_text')->nullable()->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participant_evaluation_answers', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable(false)->change();
            $table->dropColumn('answer_text');
        });
    }
};
