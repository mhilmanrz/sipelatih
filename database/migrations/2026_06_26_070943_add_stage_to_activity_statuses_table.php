<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_statuses', function (Blueprint $table) {
            $table->enum('stage', ['pengusul', 'perencanaan', 'penyelenggara', 'evaluasi'])
                ->default('pengusul')
                ->after('activity_id');
        });

        // Switch status to a plain string: valid values are enforced in the app layer
        // (Activity::currentStatus()/isEditableByRole(), ActivityStatusController), and a
        // driver-portable string avoids MySQL-only `ALTER ... MODIFY COLUMN ENUM` raw SQL
        // (which SQLite, used in tests, cannot run at all).
        Schema::table('activity_statuses', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
        });

        // Migrate existing data
        DB::table('activity_statuses')->where('status', 'submitted')->update([
            'stage' => 'perencanaan',
            'status' => 'pending',
        ]);

        DB::table('activity_statuses')->where('status', 'accepted')->update([
            'stage' => 'penyelenggara',
            'status' => 'pending',
        ]);

        DB::table('activity_statuses')->where('status', 'draft')->update(['stage' => 'pengusul']);
        DB::table('activity_statuses')->where('status', 'revision')->update(['stage' => 'pengusul']);
    }

    public function down(): void
    {
        DB::table('activity_statuses')
            ->where('stage', 'perencanaan')->where('status', 'pending')
            ->update(['status' => 'submitted']);

        DB::table('activity_statuses')
            ->where('stage', 'penyelenggara')->where('status', 'pending')
            ->update(['stage' => 'pengusul', 'status' => 'accepted']);

        Schema::table('activity_statuses', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'revision', 'accepted'])->default('draft')->change();
        });

        Schema::table('activity_statuses', function (Blueprint $table) {
            $table->dropColumn('stage');
        });
    }
};
