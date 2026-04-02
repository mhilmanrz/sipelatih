<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityPerParticipantTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nip_peserta',
            'judul_kegiatan',
            'pengusul_unit_kerja',
            'jenis_kegiatan',
            'waktu_mulai',
            'waktu_selesai',
            'jenis_materi'
        ];
    }

    public function array(): array
    {
        return [
            [
                '199001012020121001',
                'Diklat Teknis Dasar', 
                'Pusdiklatwas BPKP', 
                'Pelatihan Teknis', 
                '2024-01-01', 
                '2024-01-05', 
                'Substantif'
            ]
        ];
    }
}
