<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('menu-admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('menu-siswa', function ($user) {
            return $user->role === 'siswa';
        });

        // Route /admin/anggota/{anggota} hanya boleh mengenali user ber-role 'siswa'.
        // Tanpa ini, mengganti ID di URL bisa mengedit atau menghapus akun admin
        // (termasuk akun admin yang sedang login).
        Route::bind('anggota', function (string $value) {
            return User::where('role', 'siswa')->findOrFail($value);
        });
    }
}
