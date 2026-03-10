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
            // Drop old string column
            $table->dropColumn('name');
            // Add foreign key mapping to activity_names
            $table->foreignId('activity_name_id')->nullable()->constrained('activity_names')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['activity_name_id']);
            $table->dropColumn('activity_name_id');
            // Restore string column
            $table->string('name')->nullable();
        });
    }
};
