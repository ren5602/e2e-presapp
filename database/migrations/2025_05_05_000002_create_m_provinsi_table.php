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
        Schema::create('m_provinsi', function (Blueprint $table) {
            $table->id('provinsi_id');
            $table->string('provinsi_nama');
            $table->unsignedBigInteger('negara_id');
            $table->timestamps();

            $table->foreign('negara_id')->references('negara_id')->on('m_negara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_provinsi');
    }
};
