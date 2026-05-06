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
            if (!Schema::hasColumn('absensis', 'lokasi')) {
                $table->string('lokasi', 255)->nullable()->after('jam_pulang');
            }
            if (!Schema::hasColumn('absensis', 'latitude')) {
                $table->double('latitude')->nullable()->after('lokasi');
            }
            if (!Schema::hasColumn('absensis', 'longitude')) {
                $table->double('longitude')->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            if (Schema::hasColumn('absensis', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('absensis', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('absensis', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
        });
    }
};
