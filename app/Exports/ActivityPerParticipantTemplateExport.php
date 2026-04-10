<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityPerParticipantTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nip',
            'nama pelatihan',
            'tanggal mulai',
            'tanggal selesai',
            'no sertifikat',
            'jenis kegiatan',
            'jenis materi',
            'metode',
            'bentuk kegiatan',
            'institusi',
            'jpl',
        ];
    }

    public function array(): array
    {
        return [
            [
                '199001012020121001',
                'Diklat Teknis Dasar',
                '2024-01-01',
                '2024-01-05',
                'SER-123456',
                'Pelatihan Teknis',
                'Substantif',
                'Klasikal',
                'Tatap Muka',
                'Pusdiklatwas BPKP',
                '10',
            ],
        ];
    }
}
