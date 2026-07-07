<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_external')->default(false)->after('account_number');
            $table->string('nik')->nullable()->after('is_external');
            $table->string('institution')->nullable()->after('nik');
            $table->string('external_position')->nullable()->after('institution');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_external', 'nik', 'institution', 'external_position']);
        });
    }
};
