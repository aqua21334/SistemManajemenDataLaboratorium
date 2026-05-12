<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle; // WAJIB: Untuk memberi nama Tab/Sheet di Excel

class AbsensiBulanSheet implements FromCollection, WithHeadings, WithTitle
{
    private $tahun;
    private $bulan;
    private $idUser;

    public function __construct(int $tahun, int $bulan, ?int $idUser = null)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->idUser = $idUser;
    }

    public function collection()
    {
        // Ambil data hanya untuk bulan dan tahun yang diminta
        $query = Absensi::with(['user.personil'])
            ->whereYear('tanggal', $this->tahun)
            ->whereMonth('tanggal', $this->bulan);

        $today = Carbon::today();
        $targetMonth = Carbon::create($this->tahun, $this->bulan, 1);

        if ($targetMonth->year > $today->year || ($targetMonth->year === $today->year && $targetMonth->month > $today->month)) {
            return collect();
        }

        if ($this->idUser) {
            $query->where('id_user', $this->idUser);
        }

        $absensis = $query->get()->keyBy(function ($absensi) {
            return optional($absensi->tanggal)->format('Y-m-d');
        });

        if ($this->idUser) {
            $user = User::with('personil')->find($this->idUser);
            $personil = $user?->personil;

            $startDate = Carbon::create($this->tahun, $this->bulan, 1)->startOfMonth();
            $endDate = Carbon::create($this->tahun, $this->bulan, 1)->endOfMonth();

            if ($targetMonth->year === $today->year && $targetMonth->month === $today->month) {
                $endDate = $today->copy()->endOfDay();
            }

            $period = CarbonPeriod::create($startDate, '1 day', $endDate);

            return collect($period)->map(function (Carbon $date) use ($absensis, $personil) {
                $tanggal = $date->format('Y-m-d');
                $absensi = $absensis->get($tanggal);

                return [
                    'nama' => $personil?->nama_personil ?? $absensi?->nama ?? '-',
                    'jabatan' => $personil?->jabatan ?? $absensi?->jabatan ?? '-',
                    'tanggal' => $tanggal,
                    'lokasi' => $absensi?->lokasi ?? '-',
                    'status' => $absensi ? 'Hadir' : 'Tidak Hadir',
                ];
            });
        }

        return $absensis->values()->map(function ($absensi) {
            $personil = $absensi->user?->personil;

            return [
                'nama' => $personil?->nama_personil ?? $absensi->user?->nama ?? '-',
                'jabatan' => $personil?->jabatan ?? '-',
                'tanggal' => optional($absensi->tanggal)->format('Y-m-d'),
                'lokasi' => $absensi->lokasi,
                'status' => 'Hadir',
            ];
        });
    }

    public function headings(): array
    {
        return ["Nama Petugas", "Jabatan", "Tanggal Absen", "Lokasi", "Status"];
    }

    public function title(): string
    {
        // Mengubah angka bulan menjadi nama bulan untuk judul tab (Contoh: "Bulan 1" jadi "Januari")
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $namaBulan[$this->bulan - 1];
    }
}