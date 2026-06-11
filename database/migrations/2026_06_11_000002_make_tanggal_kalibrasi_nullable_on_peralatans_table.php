<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('peralatans')) {
            return;
        }

        DB::statement('ALTER TABLE peralatans MODIFY tanggal_kalibrasi DATE NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('peralatans')) {
            return;
        }

        DB::statement('UPDATE peralatans SET tanggal_kalibrasi = CURDATE() WHERE tanggal_kalibrasi IS NULL');
        DB::statement('ALTER TABLE peralatans MODIFY tanggal_kalibrasi DATE NOT NULL');
    }
};