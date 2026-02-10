<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable();
            $table->string('nip')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('nik')->nullable();
            $table->string('status')->nullable();
            $table->string('department')->nullable();  //unit kerja
            $table->string('no_hp')->nullable();
            $table->string('profession')->nullable();
            $table->string('office')->nullable();
            $table->string('grade')->nullable();
            $table->string('position')->nullable();
            $table->string('jabfung')->nullable();
            $table->string('npwp')->nullable();
            $table->string('norek')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
