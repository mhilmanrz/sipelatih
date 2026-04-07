<?php

namespace App\Jobs;

use App\Imports\UsersImport;
use App\Notifications\ImportUsersCompleted;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ImportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300; // 5 menit

    public function __construct(
        protected string $filePath,
        protected int $userId,
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        try {
            $import = new UsersImport();
            Excel::import($import, Storage::path($this->filePath));

            $count = $import->getRowCount();

            if ($user) {
                $user->notify(new ImportUsersCompleted(
                    success: true,
                    count: $count,
                ));
            }

            // Hapus file hanya setelah berhasil
            Storage::delete($this->filePath);

        } catch (\Throwable $e) {
            if ($user) {
                $user->notify(new ImportUsersCompleted(
                    success: false,
                    count: 0,
                    message: $e->getMessage(),
                ));
            }

            // Hapus file juga saat gagal permanen (tidak akan diretry lagi)
            if ($this->attempts() >= $this->tries) {
                Storage::delete($this->filePath);
            }

            // Re-throw agar Laravel bisa retry / tandai failed
            throw $e;
        }
    }
}
