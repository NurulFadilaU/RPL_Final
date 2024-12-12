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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->dateTime('tanggal_kirim', precision: 0);
            $table->enum('jenis_notifikasi', ['kegiatan_baru', 'h-10', 'h-5', 'h-3', 'h-2', 'h-1', 'evaluasi']);
            $table->boolean('status');
            $table->text('pesan');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kegiatan')->nullable();

            $table->foreign('id_user')->references('id_notifikasi')->on('notifikasis');
            $table->foreign('id_kegiatan')->references('id_notifikasi')->on('notifikasis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
