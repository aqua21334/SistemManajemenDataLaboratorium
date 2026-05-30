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
        if (!Schema::hasTable('peralatans') || !Schema::hasTable('status_peralatans')) {
            return;
        }

        DB::statement('ALTER TABLE status_peralatans DROP FOREIGN KEY status_peralatans_kode_bmn_foreign');

        DB::statement("ALTER TABLE peralatans MODIFY kode_bmn VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
        DB::statement("ALTER TABLE status_peralatans MODIFY kode_bmn VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");

        DB::statement('ALTER TABLE status_peralatans ADD CONSTRAINT status_peralatans_kode_bmn_foreign FOREIGN KEY (kode_bmn) REFERENCES peralatans (kode_bmn) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('peralatans') || !Schema::hasTable('status_peralatans')) {
            return;
        }

        DB::statement('ALTER TABLE status_peralatans DROP FOREIGN KEY status_peralatans_kode_bmn_foreign');

        DB::statement('ALTER TABLE peralatans MODIFY kode_bmn VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE status_peralatans MODIFY kode_bmn VARCHAR(50) NOT NULL');

        DB::statement('ALTER TABLE status_peralatans ADD CONSTRAINT status_peralatans_kode_bmn_foreign FOREIGN KEY (kode_bmn) REFERENCES peralatans (kode_bmn) ON DELETE CASCADE');
    }
};