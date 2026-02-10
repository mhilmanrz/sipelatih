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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis');
            $table->string('cakupan');
            $table->string('materi');
            $table->string('metode');
            $table->integer('angkatan');
            $table->string('bentuk');
            $table->string('institusi');
            $table->string('peserta');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->integer('anggaran');
            $table->string('unit_pengusul');
            $table->string('pic');
            $table->integer('wa_pic');
            $table->string('no_registrasi')->unique();
            $table->integer('jpl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
