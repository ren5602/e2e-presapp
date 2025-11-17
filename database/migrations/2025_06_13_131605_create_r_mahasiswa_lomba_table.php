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
        Schema::create('r_mahasiswa_lomba', function (Blueprint $table) {
            $table->id('mahasiswa_lomba_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lomba_id');
            $table->boolean('status_verifikasi')->nullable()->default(null);
            $table->enum('pengaju', ['ADM', 'MHS', 'DOS', 'SPK']);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');
            $table->foreign('lomba_id')->references('lomba_id')->on('m_lomba');
            $table->foreign('user_id')->references('user_id')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_mahasiswa_lomba');
    }
};
