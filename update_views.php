<?php

$directories = [
    'activity_category',
    'activity_format',
    'activity_method',
    'activity_scope',
    'activity_type',
    'batch',
    'fund_source',
    'material_type',
    'profession-category',
];

$baseDir = __DIR__ . '/resources/views/';

foreach ($directories as $dir) {
    $createFile = $baseDir . $dir . '/create.blade.php';
    if (file_exists($createFile)) {
        $content = file_get_contents($createFile);
        $codeInput = '
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-semibold mb-2">Kode</label>
                    <input type="text" name="code" id="code" value="{{ old(\'code\') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error(\'code\') border-red-500 @enderror"
                        placeholder="Kode (opsional)">
                    @error(\'code\')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
';
        // Insert before the name input or after @csrf
        if (!str_contains($content, 'name="code"')) {
            $content = preg_replace('/(@csrf)/', "$1\n$codeInput", $content);
            file_put_contents($createFile, $content);
            echo "Updated $createFile\n";
        }
    }

    $editFile = $baseDir . $dir . '/edit.blade.php';
    if (file_exists($editFile)) {
        $content = file_get_contents($editFile);
        
        // Use a heuristic to find the variable name, e.g. $activityCategory
        // Typically value="{{ old('name', $activityCategory->name) }}"
        preg_match('/value="\{\{\s*old\(\'name\',\s*\$([a-zA-Z0-9_]+)->name\)\s*\}\}"/', $content, $matches);
        $varName = $matches[1] ?? 'item';

        $codeInput = '
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-semibold mb-2">Kode</label>
                    <input type="text" name="code" id="code" value="{{ old(\'code\', $'.$varName.'->code) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error(\'code\') border-red-500 @enderror"
                        placeholder="Kode (opsional)">
                    @error(\'code\')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
';
        if (!str_contains($content, 'name="code"')) {
            $content = preg_replace('/(@method\(\'PUT\'\))/', "$1\n$codeInput", $content);
            file_put_contents($editFile, $content);
            echo "Updated $editFile\n";
        }
    }

    $indexFile = $baseDir . $dir . '/index.blade.php';
    if (file_exists($indexFile)) {
        $content = file_get_contents($indexFile);
        
        // Add header
        if (!str_contains($content, '>Kode</th>')) {
            $content = preg_replace('/(<th[^>]*>No<\/th>\s*)/', "$1<th class=\"py-3 px-6 text-left\">Kode</th>\n                                    ", $content);
            
            // Add column
            preg_match('/@foreach \(\$([a-zA-Z0-9_]+) as \$([a-zA-Z0-9_]+)\)/', $content, $matches);
            $itemName = $matches[2] ?? 'item';
            
            $content = preg_replace('/(<td[^>]*>\{\{\s*\$([a-zA-Z0-9_]+)->name\s*\}\}<\/td>)/', "<td class=\"py-3 px-6 text-left\">{{ $$itemName->code ?? '-' }}</td>\n                                    $1", $content);
            
            file_put_contents($indexFile, $content);
            echo "Updated $indexFile\n";
        }
    }
}
