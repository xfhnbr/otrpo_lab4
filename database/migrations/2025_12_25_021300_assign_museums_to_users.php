<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Museum;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        // Получаем всех пользователей
        $users = User::all();
        
        if ($users->count() > 0) {
            // Назначаем каждому музею случайного пользователя
            $museums = Museum::all();
            foreach ($museums as $museum) {
                $museum->user_id = $users->random()->id;
                $museum->save();
            }
        }
    }

    public function down()
    {
        // Отменить назначение
        Museum::query()->update(['user_id' => null]);
    }
};