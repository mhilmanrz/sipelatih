<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat kolom 'code' menjadi nullable pada tabel relasi user
     * agar import bisa auto-create entri baru tanpa harus mengisi code.
     */
    public function up(): void
    {
        $tables = ['work_units', 'positions', 'professions', 'employment_types'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('code')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        $tables = ['work_units', 'positions', 'professions', 'employment_types'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('code')->nullable(false)->change();
            });
        }
    }
};
