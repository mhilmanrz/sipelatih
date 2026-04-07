<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->onDelete('set null')->after('fund_source_id');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeignIfExists(['budget_id']);
            $table->dropColumn('budget_id');
        });
    }
};
