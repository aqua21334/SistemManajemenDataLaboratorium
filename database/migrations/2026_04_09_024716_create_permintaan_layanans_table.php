<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('permintaan_layanans', function (Blueprint $table) {
        $table->id('id_permintaan');
        $table->unsignedBigInteger('id_user'); // FK ke Customer/Pemohon
        
        $table->string('pemohon', 100);
        $table->string('jenis_permintaan', 100);
        // Enum sangat cocok untuk status yang opsinya sudah pasti
        $table->enum('status', ['sedang diproses', 'diverifikasi', 'selesai'])->default('sedang diproses');
        $table->date('tanggal_permintaan');
        $table->timestamps();

        $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_layanans');
    }
};
