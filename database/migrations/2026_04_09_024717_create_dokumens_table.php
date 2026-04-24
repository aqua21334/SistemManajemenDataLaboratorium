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
    Schema::create('dokumens', function (Blueprint $table) {
        $table->id('id_dokumen');
        $table->unsignedBigInteger('id_permintaan'); // FK
        
        $table->string('nama_file', 100);
        $table->string('jenis_permintaan', 100);
        $table->string('file', 100); // Menyimpan path/lokasi file PDF
        $table->timestamps();

        $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan_layanans')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
