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
        Schema::create('m_admin', function (Blueprint $table) {
            $table->id('admin_id');
            $table->unsignedBigInteger('user_id')->unique();
            // $table->string('username')->unique();
            // $table->string('password');
            $table->string('nama');
            $table->string('email');
            $table->string('no_tlp');
            $table->string('foto_profile')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_admin');
    }
};
