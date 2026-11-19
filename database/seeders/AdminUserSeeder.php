<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Akun admin pertama. Atur lewat .env:
     *   ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD
     * Jika ADMIN_PASSWORD kosong, kata sandi acak dibuat dan ditampilkan SEKALI di terminal.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@bpp-routa.test');

        if (User::where('email', $email)->exists()) {
            $this->command?->info("Akun {$email} sudah ada, dilewati.");

            return;
        }

        $password = env('ADMIN_PASSWORD');
        $generated = false;

        if (! $password) {
            $password = Str::password(14, symbols: false);
            $generated = true;
        }

        User::create([
            'name' => env('ADMIN_NAME', 'Administrator BPP Routa'),
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command?->info("Akun admin dibuat: {$email}");

        if ($generated) {
            $this->command?->warn("Kata sandi sementara: {$password}  (catat, lalu segera ganti setelah login)");
        }
    }
}
