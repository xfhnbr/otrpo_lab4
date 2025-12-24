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
        // Gate для проверки права редактирования музея
        Gate::define('update-museum', function ($user, $museum) {
            // Пользователь может редактировать только созданные им музеи
            // Администратор может редактировать все
            return $user->id === $museum->user_id;
        });

        // Gate для проверки права удаления музея
        Gate::define('delete-museum', function ($user, $museum) {
            // Пользователь может удалять только созданные им музеи
            // Администратор может удалять все
            return $user->id === $museum->user_id || $user->is_admin === true;
        });

        // Gate для проверки права восстановления музея
        Gate::define('restore-museum', function ($user) {
            // Только администратор может восстанавливать
            return $user->is_admin === true;
        });

        // Gate для проверки права полного удаления музея
        Gate::define('force-delete-museum', function ($user) {
            // Только администратор может полностью удалять
            return $user->is_admin === true;
        });

        // Gate для проверки права просмотра корзины
        Gate::define('view-trash', function ($user) {
            // Только администратор может просматривать корзину
            return $user->is_admin === true;
        });

        // Gate для проверки права очистки всей корзины
        Gate::define('force-delete-all', function ($user) {
            // Только администратор может очищать всю корзину
            return $user->is_admin === true;
        });

        // Gate для проверки, является ли пользователь администратором
        Gate::define('admin-access', function ($user) {
            return $user->is_admin === true;
        });
    }
}