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
        Schema::table('evaluation_criteria', function (Blueprint $table) {
            $table->foreignId('evaluation_category_id')->nullable()->after('evaluation_type')->constrained('evaluation_categories')->onDelete('set null');
            $table->enum('form_type', ['speaker', 'activity'])->nullable()->after('evaluation_category_id'); // untuk level 1 saja
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_criteria', function (Blueprint $table) {
            $table->dropForeign(['evaluation_category_id']);
            $table->dropColumn(['evaluation_category_id', 'form_type']);
        });
    }
};
