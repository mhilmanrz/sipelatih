<?php

namespace App\Jobs;

use App\Imports\ActivityImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        Log::info('Mulai memproses job ImportActivityJob untuk file: '.$this->filePath);

        try {
            $absolutePath = storage_path('app/private/'.$this->filePath);
            Excel::import(new ActivityImport, $absolutePath);
            Log::info('Berhasil import Usulan Diklat. Menghapus file sementara: '.$this->filePath);
        } catch (\Exception $e) {
            Log::error('Gagal import Usulan Diklat: '.$e->getMessage());
        } finally {
            if (Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
        }
    }
}
