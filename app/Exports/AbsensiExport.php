<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AbsensiExport implements WithMultipleSheets
{
    protected int $tahun;
    protected ?int $idUser;

    // Menangkap filter tahun dan user dari Controller
    public function __construct(int $tahun, ?int $idUser = null) {
        $this->tahun = $tahun;
        $this->idUser = $idUser;
    }

    public function sheets(): array
    {
        $sheets = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $sheets[] = new AbsensiBulanSheet($this->tahun, $bulan, $this->idUser);
        }

        return $sheets;
    }
}