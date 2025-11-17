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
        Schema::create('m_bidang_keahlian', function (Blueprint $table) {
            $table->id('bidang_keahlian_id');
            $table->string('bidang_keahlian_kode')->unique();
            $table->string('bidang_keahlian_nama');
            $table->unsignedBigInteger('kategori_bidang_keahlian_id');
            $table->timestamps();
            
            $table->foreign('kategori_bidang_keahlian_id')->references('kategori_bidang_keahlian_id')->on('m_kategori_bidang_keahlian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_bidang_keahlian');
    }
};
