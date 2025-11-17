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
        Schema::table('m_user', function (Blueprint $table) {
            // Pastikan semua nilai NULL sudah diubah sebelumnya
            $table->unsignedBigInteger('level_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('m_user', function (Blueprint $table) {
            $table->unsignedBigInteger('level_id')->nullable()->change();
        });
    }
};
