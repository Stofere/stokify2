<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Atribut extends Model
{
    protected $table = 'atribut';
    protected $primaryKey = 'id_atribut';

    protected $fillable = [
        'nama_atribut',
        'pilihan_opsi',
    ];

    protected $casts = [
        'pilihan_opsi' => 'array', // Otomatis konversi JSON ke Array PHP
    ];

    public function kategori(): BelongsToMany
    {
        return $this->belongsToMany(Kategori::class, 'kategori_atribut', 'id_atribut', 'id_kategori');
    }
}