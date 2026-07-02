<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MaterialTemplateExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Pengantar Kebijakan (CONTOH - HAPUS BARIS INI)', 90],
            ['Praktik Lapangan (CONTOH - HAPUS BARIS INI)', 135],
        ];
    }

    public function headings(): array
    {
        return [
            'NAMA_MATERI',
            'MENIT',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF14B8A6']]],
        ];
    }
}
