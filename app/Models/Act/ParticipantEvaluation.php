<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ParticipantEvaluation extends Model
{
    protected $fillable = [
        'activity_participant_id',
        'evaluation_type',
        'form_type',
        'activity_speaker_id',
        'supervisor_recommendation',
        'token',
        'submitted_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_type' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    protected static function booting(): void
    {
        static::creating(function ($model) {
            if (! $model->token) {
                $model->token = Str::random(64);
            }
        });
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(ActivityParticipant::class, 'activity_participant_id');
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(ActivitySpeaker::class, 'activity_speaker_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ParticipantEvaluationAnswer::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ParticipantEvaluationFile::class);
    }

    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }
}
