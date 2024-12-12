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
        Schema::create('rekaps', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->integer('rekap_selesai');
            $table->integer('rekap_total');
            $table->integer('total_target');
            $table->integer('total_diterima');
            $table->date('tanggal_rekap');
           
            $table->unsignedBigInteger('id_tim');

            $table->foreign('id_tim')->references('id_rekap')->on('rekaps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekaps');
    }
};
