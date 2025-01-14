<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'namaTiket',
        'stok',
        'hargaJual',
        'keterangan',
        'user_id', // Foreign key untuk relasi ke User
        'store_id', // Foreign key untuk relasi ke Store
    ];

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Store (Many-to-One)
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
