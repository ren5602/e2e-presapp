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
        Schema::create('r_mahasiswa_organisasi', function (Blueprint $table) {
            $table->id('mahasiswa_organisasi_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('organisasi_id');
            $table->timestamps();
            
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('organisasi_id')->references('organisasi_id')->on('m_organisasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_mahasiswa_organisasi');
    }
};
