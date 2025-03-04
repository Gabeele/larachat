<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChatUserFactory extends Factory
{
    protected $model = ChatUser::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'chat_id' => Chat::factory(),
        ];
    }
}
