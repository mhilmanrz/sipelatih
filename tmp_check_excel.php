<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Budget count: " . App\Models\Budget::count() . "\n";
echo "BudgetCategory count: " . App\Models\BudgetCategory::count() . "\n";
echo "\nDaftar Kategori:\n";
foreach(App\Models\BudgetCategory::all() as $cat) {
    echo "  [{$cat->id}] code={$cat->code} name={$cat->name}\n";
}
echo "\nbuda amount adalah kolom di tabel activities (budget_amount), bukan di tabel budgets.\n";
echo "budgets.total_amount = pagu keseluruhan\n";
echo "activities.budget_amount = anggaran per kegiatan\n";
