<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle; // WAJIB: Untuk memberi nama Tab/Sheet di Excel
use Maatwebsite\Excel\Events\AfterSheet;

class AbsensiBulanSheet implements FromCollection, WithHeadings, WithTitle, WithEvents
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
                $status = $absensi?->status;

                if (!$status && $absensi) {
                    $status = 'hadir';
                }

                return [
                    'nama' => $personil?->nama_personil ?? $absensi?->nama ?? '-',
                    'jabatan' => $personil?->jabatan ?? $absensi?->jabatan ?? '-',
                    'tanggal' => $tanggal,
                    'status' => $this->formatStatus($status),
                ];
            });
        }

        return $absensis->values()->map(function ($absensi) {
            $personil = $absensi->user?->personil;
            $status = $absensi->status ?: 'hadir';

            return [
                'nama' => $personil?->nama_personil ?? $absensi->user?->nama ?? '-',
                'jabatan' => $personil?->jabatan ?? '-',
                'tanggal' => optional($absensi->tanggal)->format('Y-m-d'),
                'status' => $this->formatStatus($status),
            ];
        });
    }

    public function headings(): array
    {
        return ["Nama Petugas", "Jabatan", "Tanggal Absen", "Status"];
    }

    public function title(): string
    {
        // Mengubah angka bulan menjadi nama bulan untuk judul tab (Contoh: "Bulan 1" jadi "Januari")
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $namaBulan[$this->bulan - 1];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $status = trim((string) $sheet->getCell("D{$row}")->getValue());

                    if (strcasecmp($status, 'Hadir') === 0) {
                        $sheet->getStyle("D{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'C6EFCE'],
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '006100'],
                            ],
                        ]);
                    } elseif (strcasecmp($status, 'Izin') === 0) {
                        $sheet->getStyle("D{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D9EAF7'],
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '1F4E79'],
                            ],
                        ]);
                    } elseif (strcasecmp($status, 'Sakit') === 0) {
                        $sheet->getStyle("D{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FCE4D6'],
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '9E480E'],
                            ],
                        ]);
                    } elseif (strcasecmp($status, 'Tidak Hadir') === 0) {
                        $sheet->getStyle("D{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFC7CE'],
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '9C0006'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }

    private function formatStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            default => 'Tidak Hadir',
        };
    }
}