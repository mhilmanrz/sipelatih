<?php

namespace App\Models;

use App\Models\Act\Activity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    protected $fillable = [
        'budget_category_id',
        'year',
        'rkkal_code',
        'submark',
        'total_amount',
        'blokir',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'blokir' => 'integer',
        ];
    }

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Pagu Efektif = Pagu Awal - Blokir
     */
    protected function efektifAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_amount - $this->blokir,
        );
    }

    /**
     * Realisasi Pagu = Akumulasi budget_amount dari seluruh kegiatan yang terikat.
     */
    protected function realisasiAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activities()->sum('budget_amount'),
        );
    }

    /**
     * Sisa Pagu = Pagu Efektif - Realisasi Pagu
     */
    protected function sisaAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->efektif_amount - $this->realisasi_amount,
        );
    }

    /**
     * @deprecated Gunakan sisa_amount. Dipertahankan untuk kompatibilitas mundur.
     */
    public function getRemainingAmountAttribute(): int
    {
        return $this->sisa_amount;
    }
}
