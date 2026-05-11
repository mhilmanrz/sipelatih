<?php

use App\Models\Act\ActivityCategory;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityScope;
use App\Models\Act\ActivityType;
use App\Models\Act\Batch;
use App\Models\Act\FundSource;
use App\Models\Act\MaterialType;
use App\Models\Act\TargetParticipant;
use App\Models\User\ProfessionCategory;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$models = [
    ActivityCategory::class,
    ActivityFormat::class,
    ActivityMethod::class,
    ActivityScope::class,
    ActivityType::class,
    Batch::class,
    FundSource::class,
    MaterialType::class,
    TargetParticipant::class,
    ProfessionCategory::class,
];

foreach ($models as $model) {
    $reflection = new ReflectionClass($model);
    $file = $reflection->getFileName();
    $content = file_get_contents($file);
    if (! str_contains($content, "'code'")) {
        $content = str_replace("'name'", "'code', 'name'", $content);
        file_put_contents($file, $content);
        echo $model." updated.\n";
    }
}
