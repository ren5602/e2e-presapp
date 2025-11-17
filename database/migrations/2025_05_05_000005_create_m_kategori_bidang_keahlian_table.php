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
        Schema::create('m_kategori_bidang_keahlian', function (Blueprint $table) {
            $table->id('kategori_bidang_keahlian_id');
            $table->string('kategori_bidang_keahlian_kode')->unique();
            $table->string('kategori_bidang_keahlian_nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kategori_bidang_keahlian');
    }
};
