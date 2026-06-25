<?php

namespace App\Jobs;

use App\Imports\ParticipantImport;
use App\Models\Act\ParticipantImportLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportParticipantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    public $activityId;

    public $logId;

    public function __construct(string $filePath, $activityId, $logId = null)
    {
        $this->filePath = $filePath;
        $this->activityId = $activityId;
        $this->logId = $logId;
    }

    public function handle(): void
    {
        Log::info('Mulai memproses job ImportParticipantJob untuk file: '.$this->filePath.' kegiatan_id: '.$this->activityId.' log_id: '.$this->logId);

        $log = $this->logId ? ParticipantImportLog::find($this->logId) : null;

        if ($log) {
            $log->update(['status' => 'processing']);
        }

        try {
            $absolutePath = storage_path('app/private/'.$this->filePath);
            Excel::import(new ParticipantImport($this->activityId, $this->logId), $absolutePath);
            Log::info('Berhasil import Peserta. Menghapus file sementara: '.$this->filePath);
        } catch (\Exception $e) {
            Log::error('Gagal import Peserta: '.$e->getMessage());
            if ($log) {
                $log->update([
                    'status' => 'failed',
                    'errors' => [
                        [
                            'row' => '-',
                            'nip' => '-',
                            'reason' => 'Gagal membaca atau memproses file Excel: '.$e->getMessage(),
                        ],
                    ],
                ]);
            }
        } finally {
            if (Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
        }
    }
}
