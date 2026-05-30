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
        $table->id(); // Primary key auto_increment
        $table->string('kode_bmn', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique(); // Kode BMN manual input, tidak auto_increment
        $table->string('nama_peralatan', 100);
        $table->date('tanggal_kalibrasi');
        $table->string('status', 50)->nullable();
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
