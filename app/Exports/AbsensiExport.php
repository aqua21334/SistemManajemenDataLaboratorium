<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Untuk judul kolom

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $start_date;
    protected $end_date;

    // Menangkap filter tanggal dari Controller
    public function __construct($start_date, $end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        // Mengambil data absensi berdasarkan rentang tanggal
        return Absensi::select('nama', 'jabatan', 'tanggal', 'lokasi')
            ->whereBetween('tanggal', [$this->start_date, $this->end_date])
            ->get();
    }

    public function headings(): array
    {
        return ["Nama Petugas", "Jabatan", "Tanggal Absen", "Lokasi"];
    }
}