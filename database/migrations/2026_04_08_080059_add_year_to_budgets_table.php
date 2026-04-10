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
        Schema::table('budgets', function (Blueprint $table) {
            $table->integer('year')->after('budget_category_id')->nullable();

            // Add unique constraint for year and rkkal_code
            $table->unique(['year', 'rkkal_code'], 'budgets_year_rkkal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropUnique('budgets_year_rkkal_unique');
            $table->dropColumn('year');
        });
    }
};
