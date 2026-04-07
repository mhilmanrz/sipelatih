<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            [
                '198501012010121001',   // NIP
                'Budi Santoso',         // NAMA
                'budi@rscm.co.id',      // EMAIL (kosongkan jika bukan unit TI)
                'rahasia123',           // PASSWORD (opsional, default: password123)
                '08123456789',          // NO_HP
                'Teknologi Informasi',  // UNIT_KERJA (nama unit kerja)
                'Analis Sistem',        // JABATAN (nama jabatan)
                'PNS',                  // JENIS_KEPEGAWAIAN
                'Programmer',           // PROFESI
            ],
            [
                '199002022015032002',
                'Siti Aminah',
                '',                     // kosong → null (bukan TI)
                '',
                '',
                'Keuangan',
                'Bendahara',
                'PPPK',
                'Akuntan',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NIP',
            'NAMA',
            'EMAIL',
            'PASSWORD',
            'NO_HP',
            'UNIT_KERJA',
            'JABATAN',
            'JENIS_KEPEGAWAIAN',
            'PROFESI',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF14B8A6']],
            ],
        ];
    }
}
