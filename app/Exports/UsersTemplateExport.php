<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nama',
            'email',
            'telepon',
            'nip',
            'unit_kerja',
            'profesi',
            'jabatan',
            'jenis_pegawai',
            'pangkat',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                'budi@example.com',
                '081234567890',
                '198501012010011001',
                'Pusdiklatwas BPKP',
                'Perawat',
                'Fungsional',
                'PNS',
                'III/a',
            ],
        ];
    }
}
