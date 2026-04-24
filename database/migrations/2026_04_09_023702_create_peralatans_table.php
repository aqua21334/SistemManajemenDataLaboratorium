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
    Schema::create('peralatans', function (Blueprint $table) {
        // Menggunakan kode_bmn sebagai Primary Key sesuai desain PDM kamu
        $table->id('kode_bmn'); 
        $table->string('nama_peralatan', 100);
        $table->date('tanggal_kalibrasi');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peralatans');
    }
};
