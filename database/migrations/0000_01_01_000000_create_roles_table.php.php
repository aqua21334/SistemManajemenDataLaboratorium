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
    Schema::create('roles', function (Blueprint $table) {
        // Menggunakan id_role sebagai Primary Key sesuai desainmu
        $table->id('id_role'); 
        $table->string('nama_role', 10);
        $table->timestamps(); // Otomatis membuat kolom created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
