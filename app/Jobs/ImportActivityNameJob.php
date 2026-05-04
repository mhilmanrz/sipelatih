<?php

namespace App\Jobs;

use App\Imports\ActivityNameImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportActivityNameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        Log::info('Mulai memproses job ImportActivityNameJob untuk file: '.$this->filePath);

        try {
            $absolutePath = storage_path('app/private/'.$this->filePath);
            Excel::import(new ActivityNameImport, $absolutePath);
            Log::info('Berhasil import Kamus Nama Pelatihan. Menghapus file sementara: '.$this->filePath);
        } catch (\Exception $e) {
            Log::error('Gagal import Kamus Nama Pelatihan: '.$e->getMessage());
        } finally {
            if (Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
        }
    }
}
