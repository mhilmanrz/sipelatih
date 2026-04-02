<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User\User::find(1);
$participants = $user->activityParticipants()->where('is_passed', true)->with('activity.activityMaterials')->get()->unique('activity_id');

$total = 0;
foreach($participants as $p) {
    if ($p->activity) {
        $sum = $p->activity->activityMaterials->sum('value');
        echo "Activity ID: {$p->activity_id}, Sum Materials: {$sum}\n";
        $total += $sum;
    }
}
echo "Total JPL for Admin: {$total}\n";
