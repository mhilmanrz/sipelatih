<?php

namespace App\Imports;

use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class AccountsImport implements ShouldQueue, ToModel, WithChunkReading, WithHeadingRow
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        $workUnitId = $this->resolveWorkUnitId($row['unit_kerja'] ?? null);
        $password = ! empty($row['password']) ? $row['password'] : 'password123';

        $user = new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make($password),
            'work_unit_id' => $workUnitId,
        ]);

        $user->save();

        if (! empty($row['role'])) {
            $role = Role::where('name', $row['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        // Return null because we already saved and assigned role manually
        return null;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function resolveWorkUnitId(?string $name): ?int
    {
        if (blank($name)) {
            return null;
        }

        $workUnit = WorkUnit::where('name', $name)->first();

        return $workUnit?->id;
    }
}
