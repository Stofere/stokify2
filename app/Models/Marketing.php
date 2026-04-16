<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marketing extends Model
{
    protected $table = 'marketing';
    protected $primaryKey = 'id_marketing';

    protected $fillable = [
        'nama',
        'telepon',
        'alamat',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function transaksiPenjualan(): HasMany
    {
        return $this->hasMany(TransaksiPenjualan::class, 'id_marketing', 'id_marketing');
    }
}
