<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_usaha',
        'jenis_usaha',
        'alamat',
        'gambar',
        'user_id', // Foreign key untuk relasi dengan User
    ];

    // Relasi ke User (One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tiket (One-to-Many)
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    // Relasi ke Produk (One-to-Many)
    public function products()
    {
        return $this->hasMany(Addproduct::class);
    }
}
