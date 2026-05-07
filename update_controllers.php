<?php

$controllers = [
    'ActivityCategoryController' => 'activity_categories',
    'ActivityFormatController' => 'activity_formats',
    'ActivityMethodController' => 'activity_methods',
    'ActivityScopeController' => 'activity_scopes',
    'ActivityTypeController' => 'activity_types',
    'BatchController' => 'batches',
    'FundSourceController' => 'fund_sources',
    'MaterialTypeController' => 'material_types',
    'TargetParticipantController' => 'target_participants',
];

$dir = __DIR__ . '/app/Http/Controllers/Act/';

foreach ($controllers as $controller => $table) {
    $file = $dir . $controller . '.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Add code to store
        $storePattern = "/('name'\s*=>\s*'required\|string\|max:255',)/";
        $storeReplace = "'code' => 'nullable|string|max:255|unique:$table,code',\n            $1";
        
        // Let's replace the first occurrence in store, but the regex matches both.
        // It's better to do a callback.
        $content = preg_replace_callback("/(public function store.*?)('name'\s*=>\s*'required\|string\|max:255',)/s", function($m) use ($table) {
            return $m[1] . "'code' => 'nullable|string|max:255|unique:$table,code',\n            " . $m[2];
        }, $content);

        // For update, the unique rule should ignore the current id.
        $content = preg_replace_callback("/(public function update\(Request \\\$request, \\\$id\).*?)('name'\s*=>\s*'required\|string\|max:255',)/s", function($m) use ($table) {
            return $m[1] . "'code' => 'nullable|string|max:255|unique:$table,code,' . \$id,\n            " . $m[2];
        }, $content);
        
        file_put_contents($file, $content);
        echo "Updated $controller\n";
    }
}

// And ProfessionCategoryController
$file = __DIR__ . '/app/Http/Controllers/ProfessionCategoryController.php';
if (file_exists($file)) {
    $table = 'profession_categories';
    $content = file_get_contents($file);
    
    $content = preg_replace_callback("/(public function store.*?)('name'\s*=>\s*'required\|string\|max:255',)/s", function($m) use ($table) {
        return $m[1] . "'code' => 'nullable|string|max:255|unique:$table,code',\n            " . $m[2];
    }, $content);

    $content = preg_replace_callback("/(public function update\(Request \\\$request, \\\$id\).*?)('name'\s*=>\s*'required\|string\|max:255',)/s", function($m) use ($table) {
        return $m[1] . "'code' => 'nullable|string|max:255|unique:$table,code,' . \$id,\n            " . $m[2];
    }, $content);
    
    file_put_contents($file, $content);
    echo "Updated ProfessionCategoryController\n";
}
