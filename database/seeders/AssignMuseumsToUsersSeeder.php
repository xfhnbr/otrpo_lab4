<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Museum;
use App\Models\User;

class AssignMuseumsToUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $museums = Museum::all();
        
        foreach ($museums as $museum) {
            $randomUser = $users->random();
            $museum->user_id = $randomUser->id;
            $museum->save();
        }
    }
}