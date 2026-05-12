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

            // Find category by code
            $budgetCategory = null;
            if (isset($row['kode_kategori_pagu']) && trim($row['kode_kategori_pagu']) !== '') {
                $categoryCode = trim($row['kode_kategori_pagu']);
                $budgetCategory = BudgetCategory::where('code', $categoryCode)->first();
            }

            $totalAmount = (float) $row['total_anggaran'];
            $blockedAmount = isset($row['dana_blokir']) && is_numeric($row['dana_blokir']) ? (float) $row['dana_blokir'] : 0;

            // Update or create budget
            Budget::updateOrCreate([
                'year' => $year,
                'rkkal_code' => $rkkal,
            ], [
                'budget_category_id' => $budgetCategory ? $budgetCategory->id : null,
                'submark' => isset($row['submark']) ? trim($row['submark']) : null,
                'total_amount' => $totalAmount,
                'blocked_amount' => $blockedAmount,
            ]);
        }
    }
}
