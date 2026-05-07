<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityPerParticipantTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            // Wajib
            'nip',
            'nama_pelatihan',
            'jenis_kegiatan',
            'kategori_kegiatan',
            'jenis_materi',
            'metode',
            'bentuk_kegiatan',
            'tanggal_mulai',
            'tanggal_selesai',
            'waktu_mulai',
            'waktu_selesai',
            'tempat',
            'institusi',
            'jpl',
            'no_sertifikat',
            // Opsional
            'tujuan',
            'justifikasi',
            'target_kompetensi',
            'scope',
            'sumber_dana',
            'jumlah_anggaran',
            'batch',
            'kuota_peserta',
            'target_peserta',
        ];
    }

    public function array(): array
    {
        return [
            [
                // Wajib
                '199001012020121001',
                'Diklat Teknis Dasar',
                'Pelatihan Teknis',
                'Keperawatan',
                'Substantif',
                'Klasikal',
                'Tatap Muka',
                '2024-01-01',
                '2024-01-05',
                '08:00',
                '15:00',
                'Ruang Kuliah 2 Diklat',
                'Pusdiklatwas BPKP',
                '10',
                'SER-123456',
                // Opsional
                'Meningkatkan kompetensi tenaga keperawatan',
                'Dalam rangka peningkatan kompetensi',
                'Target kompetensi pasca pelatihan',
                'Internal',
                'DIPA RSCM',
                '50000000',
                'Angkatan 1',
                '30',
                'Perawat',
            ],
        ];
    }
}
