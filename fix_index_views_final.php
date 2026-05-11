<?php

$directories = ['activity_category', 'activity_format', 'activity_method', 'activity_scope', 'activity_type', 'batch', 'fund_source', 'material_type', 'profession-category'];
$baseDir = __DIR__.'/resources/views/';
foreach ($directories as $dir) {
    $indexFile = $baseDir.$dir.'/index.blade.php';
    if (file_exists($indexFile)) {
        $content = file_get_contents($indexFile);
        preg_match('/@forelse\(\$([a-zA-Z0-9_]+) as \$index => \$([a-zA-Z0-9_]+)\)/', $content, $matches);
        if (! $matches) {
            preg_match('/@foreach\(\$([a-zA-Z0-9_]+) as \$([a-zA-Z0-9_]+)\)/', $content, $matches);
        }
        $itemName = $matches[2] ?? 'item';

        $content = str_replace('{{ $ ?? \'-\' }}', '{{ $'.$itemName.'->code ?? \'-\' }}', $content);
        file_put_contents($indexFile, $content);
        echo "Fixed $dir\n";
    }
}
