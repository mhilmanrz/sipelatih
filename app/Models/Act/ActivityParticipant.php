<?php

namespace App\Models\Act;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ActivityParticipant extends Model
{
    use HasFactory;

    protected $table = 'activity_participants';

    protected $fillable = [
        'activity_id',
        'user_id',
        'certificate_number',
        'is_passed',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function score()
    {
        return $this->hasOne(ActivityScore::class);
    }

    public function componentScores()
    {
        return $this->hasMany(ActivityComponentScore::class);
    }

    /**
     * Hitung nilai akhir berdasarkan komponen berpersentase (non pre-test).
     * Mengembalikan null jika belum ada komponen yang dikonfigurasi.
     */
    public function calculateFinalScore(): ?float
    {
        $components = $this->activity->scoreComponents ?? collect();
        $graded = $components->filter(fn ($c) => $c->type !== 'pre_test');

        if ($graded->isEmpty()) {
            return null;
        }

        $total = 0;
        foreach ($graded as $component) {
            $componentScore = $this->componentScores
                ->firstWhere('activity_score_component_id', $component->id);
            $score = $componentScore?->score ?? 0;
            $total += ($score * $component->percentage) / 100;
        }

        return round($total, 2);
    }

    /**
     * Get the matching grade category based on final score.
     * Returns null if no categories configured or final score is null.
     *
     * @param  Collection|null  $categories
     */
    public function getGradeCategory($categories = null): ?ActivityGradeCategory
    {
        $finalScore = $this->calculateFinalScore();

        if ($finalScore === null) {
            return null;
        }

        $categories = $categories ?? $this->activity->gradeCategories ?? collect();

        foreach ($categories as $category) {
            if ($category->matches($finalScore)) {
                return $category;
            }
        }

        return null;
    }
}
