<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ActivityScoreTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Peserta',
            'Pre Test',
            'Post Test',
            'Praktik',
            'Lulus (Ya/Tidak)',
        ];
    }

    public function array(): array
    {
        return [
            [
                '198501012010121001',
                'Peserta Contoh 1',
                '75',
                '80',
                '85',
                'Ya',
            ],
            [
                '199002022015031002',
                'Peserta Contoh 2',
                '60',
                '65',
                '70',
                'Tidak',
            ],
        ];
    }

    public function title(): string
    {
        return 'Template Import Nilai';
    }
}
