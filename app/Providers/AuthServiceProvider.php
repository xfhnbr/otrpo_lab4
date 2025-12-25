<?php

namespace App\Providers;

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
        Gate::define('update-museum', function ($user, $museum) {
            // пользователь может редактировать только созданные им музеи
            // администратор может редактировать все
            return $user->id === $museum->user_id && !$museum->trashed() || $user->is_admin === true;
        });

        Gate::define('delete-museum', function ($user, $museum) {
            // пользователь может удалять только созданные им музеи
            // администратор может удалять все
            return !$museum->trashed() && ($user->id === $museum->user_id || $user->is_admin === true);
        });

        Gate::define('restore-museum', function ($user) {
            // только администратор может восстанавливать
            return $user->is_admin === true;
        });

        Gate::define('force-delete-museum', function ($user) {
            // только администратор может полностью удалять
            return $user->is_admin === true;
        });

        Gate::define('view-trash', function ($user) {
            // только администратор может просматривать корзину
            return $user->is_admin === true;
        });

        Gate::define('force-delete-all', function ($user) {
            // только администратор может очищать всю корзину
            return $user->is_admin === true;
        });

        Gate::define('admin-access', function ($user) {
            return $user->is_admin === true;
        });
    }
}