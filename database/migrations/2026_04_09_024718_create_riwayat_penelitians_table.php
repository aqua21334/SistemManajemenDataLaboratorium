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
    Schema::create('riwayat_penelitians', function (Blueprint $table) {
        $table->id('id_riwayat');
        $table->unsignedBigInteger('id_permintaan'); // FK ke Permintaan
        $table->unsignedBigInteger('id_user'); // FK ke Petugas yang mengubah status
        
        $table->string('nama_laporan', 100)->nullable();
        $table->date('tanggal_selesai')->nullable();
        $table->string('status', 20); // Mencatat perubahan status saat itu
        $table->timestamps();

        $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan_layanans')->onDelete('cascade');
        $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penelitians');
    }
};
