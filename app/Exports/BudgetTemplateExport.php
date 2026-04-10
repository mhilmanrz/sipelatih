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
            'kategori pagu',
            'submark',
            'total anggaran',
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
            ],
            [
                date('Y'),
                'RKAKL-DUMMY-456',
                'PNBP',
                'Submark Dummy B',
                '200000000',
            ],
        ];
    }
}
