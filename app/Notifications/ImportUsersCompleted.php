<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ImportUsersCompleted extends Notification
{
    use Queueable;

    public function __construct(
        protected bool $success,
        protected int $count,
        protected string $message = '',
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        if ($this->success) {
            return [
                'status'  => 'success',
                'title'   => 'Import Pegawai Selesai',
                'message' => "Berhasil mengimpor {$this->count} pegawai baru.",
                'icon'    => '✅',
            ];
        }

        return [
            'status'  => 'error',
            'title'   => 'Import Pegawai Gagal',
            'message' => 'Terjadi kesalahan saat memproses file import. ' . $this->message,
            'icon'    => '❌',
        ];
    }
}
