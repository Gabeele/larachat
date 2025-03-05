<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Tom Klairing',
            'email' => 'tom@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Bess Marvins',
            'email' => 'bess@example.com',
        ]);

       $chat = Chat::factory()->create([
            'user_id' => $user1->id,
        ]);

        Message::factory()->count(300)->create([
            'chat_id' => $chat->id,
            'user_id' => fn() => Arr::random([$user1->id, $user2->id]),
        ]);

        $chat->users()->attach([$user2->id, $user1->id]);


    }
}
