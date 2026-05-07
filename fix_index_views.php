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
    $indexFile = $baseDir . $dir . '/index.blade.php';
    if (file_exists($indexFile)) {
        $content = file_get_contents($indexFile);
        
        // Remove the buggy line if exists
        $content = preg_replace('/<td class="py-3 px-6 text-left">\{\{\s*\\$\s*\?\?\s*\'-\'\s*\}\}<\/td>\s*/', '', $content);
        
        // Add header
        if (!str_contains($content, '>Kode</th>')) {
            $content = preg_replace('/(<th[^>]*>No\.<\/th>\s*)/', "$1<th class=\"py-3 px-4 font-semibold text-sm text-left\">Kode</th>\n                            ", $content);
        }
        
        // Extract the variable name loop
        preg_match('/@forelse\(\$([a-zA-Z0-9_]+) as \$index => \$([a-zA-Z0-9_]+)\)/', $content, $matches);
        if (!$matches) {
            preg_match('/@foreach\(\$([a-zA-Z0-9_]+) as \$([a-zA-Z0-9_]+)\)/', $content, $matches);
        }
        $itemName = $matches[2] ?? 'item';

        if (!str_contains($content, '->code')) {
            $content = preg_replace('/(<td[^>]*>\{\{\s*\$'.$itemName.'->name\s*\}\}<\/td>)/', "<td class=\"py-3 px-4 text-left\">{{ $$itemName->code ?? '-' }}</td>\n                                $1", $content);
        }

        // Fix colspan
        $content = preg_replace('/colspan="3"/', 'colspan="4"', $content);
        
        file_put_contents($indexFile, $content);
        echo "Fixed $indexFile\n";
    }
}
