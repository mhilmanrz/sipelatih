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
        $tables = [
            'activity_categories',
            'activity_formats',
            'activity_methods',
            'activity_scopes',
            'activity_types',
            'batches',
            'fund_sources',
            'material_types',
            'profession_categories',
            'target_participants',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                // If the table already has a 'name' column, place 'code' after 'id', else after 'id'
                if (Schema::hasColumn($table, 'name')) {
                    $tableBlueprint->string('code')->nullable()->unique()->after('id');
                } else {
                    $tableBlueprint->string('code')->nullable()->unique()->after('id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'activity_categories',
            'activity_formats',
            'activity_methods',
            'activity_scopes',
            'activity_types',
            'batches',
            'fund_sources',
            'material_types',
            'profession_categories',
            'target_participants',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) {
                $tableBlueprint->dropColumn('code');
            });
        }
    }
};
