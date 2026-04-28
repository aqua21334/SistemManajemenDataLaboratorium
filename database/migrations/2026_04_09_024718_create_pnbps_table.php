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
    Schema::create('pnbps', function (Blueprint $table) {
        $table->id('id_pnbp');
        $table->unsignedBigInteger('id_permintaan');
        
        // --- Kolom Baru untuk Sistem Invoice ---
        $table->decimal('total_biaya', 15, 2)->default(0); // Harga Awal dari Lab
        $table->decimal('jumlah_bayar', 15, 2)->default(0); // Yang dibayar Customer
        $table->decimal('sisa_tagihan', 15, 2)->default(0); // Otomatis (Total - Bayar)
        $table->string('status_pembayaran', 50)->default('Belum Dibayar'); // Lunas / Kurang / Belum
        
        $table->date('tanggal_bayar')->nullable();
        $table->string('bukti_bayar')->nullable(); // Foto struk transfer
        $table->timestamps();

        $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan_layanans')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pnbps');
    }
};
