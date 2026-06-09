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
            $table->dropColumn('is_fillable');
            $table->string('type')->default('rating')->change();
            $table->string('bottom_label')->nullable()->after('type');
            $table->string('top_label')->nullable()->after('bottom_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_criteria', function (Blueprint $table) {
            $table->boolean('is_fillable')->default(false);
            $table->enum('type', ['string', 'number'])->default('string')->change();
            $table->dropColumn(['bottom_label', 'top_label']);
        });
    }
};
