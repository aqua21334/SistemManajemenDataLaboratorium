<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Kita buat Trigger bernama 'tr_catat_riwayat_status'
        // 2. Trigger ini berjalan AFTER UPDATE (setelah ada data yang diubah) di tabel permintaan_layanans
        
        DB::unprepared('
            CREATE TRIGGER tr_catat_riwayat_status 
            AFTER UPDATE ON permintaan_layanans
            FOR EACH ROW
            BEGIN
                -- Cek apakah status yang lama BERBEDA dengan status yang baru
                IF OLD.status <> NEW.status THEN
                    -- Jika berbeda, otomatis insert ke tabel riwayat_penelitians
                    INSERT INTO riwayat_penelitians (
                        id_permintaan, 
                        id_user, 
                        status, 
                        created_at, 
                        updated_at
                    ) VALUES (
                        NEW.id_permintaan, 
                        NEW.id_user, 
                        NEW.status, 
                        NOW(), 
                        NOW()
                    );
                END IF;
            END
        ');
    }

    public function down()
    {
        // Jika di-rollback, hapus triggernya
        DB::unprepared('DROP TRIGGER IF EXISTS tr_catat_riwayat_status');
    }
};