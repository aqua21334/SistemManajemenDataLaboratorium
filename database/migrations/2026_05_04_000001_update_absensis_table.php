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
        Schema::table('absensis', function (Blueprint $table) {
            $table->bigInteger('id_user')->after('id_absensi')->nullable();
            $table->time('jam_masuk')->after('tanggal')->nullable();
            $table->time('jam_pulang')->after('jam_masuk')->nullable();
            
            // Drop columns yang tidak perlu lagi
            $table->dropColumn(['nama', 'jabatan', 'foto', 'lokasi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('nama', 100)->after('id_absensi');
            $table->string('jabatan', 50)->after('nama');
            $table->text('foto')->nullable()->after('jam_pulang');
            $table->string('lokasi', 100)->after('foto');
            
            $table->dropColumn(['id_user', 'jam_masuk', 'jam_pulang']);
        });
    }
};
