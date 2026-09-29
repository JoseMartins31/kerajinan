<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promosi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promosi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idPromosi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_promosi',
        'idProduk',
        'tanggal_mulai',
        'tanggal_akhir',
        'tanggal_selesai',
        'persentase_diskon',
        'diskon',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_akhir' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'diskon' => 'float',
            'persentase_diskon' => 'float',
        ];
    }

    /**
     * Get the product that owns the promotion.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'idProduk', 'idProduk');
    }

    /**
     * Check if the promotion is currently active
     */
    public function isActive(): bool
    {
        $now = now();
        $endDate = $this->tanggal_akhir ?? $this->tanggal_selesai;

        return $this->status === 'aktif' &&
            $this->tanggal_mulai <= $now &&
            $endDate >= $now;
    }

    /**
     * Check if the promotion is expired
     */
    public function isExpired(): bool
    {
        $endDate = $this->tanggal_akhir ?? $this->tanggal_selesai;
        return $endDate < now();
    }

    /**
     * Check if the promotion is scheduled (future)
     */
    public function isScheduled(): bool
    {
        return $this->tanggal_mulai > now();
    }

    /**
     * Get the promotion status display text
     */
    public function getStatusDisplay(): string
    {
        if ($this->isExpired()) {
            return 'Berakhir';
        } elseif ($this->isScheduled()) {
            return 'Terjadwal';
        } elseif ($this->isActive()) {
            return 'Aktif';
        } else {
            return 'Tidak Aktif';
        }
    }

    /**
     * Get the promotion status class for styling
     */
    public function getStatusClass(): string
    {
        if ($this->isExpired()) {
            return 'danger';
        } elseif ($this->isScheduled()) {
            return 'primary';
        } elseif ($this->isActive()) {
            return 'success';
        } else {
            return 'secondary';
        }
    }

    /**
     * Get discount percentage (handle both column names)
     */
    public function getDiscountPercentage(): float
    {
        return $this->persentase_diskon ?? $this->diskon ?? 0;
    }

    /**
     * Get end date (handle both column names)
     */
    public function getEndDate()
    {
        return $this->tanggal_selesai ?? $this->tanggal_akhir;
    }
}
