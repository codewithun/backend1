<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Store (One-to-One)
    public function store()
    {
        return $this->hasOne(Store::class);
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
