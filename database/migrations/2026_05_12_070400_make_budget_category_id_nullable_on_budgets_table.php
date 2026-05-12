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
            $table->dropForeign(['budget_category_id']);
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('budget_category_id')->nullable()->change();
            $table->foreign('budget_category_id')->references('id')->on('budget_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['budget_category_id']);
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('budget_category_id')->nullable(false)->change();
            $table->foreign('budget_category_id')->references('id')->on('budget_categories')->onDelete('cascade');
        });
    }
};
