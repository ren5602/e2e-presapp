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
        Schema::create('m_mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->integer('tahun_angkatan');
            $table->string('nim')->unique();
            // $table->string('password');
            $table->string('nama');
            $table->unsignedBigInteger('kelas_id');
            //$table->boolean('status_kuliah')->default(true); // status mahasiswa aktif atau tidak
            $table->string('no_tlp');
            $table->string('email');
            $table->string('alamat')->nullable();
            $table->float('ipk')->default(0);
            $table->string('foto_profile')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('kelas_id')->references('kelas_id')->on('m_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_mahasiswa');
    }
};
