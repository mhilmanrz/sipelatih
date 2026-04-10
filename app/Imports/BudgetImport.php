<?php

namespace App\Imports;

use App\Models\Budget;
use App\Models\BudgetCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BudgetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (! isset($row['tahun']) || ! isset($row['no_rkakl']) || ! isset($row['total_anggaran'])) {
                continue;
            }

            $year = trim($row['tahun']);
            $rkkal = trim($row['no_rkakl']);

            // Skip invalid data
            if (! is_numeric($year) || empty($rkkal)) {
                continue;
            }

            // Find or create category
            $budgetCategory = null;
            if (isset($row['kategori_pagu']) && trim($row['kategori_pagu']) !== '') {
                $categoryName = trim($row['kategori_pagu']);
                $budgetCategory = BudgetCategory::firstOrCreate(['name' => $categoryName]);
            }

            $totalAmount = (float) $row['total_anggaran'];

            // Update or create budget
            Budget::updateOrCreate([
                'year' => $year,
                'rkkal_code' => $rkkal,
            ], [
                'budget_category_id' => $budgetCategory ? $budgetCategory->id : null,
                'submark' => isset($row['submark']) ? trim($row['submark']) : null,
                'total_amount' => $totalAmount,
            ]);
        }
    }
}
