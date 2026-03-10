<?php

namespace App\Imports;

use App\Models\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
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
            'name'         => $row['nama'],
            'email'        => $row['email'],
            'password'     => bcrypt('password123'),
            'phone_number' => $row['telepon'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
