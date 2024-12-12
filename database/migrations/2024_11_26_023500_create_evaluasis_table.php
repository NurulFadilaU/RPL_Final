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
        Schema::create('evaluasis', function (Blueprint $table) {
            $table->id('id_evaluasi'); // Primary key untuk tabel evaluasis
            $table->text('evaluasi'); // Kolom untuk menyimpan evaluasi

            // Kolom id_kegiatan sebagai foreign key
            $table->unsignedBigInteger('id_kegiatan');

            // Definisi foreign key
            $table->foreign('id_kegiatan')->references('id_kegiatan')->on('kegiatans')->onDelete('cascade');

            // Menambahkan kolom timestamps (created_at dan updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasis');
    }
};
