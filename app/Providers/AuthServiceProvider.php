<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate untuk mengelola users (hanya super_admin dan admin)
        Gate::define('manage-users', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin']);
        });

        // Gate untuk mengelola master data (hanya super_admin dan admin)
        Gate::define('manage-master', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin']);
        });

        // Gate untuk mengelola HPP (hanya super_admin dan admin)
        Gate::define('manage-hpp', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin']);
        });

        // Gate untuk mengelola Service (hanya super_admin dan admin)
        Gate::define('manage-service', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin']);
        });

        // Gate untuk melihat HPP (semua role)
        Gate::define('view-hpp', function (User $user) {
            return true;
        });

        // Gate untuk melihat Service (semua role)
        Gate::define('view-service', function (User $user) {
            return true;
        });

        // Gate untuk super admin only
        Gate::define('super-admin', function (User $user) {
            return $user->role === 'super_admin';
        });

        // Gate untuk admin atau lebih tinggi
        Gate::define('admin-or-higher', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });
    }
}
