<?php

namespace App\Jobs;

use App\Imports\ActivityPerParticipantImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportActivityParticipantJob implements ShouldQueue
{
    use Queueable;

    public $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Storage::disk('local')->exists($this->filePath)) {
            Excel::import(new ActivityPerParticipantImport, Storage::disk('local')->path($this->filePath));
            Storage::disk('local')->delete($this->filePath); // cleanup after import
        }
    }
}
