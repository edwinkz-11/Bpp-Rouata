<?php

namespace App\Providers;

use App\Models\Profile;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        // Pembatas laju untuk halaman publik. Pesan kontak dibatasi ketat untuk menahan spam.
        RateLimiter::for('public', fn (Request $r) => Limit::perMinute(120)->by($r->ip()));
        RateLimiter::for('search', fn (Request $r) => Limit::perMinute(30)->by($r->ip()));
        RateLimiter::for('contact', fn (Request $r) => [
            Limit::perMinute(2)->by($r->ip()),
            Limit::perHour(8)->by($r->ip()),
        ]);

        // Hanya Admin yang mengelola akun pengguna dan data struktural (desa, kategori, penyuluh).
        Gate::define('manage-users', fn (User $user) => $user->isAdmin());
        Gate::define('manage-structure', fn (User $user) => $user->isAdmin());

        // Admin boleh menghapus apa saja; Editor hanya data yang ia buat sendiri.
        Gate::define('delete-record', function (User $user, $model) {
            return $user->isAdmin()
                || (isset($model->created_by) && (int) $model->created_by === (int) $user->id);
        });

        // Data bersama ($bpp, $site) untuk halaman publik & admin.
        // Harus mencakup view anak (web.*, admin.*), bukan hanya layout, karena
        // bagian @section view anak dirender sebelum layout dibuat.
        $shared = null;
        $resolve = function () use (&$shared) {
            if ($shared !== null) {
                return $shared;
            }

            try {
                return $shared = ['bpp' => Profile::bpp(), 'site' => Setting::allKeyed()];
            } catch (\Throwable $e) {
                // Database belum dimigrasi: pakai nilai bawaan agar halaman tidak error.
                return $shared = [
                    'bpp' => new Profile([
                        'name' => 'BPP Routa',
                        'institution_name' => 'Balai Penyuluhan Pertanian Kecamatan Routa',
                    ]),
                    'site' => [],
                ];
            }
        };

        View::composer(['layouts.public', 'web.*', 'admin.*'], fn ($view) => $view->with($resolve()));
    }
}
