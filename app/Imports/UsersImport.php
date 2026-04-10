<?php

namespace App\Imports;

use App\Models\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ShouldQueue, ToModel, WithChunkReading, WithHeadingRow
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        // Require name and email
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        // Check if user already exists
        $user = User::where('email', $row['email'])->first();
        if ($user) {
            return null;
        }

        return new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => bcrypt('password123'),
            'phone_number' => $row['telepon'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
