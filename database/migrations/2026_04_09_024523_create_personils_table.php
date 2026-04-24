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
    Schema::create('personils', function (Blueprint $table) {
        $table->id('id_personil'); // Primary Key
        $table->unsignedBigInteger('id_user'); // Foreign Key ke tabel users
        
        $table->string('nama_personil', 100);
        $table->string('jabatan', 50);
        $table->string('nip', 25)->nullable(); // Dibuat nullable jika sewaktu-waktu ada pegawai non-PNS
        $table->text('foto')->nullable();
        $table->string('email', 100);
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
        Schema::dropIfExists('personils');
    }
};
