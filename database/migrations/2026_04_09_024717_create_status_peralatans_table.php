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
    Schema::create('status_peralatans', function (Blueprint $table) {
        $table->id('id_status');
        $table->unsignedBigInteger('kode_bmn'); // FK ke Peralatan
        $table->string('petugas', 100);
        $table->timestamps();

        $table->foreign('kode_bmn')->references('kode_bmn')->on('peralatans')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_peralatans');
    }
};
