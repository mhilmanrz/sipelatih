<?php

namespace App\Exports;

use App\Models\Act\ActivityScoreComponent;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ActivityScoreTemplateExport implements FromArray, WithHeadings, WithTitle
{
    protected $activityId;

    public function __construct($activityId)
    {
        $this->activityId = $activityId;
    }

    public function headings(): array
    {
        $components = ActivityScoreComponent::where('activity_id', $this->activityId)
            ->orderBy('order')
            ->get();

        $headings = [
            'NIP',
            'Nama Peserta',
        ];

        foreach ($components as $component) {
            $headings[] = $component->name;
        }

        return $headings;
    }

    public function array(): array
    {
        $components = ActivityScoreComponent::where('activity_id', $this->activityId)
            ->orderBy('order')
            ->get();

        $row1 = [
            '198501012010121001',
            'Peserta Contoh 1',
        ];

        $row2 = [
            '199002022015031002',
            'Peserta Contoh 2',
        ];

        foreach ($components as $component) {
            // Provide some dummy scores
            $row1[] = '85';
            $row2[] = '60';
        }

        return [
            $row1,
            $row2,
        ];
    }

    public function title(): string
    {
        return 'Template Import Nilai';
    }
}
