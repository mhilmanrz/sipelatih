<?php

namespace App\Models\User;

use App\Models\Act\Activity;
use App\Models\Act\ActivityParticipant;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'users';

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $appends = [
        'nip',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'work_unit_id',
        'position_id',
        'employment_type_id',
        'profession_id',
        'rank_id',
        'employee_id',
        'phone_number',
        'npwp',
        'bank_name',
        'account_number',
        'is_external',
        'nik',
        'institution',
        'external_position',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'is_external' => 'boolean',
        ];
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function position()
    {
        return $this->belongsTo(Positions::class);
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function activityParticipants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function getNipAttribute(): ?string
    {
        return $this->employee_id;
    }

    public function isPegawai(): bool
    {
        return ! $this->hasAnyRole(['superadmin', 'perencanaan', 'penyelenggara', 'evaluasi', 'pengusul']);
    }

    /**
     * The value shown in the "Pangkat/Gol." column of Surat Tugas narasumber
     * tables: an internal user's rank, or an external person's institution.
     */
    public function documentRankOrInstitution(): string
    {
        return $this->is_external ? ($this->institution ?? '-') : ($this->rank?->name ?? '-');
    }

    /**
     * The value shown in the "Jabatan" column of Surat Tugas narasumber
     * tables: an internal user's position, or an external person's
     * position at their institution.
     */
    public function documentPosition(): string
    {
        return $this->is_external ? ($this->external_position ?? '-') : ($this->position?->name ?? '-');
    }

    /**
     * The single stage role this user acts as in the kegiatan approval flow, if any.
     */
    public function stageRole(): ?string
    {
        foreach (['superadmin', 'pengusul', 'perencanaan', 'penyelenggara', 'evaluasi'] as $role) {
            if ($this->hasRole($role)) {
                return $role;
            }
        }

        return null;
    }
}
