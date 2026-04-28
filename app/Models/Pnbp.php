<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pnbp extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pnbp';

    protected $fillable = [
        'id_permintaan', 'total_biaya', 'jumlah_bayar', 'sisa_tagihan', 
        'status_pembayaran', 'tanggal_bayar', 'bukti_bayar'
    ];

    // Data PNBP ini milik 1 Permintaan Layanan
    public function permintaanLayanan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'id_permintaan', 'id_permintaan');
    }
}