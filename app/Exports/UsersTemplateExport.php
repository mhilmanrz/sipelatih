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
            'npwp',
            'nama_bank',
            'nomor_rekening',
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
                'PRW',
                'FUNG',
                'PNS',
                'III/a',
                '12.345.678.9-012.000',
                'Bank Mandiri',
                '1234567890',
            ],
        ];
    }
}
