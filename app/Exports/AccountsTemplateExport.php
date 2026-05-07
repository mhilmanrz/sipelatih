<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nama',
            'email',
            'password',
            'unit_kerja',
            'role',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Admin Diklat',
                'admin.diklat@rscm.co.id',
                'password123',
                'Tim Kerja Diklat',
                'admin-diklat',
            ],
        ];
    }
}
