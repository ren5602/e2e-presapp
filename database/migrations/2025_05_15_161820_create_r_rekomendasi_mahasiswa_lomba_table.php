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
        Schema::create('r_rekomendasi_mahasiswa_lomba', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lomba_id');
            $table->integer('rank');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');
            $table->foreign('lomba_id')->references('lomba_id')->on('m_lomba');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_rekomendasi_mahasiswa_lomba');
    }
};
