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
        Schema::create('m_lomba', function (Blueprint $table) {
            $table->id('lomba_id');
            $table->string('lomba_kode')->unique();
            $table->string('lomba_nama');
            $table->text('lomba_deskripsi');
            $table->string('link_website');
            $table->unsignedBigInteger('tingkat_lomba_id');
            $table->unsignedBigInteger('bidang_keahlian_id');
            $table->unsignedBigInteger('penyelenggara_id');
            $table->string('jumlah_anggota')->default(1);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('foto_pamflet');
            $table->unsignedBigInteger('user_id');
            $table->boolean('status_verifikasi')->nullable()->default(null);

            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('penyelenggara_id')->references('penyelenggara_id')->on('m_penyelenggara');
            $table->foreign('tingkat_lomba_id')->references('tingkat_lomba_id')->on('m_tingkat_lomba');
            $table->foreign('bidang_keahlian_id')->references('bidang_keahlian_id')->on('m_bidang_keahlian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_lomba');
    }
};
