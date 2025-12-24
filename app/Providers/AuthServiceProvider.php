<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Museum;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate для проверки права редактирования музея
        Gate::define('update-museum', function (User $user, Museum $museum) {
            // Пользователь может редактировать только созданные им музеи
            return $user->id === $museum->user_id;
        });

        // Gate для проверки права удаления музея
        Gate::define('delete-museum', function (User $user, Museum $museum) {
            // Пользователь может удалять только созданные им музеи
            return $user->id === $museum->user_id;
        });

        // Gate для проверки права восстановления музея
        Gate::define('restore-museum', function (User $user) {
            // Только администратор может восстанавливать
            return $user->is_admin === true;
        });

        // Gate для проверки права полного удаления музея
        Gate::define('force-delete-museum', function (User $user) {
            // Только администратор может полностью удалять
            return $user->is_admin === true;
        });

        // Gate для проверки права просмотра корзины
        Gate::define('view-trash', function (User $user) {
            // Только администратор может просматривать корзину
            return $user->is_admin === true;
        });

        // Gate для проверки права очистки всей корзины
        Gate::define('force-delete-all', function (User $user) {
            // Только администратор может очищать всю корзину
            return $user->is_admin === true;
        });
    }
}