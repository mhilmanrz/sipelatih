<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BudgetTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'tahun',
            'no rkakl',
            'kode kategori pagu',
            'submark',
            'total anggaran',
            'dana blokir',
        ];
    }

    public function array(): array
    {
        return [
            [
                date('Y'),
                'RKAKL-DUMMY-123',
                'DIPA',
                'Submark Dummy A',
                '150000000',
                '0',
            ],
            [
                date('Y'),
                'RKAKL-DUMMY-456',
                'PNBP',
                'Submark Dummy B',
                '200000000',
                '50000000',
            ],
        ];
    }
}
