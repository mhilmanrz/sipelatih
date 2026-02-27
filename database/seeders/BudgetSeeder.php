<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\BudgetCategory;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = BudgetCategory::all();

        $budgets = [
            ['budget_category_id' => $categories->where('code', 'BC001')->first()->id ?? 1, 'rkkal_code' => 'RK-2026-001', 'submark' => 'Pelatihan Dasar', 'total_amount' => 50000000, 'remaining_amount' => 50000000],
            ['budget_category_id' => $categories->where('code', 'BC002')->first()->id ?? 2, 'rkkal_code' => 'RK-2026-002', 'submark' => 'Transportasi Peserta', 'total_amount' => 30000000, 'remaining_amount' => 30000000],
            ['budget_category_id' => $categories->where('code', 'BC003')->first()->id ?? 3, 'rkkal_code' => 'RK-2026-003', 'submark' => 'Penginapan Narasumber', 'total_amount' => 20000000, 'remaining_amount' => 20000000],
            ['budget_category_id' => $categories->where('code', 'BC004')->first()->id ?? 4, 'rkkal_code' => 'RK-2026-004', 'submark' => 'Makan Siang Pelatihan', 'total_amount' => 15000000, 'remaining_amount' => 15000000],
            ['budget_category_id' => $categories->where('code', 'BC005')->first()->id ?? 5, 'rkkal_code' => 'RK-2026-005', 'submark' => 'Alat Tulis Kantor', 'total_amount' => 10000000, 'remaining_amount' => 10000000],
        ];

        foreach ($budgets as $budget) {
            Budget::create($budget);
        }
    }
}
