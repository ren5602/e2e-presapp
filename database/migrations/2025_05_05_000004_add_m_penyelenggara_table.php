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
        Schema::create('m_penyelenggara', function (Blueprint $table) {
            $table->id('penyelenggara_id');
            $table->string('penyelenggara_nama');
            $table->unsignedBigInteger('kota_id')->nullable();
            // $table->unsignedBigInteger('negara_id')->nullable();
            $table->timestamps();

            $table->foreign('kota_id')->references('kota_id')->on('m_kota');
            // $table->foreign('negara_id')->references('negara_id')->on('m_negara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_penyelenggara');
    }
};
