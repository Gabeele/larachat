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
        $user1 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user1@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user2@example.com',
        ]);

       $chat = Chat::factory()->create([
            'user_id' => $user1->id,
        ]);

       $chat->users()->attach([$user2->id, $user1->id]);


    }
}
