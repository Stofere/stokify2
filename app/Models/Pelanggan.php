<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey ='id_pelanggan';

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
        return $this->hasMany(TransaksiPenjualan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
