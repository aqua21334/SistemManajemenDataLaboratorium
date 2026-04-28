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
            $table->unsignedBigInteger('id_user'); // FK ke Customer
            
            // --- KOLOM BARU YANG DITAMBAHKAN ---
            $table->string('no_hp', 20);
            $table->string('file_layanan', 255); // Untuk menyimpan nama file dokumen
            // -----------------------------------

            $table->string('jenis_permintaan', 100);
            $table->enum('status', ['sedang diproses', 'diverifikasi', 'selesai'])->default('sedang diproses');
            
            // Dijadikan nullable (opsional) agar tidak error saat create data dari Customer
            // Karena nama bisa diambil dari relasi user, dan tanggal dari created_at
            $table->string('pemohon', 100)->nullable();
            $table->date('tanggal_permintaan')->nullable();
            
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