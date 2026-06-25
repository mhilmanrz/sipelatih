<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantImportLog extends Model
{
    use HasFactory;

    protected $table = 'participant_import_logs';

    protected $fillable = [
        'activity_id',
        'filename',
        'status',
        'total_rows',
        'success_count',
        'failed_count',
        'errors',
    ];

    /**
     * Get the casts for the model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'errors' => 'array',
        ];
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
