<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BudgetCategory;

class BudgetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budgetCategories = [
            ['code' => 'BC001', 'name' => 'Honorarium'],
            ['code' => 'BC002', 'name' => 'Transportasi'],
            ['code' => 'BC003', 'name' => 'Akomodasi'],
            ['code' => 'BC004', 'name' => 'Konsumsi'],
            ['code' => 'BC005', 'name' => 'Bahan dan Alat'],
        ];

        foreach ($budgetCategories as $budgetCategory) {
            BudgetCategory::create($budgetCategory);
        }
    }
}
