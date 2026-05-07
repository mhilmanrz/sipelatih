<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    \App\Models\Act\ActivityCategory::class,
    \App\Models\Act\ActivityFormat::class,
    \App\Models\Act\ActivityMethod::class,
    \App\Models\Act\ActivityScope::class,
    \App\Models\Act\ActivityType::class,
    \App\Models\Act\Batch::class,
    \App\Models\Act\FundSource::class,
    \App\Models\Act\MaterialType::class,
    \App\Models\Act\TargetParticipant::class,
    \App\Models\User\ProfessionCategory::class,
];

foreach ($models as $model) {
    $reflection = new ReflectionClass($model);
    $file = $reflection->getFileName();
    $content = file_get_contents($file);
    if (!str_contains($content, "'code'")) {
        $content = str_replace("'name'", "'code', 'name'", $content);
        file_put_contents($file, $content);
        echo $model . " updated.\n";
    }
}
