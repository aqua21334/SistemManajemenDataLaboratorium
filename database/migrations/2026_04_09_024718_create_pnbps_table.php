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
    Schema::create('pnbps', function (Blueprint $table) {
        $table->id('id_pnbp');
        $table->unsignedBigInteger('id_permintaan'); // FK
        
        $table->decimal('jumlah', 12, 2);
        $table->date('tanggal_bayar')->nullable();
        $table->string('invoice', 100)->nullable();
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
