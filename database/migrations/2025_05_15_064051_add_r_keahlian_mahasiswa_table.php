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
        Schema::create('r_keahlian_mahasiswa', function (Blueprint $table) {
            $table->id('keahlian_mahasiswa_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('bidang_keahlian_id');
            $table->string('file_sertifikat');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');
            $table->foreign('bidang_keahlian_id')->references('bidang_keahlian_id')->on('m_bidang_keahlian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_keahlian_mahasiswa');
    }
};
