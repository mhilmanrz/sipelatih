<?php

namespace App\Jobs;

use App\Imports\ActivityScoreImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportActivityScoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    public $activityId;

    public function __construct(string $filePath, $activityId)
    {
        $this->filePath = $filePath;
        $this->activityId = $activityId;
    }

    public function handle(): void
    {
        Log::info('Mulai memproses job ImportActivityScoreJob untuk file: '.$this->filePath.' kegiatan_id: '.$this->activityId);

        try {
            $absolutePath = storage_path('app/private/'.$this->filePath);
            Excel::import(new ActivityScoreImport($this->activityId), $absolutePath);
            Log::info('Berhasil import nilai. Menghapus file sementara: '.$this->filePath);
        } catch (\Exception $e) {
            Log::error('Gagal import nilai: '.$e->getMessage());
        } finally {
            if (Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
        }
    }
}
