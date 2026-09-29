<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimoni extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'testimoni';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idTestimoni';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idUser',
        'idProduk',
        'rating',
        'komentar',
        'tanggal',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'tanggal' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the testimonial.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    /**
     * Get the product that owns the testimonial.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'idProduk', 'idProduk');
    }
}
