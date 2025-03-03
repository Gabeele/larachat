<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

       $chat = Chat::factory()->create([
            'user_id' => $user->id,
        ]);

       ChatUser::factory()->create([
           'user_id' => $user->id,
           'chat_id' => $chat->id,
       ]);

    }
}
