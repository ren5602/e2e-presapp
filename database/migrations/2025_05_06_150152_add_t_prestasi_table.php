<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_prestasi', function (Blueprint $table) {
            $table->id('prestasi_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('prestasi_nama');
            $table->unsignedBigInteger('lomba_id');
            $table->enum('juara', ['1', '2', '3', '4']); // hanya bisa 1-4
            $table->string('nama_juara')->nullable(); // keterangan tambahan juara
            // $table->date('tanggal_mulai');
            $table->date('tanggal_perolehan');
            $table->string('file_sertifikat');
            $table->string('file_bukti_foto');
            $table->string('file_surat_tugas');
            $table->string('file_surat_undangan');
            $table->string('file_proposal')->nullable();
            $table->integer('poin')->nullable()->default(null);
            $table->boolean('status_verifikasi')->nullable()->default(null);
            $table->string('message')->nullable();   
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');
            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
            $table->foreign('lomba_id')->references('lomba_id')->on('m_lomba');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_prestasi');
    }
};
