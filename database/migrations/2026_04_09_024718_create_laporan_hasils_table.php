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
    Schema::create('laporan_hasils', function (Blueprint $table) {
        $table->id('id_laporan');
        $table->unsignedBigInteger('id_permintaan'); // FK
        
        $table->string('nama_laporan', 100);
        $table->string('file_hasil', 100); // Menyimpan path PDF
        $table->date('tanggal');
        $table->timestamps();

        $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan_layanans')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_hasils');
    }
};
