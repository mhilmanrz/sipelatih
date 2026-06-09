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
        Schema::table('participant_evaluation_files', function (Blueprint $table) {
            $table->foreignId('evaluation_criteria_id')->nullable()->after('participant_evaluation_id')->constrained('evaluation_criteria')->nullOnDelete();
            $table->string('file_name')->nullable()->after('file_path');
            $table->string('mime_type')->nullable()->after('file_name');
            $table->bigInteger('file_size')->nullable()->after('mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participant_evaluation_files', function (Blueprint $table) {
            $table->dropForeign(['evaluation_criteria_id']);
            $table->dropColumn(['evaluation_criteria_id', 'file_name', 'mime_type', 'file_size']);
        });
    }
};
