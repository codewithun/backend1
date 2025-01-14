<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat pengguna baru
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'), // Pastikan untuk mengenkripsi password
        ]);

        // Jika Anda ingin membuat token untuk pengguna ini, lakukan hal berikut:
        $token = $user->createToken('Flexy')->plainTextToken;

        // Tampilkan token (opsional)
        echo "Token untuk {$user->email}: {$token}\n";
    }
}
