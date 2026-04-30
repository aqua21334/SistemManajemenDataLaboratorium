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
    Schema::create('daftar_sops', function (Blueprint $table) {
        $table->string('id_sop', 50)->primary(); // Primary Key (String, Tidak Auto Increment)
        $table->unsignedBigInteger('id_user'); // Mengacu pada siapa yang mengunggah
        
        $table->string('jenis_sop', 50);
        $table->string('judul_sop', 100);
        $table->string('file_sop')->nullable(); // Menyimpan nama file
        $table->timestamps();

        // Relasi ke tabel users
        $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_sops');
    }
};
