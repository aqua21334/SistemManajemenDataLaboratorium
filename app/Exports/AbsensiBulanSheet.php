<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle; // WAJIB: Untuk memberi nama Tab/Sheet di Excel

class AbsensiBulanSheet implements FromCollection, WithHeadings, WithTitle
{
    private $tahun;
    private $bulan;

    public function __construct(int $tahun, int $bulan)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function collection()
    {
        // Ambil data hanya untuk bulan dan tahun yang diminta
        return Absensi::select('nama', 'jabatan', 'tanggal', 'lokasi')
            ->whereYear('tanggal', $this->tahun)
            ->whereMonth('tanggal', $this->bulan)
            ->get();
    }

    public function headings(): array
    {
        return ["Nama Petugas", "Jabatan", "Tanggal Absen", "Lokasi"];
    }

    public function title(): string
    {
        // Mengubah angka bulan menjadi nama bulan untuk judul tab (Contoh: "Bulan 1" jadi "Januari")
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $namaBulan[$this->bulan - 1];
    }
}