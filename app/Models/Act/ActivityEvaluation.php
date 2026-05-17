<?php

namespace App\Models\Act;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityEvaluation extends Model
{
    use HasFactory;

    protected $table = 'activity_evaluations';

    protected $fillable = [
        'activity_id',
        'evaluation_type',
        'is_passed',
        'notes',
        'evaluated_by',
        'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_type' => 'integer',
            'is_passed' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function evaluationCriteria(): HasMany
    {
        return $this->hasMany(ActivityEvaluationCriteria::class, 'activity_evaluation_id');
    }

    public function criteriaValues(): HasMany
    {
        return $this->hasMany(ActivityEvaluationCriteria::class, 'activity_evaluation_id');
    }

    public static function getLevels(): array
    {
        return [
            1 => [
                'label' => 'Evaluasi Penyelenggaraan',
                'sub' => 'Tingkat 1 · Kepuasan & Sarana',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            ],
            2 => [
                'label' => 'Evaluasi Hasil Belajar',
                'sub' => 'Tingkat 2 · Nilai & JPL',
                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            ],
            3 => [
                'label' => 'Evaluasi Dampak',
                'sub' => 'Tingkat 3 · Kinerja Pasca Pelatihan',
                'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
            ],
        ];
    }
}
