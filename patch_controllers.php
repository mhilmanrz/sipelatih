<?php

$files = [
    'app/Http/Controllers/User/PositionController.php',
    'app/Http/Controllers/User/ProfessionController.php',
    'app/Http/Controllers/User/WorkUnitController.php',
    'app/Http/Controllers/User/EmploymentTypeController.php',
    'app/Http/Controllers/ProfessionCategoryController.php',
    'app/Http/Controllers/ActivityNameController.php',
    'app/Http/Controllers/Act/BatchController.php',
    'app/Http/Controllers/Act/BudgetCategoryController.php', // Check both?
    'app/Http/Controllers/BudgetCategoryController.php',
    'app/Http/Controllers/Act/MaterialTypeController.php',
    'app/Http/Controllers/Act/TargetParticipantController.php',
    'app/Http/Controllers/Act/FundSourceController.php',
    'app/Http/Controllers/Act/ActivityTypeController.php',
    'app/Http/Controllers/Act/ActivityScopeController.php',
    'app/Http/Controllers/Act/ActivityMethodController.php',
    'app/Http/Controllers/Act/ActivityFormatController.php',
    'app/Http/Controllers/Act/ActivityCategoryController.php',
];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }

    $content = file_get_contents($file);

    // 1. Change `public function index()` to `public function index(Request $request)`
    if (strpos($content, 'public function index(Request $request)') === false) {
        $content = preg_replace('/public function index\(\s*\)/', 'public function index(Request $request)', $content);
    }

    // 2. Ensure `use Illuminate\Http\Request;` exists
    if (strpos($content, 'use Illuminate\Http\Request;') === false) {
        $content = preg_replace('/(namespace App\\\\.*;)/', "$1\n\nuse Illuminate\Http\Request;", $content);
    }

    // 3. Find `$var = Model::paginate(10);` and replace it
    // Note: ActivityNameController has `$activityNames = ActivityName::paginate(10);`
    if (preg_match('/\$([a-zA-Z0-9_]+)\s*=\s*([a-zA-Z0-9_]+)::paginate\(\s*10\s*\);/', $content, $matches)) {
        $varName = $matches[1];
        $modelName = $matches[2];

        $replacement = '        $query = '.$modelName.'::query();
        if ($request->has(\'q\') && $request->q != \'\') {
            $query->where(\'name\', \'like\', \'%\' . $request->q . \'%\');
        }
        $perPage = $request->input(\'entries\', $request->input(\'per_page\', 10));
        $'.$varName.' = $query->paginate($perPage)->appends($request->all());';

        $content = str_replace($matches[0], ltrim($replacement), $content);
    }

    file_put_contents($file, $content);
    echo "Updated: $file\n";
}

echo "Done\n";
