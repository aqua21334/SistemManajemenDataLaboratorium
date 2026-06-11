<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        Schema::table('peralatans', function (Blueprint $table) {
            $table->date('tanggal_masuk')->nullable()->after('nama_peralatan');
        });

        DB::statement('UPDATE peralatans SET tanggal_masuk = DATE(created_at) WHERE tanggal_masuk IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('peralatans')) {
            return;
        }

        Schema::table('peralatans', function (Blueprint $table) {
            $table->dropColumn('tanggal_masuk');
        });
    }
};